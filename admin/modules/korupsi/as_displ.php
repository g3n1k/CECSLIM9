<?php
/**
 * Copyright (C) 2007,2008  Arie Nugraha (dicarve@yahoo.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

/* Place Management section */

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

$query = "SELECT * FROM sarasehan_display";
$data = $dbs->query($query);
foreach ($data as $value) {
  $gbr[] = $value['img'];
};

?>
<fieldset class="menuBox">
  <div class="menuBoxInner masterFileIcon">
  	<div class="per_title">
      	<h2><?php echo __('Sarasehan On Landing Page'); ?></h2>
  	</div>
  </div>
</fieldset>
<div class="contentDesc baca">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-info">
          <div class="panel-body" style="min-height:470px;">

            <hr>
            <div class="biblioPaging pull-right">
              <span class="pagingList lis">
              </span>
            </div>
            <br>
            <section style="width:100%;overflow-x:scroll;">
              <table style="min-width:100%;">
                <tr>
                  <td>
                    <a href="javascript:void(0);" id="1" class="display_ss" data-value="1">
                      <img style="width:200px; height:133px;object-fit: cover;" src="../images/sarasehan/<?php echo $gbr[0] ?>" alt="" class="1">
                    </a>
                    <br>
                    No.1
                  </td>
                  <td>
                    <a href="javascript:void(0);" id="2" class="display_ss" data-value="2">
                      <img style="width:200px; height:133px;object-fit: cover;" src="../images/sarasehan/<?php echo $gbr[1] ?>" alt="" class="2">
                    </a>
                    <br>
                    No.2
                  </td>
                  <td rowspan="2">
                    <a href="javascript:void(0);" id="5" class="display_ss" data-value="5">
                      <img style="width:200px; height:333px;object-fit: cover;" src="../images/sarasehan/<?php echo $gbr[4] ?>" alt="" class="5">
                    </a>
                    <br>
                    No.5
                  </td>
                  <td rowspan="2">
                    <a href="javascript:void(0);" id="6" class="display_ss" data-value="6">
                      <img style="width:200px; height:333px;object-fit: cover;" src="../images/sarasehan/<?php echo $gbr[5] ?>" alt="" class="6">
                    </a>
                    <br>
                    No.6
                  </td>
                </tr>
                <tr>
                  <td>
                    <a href="javascript:void(0);" id="3" class="display_ss" data-value="3">
                      <img style="width:200px; height:133px;object-fit: cover;" src="../images/sarasehan/<?php echo $gbr[2] ?>" alt="" class="3">
                    </a>
                    <br>
                    No.3
                  </td>
                  <td>
                    <a href="javascript:void(0);" id="4" class="display_ss" data-value="4">
                      <img style="width:200px; height:133px;object-fit: cover;" src="../images/sarasehan/<?php echo $gbr[3] ?>" alt="" class="4">
                    </a>
                    <br>
                    No.4
                  </td>
                </tr>
              </table>
            </section>
            <hr>
            <section class="pilih" style="display:none;">
              <h4>You're about to change picture no <b class="no"></b></h4>
              <table style="width:100%;">
                <thead>
                  <tr class="dataListHeader" style="font-weight: bold; cursor: pointer; background-color: rgb(49, 53, 62);">
                    <td>Title</td>
                    <td>Picture</td>
                  </tr>
                </thead>
                <tbody class="list">

                </tbody>
              </table>
            </section>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
var idnya = '';
  $(document).on('click','.display_ss',function() {
    idnya = $(this).data('value');
    $.ajax({
      type : 'POST',
      url : '<?php echo MWB; ?>korupsi/as_ddata.php',
      data : {
        'idnya' : idnya
      },
      success : function(data) {
        $('.list').empty();
        $('.list').html(data);
        $('.pilih').slideDown();
        $('.no').html(idnya);
      }
    })
  })
  $(document).on('click','.gbrnya',function() {
    var ids = $(this).data('value');
    var pic = $(this).data('gbr');
    $.ajax({
      type : 'POST',
      url : '<?php echo MWB; ?>korupsi/as_dganti.php',
      data : {
        'idnya' : idnya,
        'ids' : ids,
        'pic': pic
      },
      success : function(data) {
        $('.pilih').slideUp();
        $('.'+idnya).attr('src','../images/sarasehan/'+pic);
      }
    })
  })
</script>

