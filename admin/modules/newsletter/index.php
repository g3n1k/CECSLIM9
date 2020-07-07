<?php
/*
 * module newsletter
 */

// key to authenticate
define('INDEX_AUTH', '1');
// key to get full database access
define('DB_ACCESS', 'fa');

// main system configuration
require '../../../sysconfig.inc.php';
// IP based access limitation
//require LIB.'ip_based_access.inc.php';
//do_checkIP('smc');
//do_checkIP('smc-masterfile');
// start the session
require SB.'admin/default/session.inc.php';
require SB.'admin/default/session_check.inc.php';
require SIMBIO.'simbio_GUI/table/simbio_table.inc.php';
require SIMBIO.'simbio_GUI/form_maker/simbio_form_table_AJAX.inc.php';
require SIMBIO.'simbio_GUI/paging/simbio_paging.inc.php';
require SIMBIO.'simbio_DB/datagrid/simbio_dbgrid.inc.php';
require SIMBIO.'simbio_DB/simbio_dbop.inc.php';
require SIMBIO.'simbio_FILE/simbio_file_upload.inc.php';

// privileges checking
$can_read = utility::havePrivilege('newsletter', 'r');
$can_write = utility::havePrivilege('newsletter', 'w');

if (!$can_read) {
    die('<div class="errorBox">'.__('You don\'t have enough privileges to access this area!').'</div>');
}

$in_pop_up = false;
// check if we are inside pop-up window
if (isset($_GET['inPopUp'])) {
	$in_pop_up = true;
}


