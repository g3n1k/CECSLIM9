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


$cari = $_POST['cari'];
$sort = $_POST['urut'];

$perpage = isset($_POST['perpage']) ? $_POST['perpage'] : 10;

$tgl_awal = strtotime($_POST['tgl_awal']);
$tgl_akhir = strtotime($_POST['tgl_akhir']);

if ($tgl_awal > $tgl_akhir) {
  $akhir = $_POST['tgl_awal'];
  $awal = $_POST['tgl_akhir'];
} else {
  $awal = $_POST['tgl_awal'];
  $akhir = $_POST['tgl_akhir'];
}

$halaman = $_POST['halaman'];
if ($halaman > 1) {
  $batas = ($halaman * $perpage) - 1;
}else{
  $batas = 0;
}


$query = "SELECT member.member_name as name,
		            member.inst_name as instansi,
                member.member_id as member_id,
		            (SELECT count(loan.loan_id) FROM loan WHERE loan.member_id = member.member_id AND loan_date BETWEEN '$awal' AND '$akhir') as jml_loan,
                (SELECT count(loan.loan_id) FROM loan WHERE loan.member_id = member.member_id AND loan.is_return = '0' AND loan_date BETWEEN '$awal' AND '$akhir') as alo
                FROM `member`
                WHERE member_name LIKE '$cari'
                OR member_id LIKE '$cari'
                OR inst_name LIKE '$cari'
                ORDER BY $sort
                LIMIT $batas, $perpage
                ";
$data = $dbs->query($query);
foreach ($data as $value) {?>
  <tr>
    <td>
      <font style="cursor:pointer;color:#42adf4;" data-id="<?php echo $value['member_id']?>" class="lm_detil">Detail</font>
    </td>
    <td><?php echo $value['name']?></td>
    <td>
      <?php
      if (strlen($value['instansi']) > 1) {
          echo  $value['instansi'];
      }else {
        echo "-";
      }
      ?>
    </td>
    <td><?php echo $value['jml_loan']?></td>
    <td><?php echo $value['alo']?></td>
  </tr>
<?php }
 ?>