<?php
// privileges checking
// $can_read = utility::havePrivilege('korupsi', 'r');
// $can_write = utility::havePrivilege('korupsi', 'w');
//
// if (!$can_read) {
//     die('<div class="errorBox">'.__('You don\'t have enough privileges to access this area!').'</div>');
// }
//
// /* RECORD OPERATION */
// if (isset($_POST['saveData']) AND $can_read AND $can_write) {
//     $ownerName = trim(strip_tags($_POST['ownerName']));
//     // check form validity
//     if (empty($ownerName)) {
//         utility::jsAlert(__('Owner Name can\'t be empty')); //mfc
//         exit();
//     } else {
//         $data['owner_name'] = $dbs->escape_string($ownerName);
//         $data['input_date'] = date('Y-m-d');
//         $data['last_update'] = date('Y-m-d');
//
//         // create sql op object
//         $sql_op = new simbio_dbop($dbs);
//         if (isset($_POST['updateRecordID'])) {
//             /* UPDATE RECORD MODE */
//             // remove input date
//             unset($data['input_date']);
//             // filter update record ID
//             $updateRecordID = (integer)$_POST['updateRecordID'];
//             // update the data
//             $update = $sql_op->update('mst_owner', $data, 'owner_id='.$updateRecordID);
//             if ($update) {
//                 utility::jsAlert(__('Owner Data Successfully Updated'));
//                 echo '<script type="text/javascript">parent.jQuery(\'#mainContent\').simbioAJAX(parent.jQuery.ajaxHistory[0].url);</script>';
//             } else { utility::jsAlert(__('Owner Data FAILED to Updated. Please Contact System Administrator')."\nDEBUG : ".$sql_op->error); }
//             exit();
//         } else {
//             /* INSERT RECORD MODE */
//             // insert the data
//             $insert = $sql_op->insert('mst_owner', $data);
//             if ($insert) {
//                 utility::jsAlert(__('New Owner Data Successfully Saved'));
//                 echo '<script type="text/javascript">parent.jQuery(\'#mainContent\').simbioAJAX(\''.$_SERVER['PHP_SELF'].'\');</script>';
//             } else { utility::jsAlert(__('Owner Data FAILED to Save. Please Contact System Administrator')."\nDEBUG : ".$sql_op->error); }
//             exit();
//         }
//     }
//     exit();
// } else if (isset($_POST['itemID']) AND !empty($_POST['itemID']) AND isset($_POST['itemAction'])) {
//     if (!($can_read AND $can_write)) {
//         die();
//     }
//     /* DATA DELETION PROCESS */
//     $sql_op = new simbio_dbop($dbs);
//     $failed_array = array();
//     $error_num = 0;
//     if (!is_array($_POST['itemID'])) {
//         // make an array
//         $_POST['itemID'] = array((integer)$_POST['itemID']);
//     }
//     // loop array
//     foreach ($_POST['itemID'] as $itemID) {
//         $itemID = (integer)$itemID;
//         if (!$sql_op->delete('mst_owner', 'owner_id='.$itemID)) {
//             $error_num++;
//         }
//     }
//
//     // error alerting
//     if ($error_num == 0) {
//         utility::jsAlert(__('All Data Successfully Deleted'));
//         echo '<script type="text/javascript">parent.jQuery(\'#mainContent\').simbioAJAX(\''.$_SERVER['PHP_SELF'].'?'.$_POST['lastQueryStr'].'\');</script>';
//     } else {
//         utility::jsAlert(__('Some or All Data NOT deleted successfully!\nPlease contact system administrator'));
//         echo '<script type="text/javascript">parent.jQuery(\'#mainContent\').simbioAJAX(\''.$_SERVER['PHP_SELF'].'?'.$_POST['lastQueryStr'].'\');</script>';
//     }
//     exit();
// }
// /* RECORD OPERATION END */
//
// /* search form */
?>
<!-- // <fieldset class="menuBox">
// <div class="menuBoxInner masterFileIcon">
// 	<div class="per_title">
//     	<h2><?php echo __('Owner'); ?></h2>
// 	</div>
// 	<div class="sub_section">
// 	  <div class="btn-group">
// 	    <a href="<?php echo MWB; ?>korupsi/owner.php?action=detail" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo __('Add New Owner'); ?></a>
//         <a href="<?php echo MWB; ?>korupsi/owner.php" class="btn btn-default"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;<?php echo __('Owner List'); ?></a>
// 	  </div>
//
//     <form name="search" action="<?php echo MWB; ?>korupsi/owner.php" id="search" method="get" style="display: inline;"><?php echo __('Search'); ?> :
//     <input type="text" name="keywords" size="30" />
//     <input type="submit" id="doSearch" value="<?php echo __('Search'); ?>" class="button btn btn-primary" />
//     </form>
// 	</div>
// </div>
// </fieldset> -->
<?php
// /* search form end */
// /* main content */
// if (isset($_POST['detail']) OR (isset($_GET['action']) AND $_GET['action'] == 'detail')) {
//     if (!($can_read AND $can_write)) {
//         die('<div class="errorBox">'.__('You don\'t have enough privileges to access this area!').'</div>');
//     }
//     /* RECORD FORM */
//     $itemID = (integer)isset($_POST['itemID'])?$_POST['itemID']:0;
//     $rec_q = $dbs->query('SELECT * FROM mst_owner WHERE owner_id='.$itemID);
//     $rec_d = $rec_q->fetch_assoc();
//
//     // create new instance
//     $form = new simbio_form_table_AJAX('mainForm', $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'], 'post');
//     $form->submit_button_attr = 'name="saveData" value="'.__('Save').'" class="button"';
//
//     // form table attributes
//     $form->table_attr = 'align="center" id="dataList" cellpadding="5" cellspacing="0"';
//     $form->table_header_attr = 'class="alterCell" style="font-weight: bold;"';
//     $form->table_content_attr = 'class="alterCell2"';
//
//     // edit mode flag set
//     if ($rec_q->num_rows > 0) {
//         $form->edit_mode = true;
//         // record ID for delete process
//         $form->record_id = $itemID;
//         // form record title
//         $form->record_title = $rec_d['owner_name'];
//         // submit button attribute
//         $form->submit_button_attr = 'name="saveData" value="'.__('Update').'" class="button"';
//     }
//
//     /* Form Element(s) */
//     // place name
//     $form->addTextField('text', 'ownerName', __('Owner Name').'*', $rec_d['owner_name'], 'style="width: 60%;"');
//
//     // edit mode messagge
//     if ($form->edit_mode) {
//         echo '<div class="infoBox">'.__('You are going to edit owner data').' : <b>'.$rec_d['owner_name'].'</b>  <br />'.__('Last Update').$rec_d['last_update'].'</div>'; //mfc
//     }
//     // print out the form object
//     echo $form->printOut();
// } else {
//     /* PLACE LIST */
//     // table spec
//     $table_spec = 'mst_owner AS pl';
//
//     // create datagrid
//     $datagrid = new simbio_datagrid();
//     if ($can_read AND $can_write) {
//         $datagrid->setSQLColumn('pl.owner_id',
//             'pl.owner_name AS \''.__('Owner Name').'\'',
//             'pl.last_update AS \''.__('Last Update').'\'');
//     } else {
//         $datagrid->setSQLColumn('pl.owner_name AS \''.__('Owner Name').'\'',
//             'pl.last_update AS \''.__('Last Update').'\'');
//     }
//     $datagrid->setSQLorder('owner_name ASC');
//
//     // is there any search
//     if (isset($_GET['keywords']) AND $_GET['keywords']) {
//        $keywords = $dbs->escape_string($_GET['keywords']);
//        $datagrid->setSQLCriteria("pl.owner_name LIKE '%$keywords%'");
//     }
//
//     // set table and table header attributes
//     $datagrid->table_attr = 'align="center" id="dataList" cellpadding="5" cellspacing="0"';
//     $datagrid->table_header_attr = 'class="dataListHeader" style="font-weight: bold;"';
//     // set delete proccess URL
//     $datagrid->chbox_form_URL = $_SERVER['PHP_SELF'];
//
//     // put the result into variable
//     $datagrid_result = $datagrid->createDataGrid($dbs, $table_spec, 20, ($can_read AND $can_write));
//     if (isset($_GET['keywords']) AND $_GET['keywords']) {
//         $msg = str_replace('{result->num_rows}', $datagrid->num_rows, __('Found <strong>{result->num_rows}</strong> from your keywords')); //mfc
//         echo '<div class="infoBox">'.$msg.' : "'.$_GET['keywords'].'"</div>';
//     }
//
//     echo $datagrid_result;
// }
/* main content end */
?>