/* RECORD OPERATION */
if (isset($_POST['saveData']) AND $can_read AND $can_write) {

	$title = trim(strip_tags($_POST['title']));
	$year  = trim(strip_tags($_POST['year']));
	$month  = trim(strip_tags($_POST['month']));

	// check form validity
    if (empty($title) OR empty($year) OR empty($month)) {
        utility::jsAlert(__('Judul, Tahun atau Bulan Tidak Boleh kosong'));
        exit();
    } else {

		$data['title'] 	= trim($dbs->escape_string(strip_tags($_POST['title'])));
		$data['year'] 	= trim($dbs->escape_string(strip_tags($_POST['year'])));
		$data['month'] 	= trim($dbs->escape_string(strip_tags($_POST['month'])));
		
		// image cover uploading
        if (!empty($_FILES['cover']) AND $_FILES['cover']['size']) {
			// create upload object
            $cover_upload = new simbio_file_upload();
            $cover_upload->setAllowableFormat($sysconf['allowed_images']);
            $cover_upload->setMaxSize($sysconf['max_image_upload']*1024);
			$cover_upload->setUploadDir('../../../newsletter/');
			
			// upload the file and change all space characters to underscore
            $cvr_upload_status = $cover_upload->doUpload('cover', preg_replace('@\s+@i', '_', $_FILES['cover']['name']));
            
            if ($cvr_upload_status == UPLOAD_SUCCESS) {
                $data['cover'] = $dbs->escape_string($cover_upload->new_filename);
                // write log
                utility::writeLogs($dbs, 'staff', $_SESSION['uid'], 'NewsLetter', $_SESSION['realname'].' upload cover image file '.$cover_upload->new_filename);
                #utility::jsAlert(__('Cover Image Uploaded Successfully'));
            } else {
                // write log
                utility::writeLogs($dbs, 'staff', $_SESSION['uid'], 'NewsLetter', 'ERROR : '.$_SESSION['realname'].' FAILED TO upload cover image file '.$cover_upload->new_filename.', with error ('.$cover_upload->error.')');
                utility::jsAlert(__('Cover Image Uploaded Failed'));
            }
		}

		// file attachment uploading
		if (isset($_FILES['attachment']) AND $_FILES['attachment']['size']) {
			
			// create upload object
            $att_upload = new simbio_file_upload();
            $att_upload->setAllowableFormat($sysconf['allowed_file_att']);
            $att_upload->setMaxSize($sysconf['max_upload']*1024);
			$att_upload->setUploadDir('../../../newsletter/');

			// upload the file and change all space characters to underscore
            $att_upload_status = $att_upload->doUpload('attachment', preg_replace('@\s+@i', '_', $_FILES['attachment']['name']));
            
			if ($att_upload_status == UPLOAD_SUCCESS) {
                $data['attachment'] = $dbs->escape_string($att_upload->new_filename);
                // write log
                utility::writeLogs($dbs, 'staff', $_SESSION['uid'], 'NewsLetter', $_SESSION['realname'].' upload Attachment file '.$att_upload->new_filename);
                #utility::jsAlert(__('Attachment Uploaded Successfully'));
            } else {
                // write log
                utility::writeLogs($dbs, 'staff', $_SESSION['uid'], 'NewsLetter', 'ERROR : '.$_SESSION['realname'].' FAILED TO upload Attachment file '.$att_upload->new_filename.', with error ('.$att_upload->error.')');
                utility::jsAlert(__('Attachment Uploaded Failed'));
			}
		}
    
		// create sql op object
        $sql_op = new simbio_dbop($dbs);
        if (isset($_POST['updateRecordID'])) {
			/* UPDATE RECORD MODE */
			// filter update record ID
            $updateRecordID = (integer)$_POST['updateRecordID'];
			// update data
			$update = $sql_op->update('newsletter', $data, 'newsletter_id='.$updateRecordID);
			if ($update) {
				utility::jsAlert(__('NewsLetter Data Successfully Updated'));
				utility::writeLogs($dbs, 'staff', $_SESSION['uid'], 'NewsLetter', $_SESSION['realname'].' update NewsLetter data ('.$data['title'].') with newsletter_id ('.$_POST['itemID'].')');
				}
            else 
				utility::jsAlert(__('NewsLetter Data FAILED to Updated. Please Contact System Administrator')."\n".$sql_op->error); 
		     
		}  else {
			/* INSERT RECORD MODE */
			$insert = $sql_op->insert('newsletter', $data);
			$last_newsletter_id = $sql_op->insert_id;
            if ($insert) {
				utility::jsAlert(__('New NewsLetter Data Successfully Saved'));
                // write log
                utility::writeLogs($dbs, 'staff', $_SESSION['uid'], 'NewsLetter', $_SESSION['realname'].' insert NewsLetter data ('.$data['title'].') with newsletter_id ('.$last_newsletter_id.')');
                
			} else {
				utility::jsAlert(__('NewsLetter Data FAILED to Save. Please Contact System Administrator')."\n".$sql_op->error);
			}
		}

	}

	echo '<script type="text/javascript">parent.$(\'#mainContent\').simbioAJAX(\''.$_SERVER['PHP_SELF'].'\', {addData: \''.$_POST['lastQueryStr'].'\'});</script>';
	
	exit();
    
} else if (isset($_POST['itemID']) AND !empty($_POST['itemID']) AND isset($_POST['itemAction'])) {
	if (!($can_read AND $can_write)) {
        die();
    }
    /* DATA DELETION PROCESS */
    // create sql op object
    $sql_op = new simbio_dbop($dbs);
    $failed_array = array();
    $error_num = 0;
    $still_have_item = array();
    if (!is_array($_POST['itemID'])) {
        $_POST['itemID'] = array((integer)$_POST['itemID']);
	}
	
	// loop array
    $http_query = '';
    foreach ($_POST['itemID'] as $itemID) {
		$itemID = (integer)$itemID;
		if (true) {
            if (!$sql_op->delete('newsletter', "newsletter_id=$itemID")) {
                $error_num++;
            } else {
                // write log
                utility::writeLogs($dbs, 'staff', $_SESSION['uid'], 'NewsLetter', $_SESSION['realname'].' DELETE NewsLetter data with newsletter_id ('.$itemID.')');
				
				$http_query .= "itemID[]=$itemID&";
            }
        } else {
			$still_have_item[] = 'still have '.$itemID.' copies';
            $error_num++;        
		}
	}

	// error alerting
    if ($error_num == 0) {
        utility::jsAlert(__('All Data Successfully Deleted'));
    } else {
        utility::jsAlert(__('Some or All Data NOT deleted successfully!\nPlease contact system administrator'));
    }
	
	echo '<script type="text/javascript">parent.$(\'#mainContent\').simbioAJAX(\''.$_SERVER['PHP_SELF'].'\', {addData: \''.$_POST['lastQueryStr'].'\'});</script>';
	
	exit();
}
/* RECORD OPERATION END */

/* main content */
$_btn = '<br />&nbsp; &nbsp;<div class="btn-group">';
$_btn .= '<a href="'.MWB.'newsletter/index.php" class="btn btn-default"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;'.__('Newsletter List').'</a>';
$_btn .= '&nbsp; &nbsp;';
$_btn .= '<a href="'.MWB.'newsletter/index.php?action=detail" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i>&nbsp;'. __('Add New Newsletter').'</a>';
$_btn .= '</div>';

