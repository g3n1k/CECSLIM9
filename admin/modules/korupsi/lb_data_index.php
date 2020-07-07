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

$query = "SELECT korupsi.title as ti,
          korupsi.biblio_id as lid,
          korupsi.visit as visit
          FROM korupsi
          WHERE korupsi.title LIKE '$cari'
          OR korupsi.isbn_issn LIKE '$cari'
          GROUP BY korupsi.biblio_id
          ORDER BY $sort
          LIMIT $batas, $perpage
          ";
echo $query;
$data = $dbs->query($query);
foreach ($data as $value) {?>
  <tr>
  <!--
    <td>
      <font style="cursor:pointer;color:#42adf4;" data-id="<?php echo $value['lid']?>" data-title='<?php echo str_replace("'","&#39;",$value['ti']) ?>' class="lb_detil">Detail</font>
    </td>
  -->
    <td><?php echo $value['ti']?></td>
  <!--  <td><?php echo $value['baca']?></td>-->
    <td><?php echo $value['visit']?></td>
  <!--  <td><?php echo $value['jlo']?></td>
    <td><?php echo $value['al']?></td> -->
  </tr>
<?php }
 ?>
