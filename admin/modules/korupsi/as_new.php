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

// privileges checking
$can_read = utility::havePrivilege('korupsi', 'r');
$can_write = utility::havePrivilege('korupsi', 'w');

if (!$can_read) {
    die('<div class="errorBox">'.__('You don\'t have enough privileges to access this area!').'</div>');
}
?>
<fieldset class="menuBox">
<div class="menuBoxInner masterFileIcon">
	<div class="per_title">
    	<h2><?php echo __('Input Sarasehan Activity'); ?></h2>
	</div>
</div>
</fieldset>
<div class="contentDesc baca">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-info">
          <div class="panel-body" style="min-height:470px;">
            <form id="1" action="javascript:void(0)" method="post">
              <table>
                <tr>
                  <td><?php echo __('Title'); ?></td>
                  <td><input type="text" class="judul" style="width:100%" name="title" value=""></td>
                </tr>
                <tr>
                  <td><?php echo __('Picture'); ?></td>
                  <td><input type="file" class="gbr" name="pile" value=""></td>
                </tr>
                <tr>
                  <td><?php echo __('Description'); ?></td>
                  <td><textarea name="DSC" id="DSC" rows="8" cols="80" class="deskripsi ini"></textarea>
                    <textarea name="deskrip" id="deskrip" rows="8" cols="80" class="deskrip ini" style="display:none"></textarea>
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td><input type="submit" name="" value="Save" class="btn btn-primary button simpan"></td>
                </tr>
              </table>
            </form>
            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<a class="cobaan" href="<?php echo strstr($_SERVER["REQUEST_URI"], 'modules', true).'modules/korupsi/as_index.php?ajaxload=1&'?>" title="Edit">
</a>
<script type="text/javascript">
var desc = 'empty';
$(document).ready(
  function() {
    /*
    $(\'#contentDesc\').removeAttr(\'disable\');
    tinymce.init({
    selector : "textarea#contentDesc",
    theme : "modern",
    plugins : "table media searchreplace directionality code",
    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
    content_css : "'.(SWB.'admin/'.$sysconf['admin_template']['css']).'",
    height : 300
    });
    */
    CKEDITOR.replace( 'DSC' );
  }
);
// $('.simpan').click(function(e) {
//   desc = CKEDITOR.instances['DSC'].getData();
//   alert(desc);
// });
    $( '#1' ).submit( function( e ) {
      var file = $('.gbr').val();
      var title = $('.judul').val();
      var deskripsi = CKEDITOR.instances['DSC'].getData();
      $('.deskrip').val(deskripsi);

      if (!title && !deskripsi) {
        alert('Please title and description cannot empty');
      }
      else {
        if (!file) {
          $.ajax({
            url: '<?php echo MWB; ?>korupsi/as_save.php',
            type: 'POST',
            data: new FormData( this ),
            processData: false,
            contentType: false,
            success : function(data) {
              alert("Data successfully saved");
              $('.gbr').val("");
              $('.judul').val("");
              CKEDITOR.instances['DSC'].setData('');
              $('.cobaan').click();
            }
          });
        }else {
          var extension = file.substr( (file.lastIndexOf('.') +1) );
          if (extension == 'jpg' || extension == 'jpeg' || extension == 'png') {
            $.ajax({
              url: '<?php echo MWB; ?>korupsi/as_save.php',
              type: 'POST',
              data: new FormData( this ),
              processData: false,
              contentType: false,
              success : function(data) {
                alert("Data successfully saved");
                $('.gbr').val("");
                $('.judul').val("");
                CKEDITOR.instances['DSC'].setData('');
                $('.cobaan').click();
              }
            });
          } else {
            alert('File is not picture ');
          }
        }
      }
      e.preventDefault();
    });





</script>
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
// /* main content end */
?>