// FORM
if (isset($_POST['detail']) OR (isset($_GET['action']) AND $_GET['action'] == 'detail')) {
    if (!($can_read AND $can_write)) {
        die('<div class="errorBox">'.__('You are not authorized to view this section').'</div>');
	}

	# set default value
	$itemID = (integer)isset($_POST['itemID'])?$_POST['itemID']:0;

	$_d_id  = $itemID;

	$_data  = array();

	$_sql_rec_q = sprintf('SELECT * FROM newsletter WHERE newsletter_id=%d', $_d_id);

	$rec_q = $dbs->query($_sql_rec_q);

	$_data = $rec_q->fetch_assoc();

	$_d_year    = isset($_data['year']) ? $_data['year'] : date('Y');

	$_d_title   = isset($_data['title']) ? $_data['title'] : '';

	$_d_month   = isset($_data['month']) ? $_data['month'] : date('n');

	$_month = array(
		array('1','Januari'),
		array('2','Februari'),
		array('3','Maret'),
		array('4','April'),
		array('5','Mei'),
		array('6','Juni'),
		array('7','Juli'),
		array('8','Agustus'),
		array('9','September'),
		array('10','Oktober'),
		array('11','November'),
		array('12','Desember')
	);

	$_d_cover =  isset($_data['cover']) ? "<a href='../newsletter/".$_data['cover']."' target='_blank'>[".$_data['cover']."]</a>" : '';
	$_d_attachment = isset($_data['attachment']) ? "<a href='../newsletter/".$_data['attachment']."' target='_blank'>[".$_data['attachment']."]</a>" : '';


	// create new instance
    $form = new simbio_form_table_AJAX('mainForm', $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'], 'post');
    $form->submit_button_attr = 'name="saveData" value="'.__('Save').'" class="button"';
    // form table attributes
    $form->table_attr = 'align="center" id="dataList" cellpadding="5" cellspacing="0"';
    $form->table_header_attr = 'class="alterCell" style="font-weight: bold;"';
	$form->table_content_attr = 'class="alterCell2"';

	// edit mode flag set
    if ($rec_q->num_rows > 0) {
		$form->edit_mode = true;
		if (!$in_pop_up) {
            // form record id
            $form->record_id = $itemID;
        } else {
            $form->addHidden('updateRecordID', $itemID);
            $form->addHidden('itemCollID', $_POST['itemCollID']);
            $form->back_button = false;
        }
        // form record title
        $form->record_title = $_d_title;
        // submit button attribute
        $form->submit_button_attr = 'name="saveData" value="'.__('Update').'" class="button"';
        // element visibility class toogle
        $visibility = 'makeHidden';

	}

	/* Form Element(s) */
	$form->addTextField('text', 'title', __('Judul'), $_d_title, 'style="width: 100%;"');
	$form->addTextField('text', 'year', __('Tahun'), $_d_year, ' maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,\'\')" ');
	$form->addSelectList('month', __('Bulan'), $_month, $_d_month);

	// newsletter cover image
    if (!trim($_d_cover)) {
        $str_input = simbio_form_element::textField('file', 'cover');
        $str_input .= ' Maximum '.$sysconf['max_image_upload'].' KB';
    } else {
		$str_input = "<a href='../newsletter/".$_data['cover']."' target='_blank'><strong>".$_data['cover']."</strong></a><br />";
        $str_input .= simbio_form_element::textField('file', 'cover');
        $str_input .= ' Maximum '.$sysconf['max_image_upload'].' KB';
    }
	$form->addAnything(__('Cover'), $str_input);

	// newsletter Attach file
    if (!trim($_d_attachment)) {
        $str_input = simbio_form_element::textField('file', 'attachment');
        $str_input .= ' Maximum '.$sysconf['max_upload'].' KB';
    } else {
		$str_input = "<a href='../newsletter/".$_data['attachment']."' target='_blank'><strong>".$_data['attachment']."</strong></a><br />";
        $str_input .= simbio_form_element::textField('file', 'attachment');
        $str_input .= ' Maximum '.$sysconf['max_upload'].' KB';
    }
	$form->addAnything(__('File Attachment'), $str_input);

	echo $_btn;
	echo $form->printOut();
}
// GRID LIST
else {
	// new instance table
	$datagrid = new simbio_datagrid();

	// set column

	$table_spec = 'newsletter AS pl';

	$datagrid->setSQLColumn('pl.newsletter_id',
	'pl.title AS \''.__('Title').'\'',
	'pl.year AS \''.__('Year').'\'',
	'pl.month AS \''.__('Month').'\'',
	'pl.cover AS \''.__('Cover').'\'',
	'pl.attachment AS \''.__('Attachment').'\'');

	// set table and table header attributes
    // set table and table header attributes
    $datagrid->table_attr = 'align="center" id="dataList" cellpadding="5" cellspacing="0"';
    $datagrid->table_header_attr = 'class="dataListHeader" style="font-weight: bold;"';
    // set delete proccess URL
    $datagrid->chbox_form_URL = $_SERVER['PHP_SELF'];

	$datagrid->debug = true;

	$datagrid_result = $datagrid->createDataGrid($dbs, $table_spec, 10, ($can_read AND $can_write));

	echo $_btn;
	echo $datagrid_result;
}

?>
