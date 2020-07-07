<?php
define('INDEX_AUTH', '1');
// key to get full database access
//define('DB_ACCESS', 'fa');

// main system configuration
require '../../../sysconfig.inc.php';
// IP based access limitation
//require LIB.'ip_based_access.inc.php';
//do_checkIP('smc');
//do_checkIP('smc-bibliography');
// start the session
require SB.'admin/default/session.inc.php';


$mem_id = $_POST['member_id'];

$tgl_awal = strtotime($_POST['tgl_awal']);
$tgl_akhir = strtotime($_POST['tgl_akhir']);

if ($tgl_awal > $tgl_akhir) {
  $akhir = $_POST['tgl_awal'];
  $awal = $_POST['tgl_akhir'];
} else {
  $awal = $_POST['tgl_awal'];
  $akhir = $_POST['tgl_akhir'];
}

$query = "SELECT member_id, member_name, member_email, inst_name,
                  member_phone, register_date, expire_date
                  FROM member WHERE member_id = '$mem_id'
                  ";
$data = $dbs->query($query);

$q_loan = "SELECT loan.item_code as ic, biblio.title as ti, loan.loan_date as ld, loan.due_date as due, loan.is_return as ir
            FROM loan
            LEFT JOIN item ON loan.item_code = item.item_code
            LEFT JOIN biblio ON item.biblio_id = biblio.biblio_id
            WHERE loan.member_id = '$mem_id'
            AND loan_date BETWEEN '$awal' AND '$akhir'
            ORDER BY loan.loan_id DESC
            ";
$d_loan = $dbs->query($q_loan);
foreach ($data as $value) { ?>
  <h2><?php echo __('Detail Member'); ?></h2>
  <hr>
  <table>
    <tr>
      <td><?php echo __('Member ID'); ?></td>
      <td class="mid">: &nbsp;<?php echo $value['member_id'] ?></td>
    </tr>
    <tr>
      <td><?php echo __('Member Name'); ?></td>
      <td class="mna">: &nbsp;<?php echo $value['member_name'] ?></td>
    </tr>
    <tr>
      <td><?php echo __('Address'); ?> </td>
      <td class="ema">: &nbsp;<?php echo $value['member_email'] ?></td>
    </tr>
    <tr>
      <td><?php echo __('Department'); ?></td>
      <td class="inn">: &nbsp;<?php echo $value['inst_name'] ?></td>
    </tr>
    <tr>
      <td><?php echo __('Member Phone'); ?></td>
      <td class="mph">: &nbsp;<?php echo $value['member_phone'] ?></td>
    </tr>
    <tr>
      <td><?php echo __('Registration Date'); ?></td>
      <td class="rda">: &nbsp;<?php echo date('d M Y',strtotime($value['register_date'])) ?></td>
    </tr>
    <tr>
      <td><?php echo __('Expire Date'); ?></td>
      <td class="eda">: &nbsp;<?php echo date('d M Y',strtotime($value['expire_date'])) ?></td>
    </tr>
  </table>
<?php }
 ?>
 <hr>
 <h2><?php echo __('Loan Detail'); ?></h2>
 <hr>
 <table align="center"  cellpadding="5" cellspacing="0" style="width:100%;">
   <tr class="dataListHeader" style="font-weight: bold; cursor: pointer; background-color: rgb(49, 53, 62);">
     <td style="width:15%;"><?php echo __('Item Code'); ?></td>
     <td style="width:40%;"><?php echo __('Title'); ?></td>
     <td style="width:15%;"><?php echo __('Loan Date'); ?></td>
     <td style="width:15%;"><?php echo __('Due Date'); ?></td>
     <td style="width:15%;"><?php echo __('Is Returned ?'); ?></td>
   </tr>
   <?php
    foreach ($d_loan as $var) { ?>
      <tr>
        <td style="width:15%;"><small><?php echo $var['ic'] ?></small></td>
        <td style="width:40%;"><small><?php echo $var['ti'] ?></small></td>
        <td style="width:15%;"><small><?php echo $var['ld'] ?></small></td>
        <td style="width:15%;"><small><?php echo $var['due'] ?></small></td>
        <td style="width:15%;">
          <small>
            <?php if ($var['ir'] == '0') {
              echo "No";
            } else {
              echo "Yes";
            }?>
          </small>
        </td>
      </tr>
    <?php }
   ?>
 </table>
