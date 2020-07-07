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

$query = "SELECT biblio.title as ti,
          biblio.biblio_id as lid,
          SUM((SELECT COUNT(*) FROM loan WHERE loan.item_code = item.item_code AND loan_date BETWEEN '$awal' AND '$akhir')) as jlo,
          SUM((SELECT COUNT(*) FROM loan WHERE loan.item_code = item.item_code AND loan.is_return = '0' AND loan_date BETWEEN '$awal' AND '$akhir')) as al
          ,biblio.baca as baca
	        ,biblio.visit as visit
    
          FROM biblio
          LEFT JOIN item ON biblio.biblio_id = item.biblio_id
          WHERE item.item_code LIKE '$cari'
          OR biblio.title LIKE '$cari'
          OR biblio.isbn_issn LIKE '$cari'
          GROUP BY biblio.biblio_id
          ORDER BY $sort
          LIMIT $batas, $perpage
          ";
echo $query;
$data = $dbs->query($query);
foreach ($data as $value) {?>
  <tr>
    <td>
      <font style="cursor:pointer;color:#42adf4;" data-id="<?php echo $value['lid']?>" data-title='<?php echo str_replace("'","&#39;",$value['ti']) ?>' class="lb_detil">Detail</font>
    </td>
    <td><?php echo $value['ti']?></td>
    <td><?php echo $value['baca']?></td>
    <td><?php echo $value['visit']?></td>
    <td><?php echo $value['jlo']?></td>
    <td><?php echo $value['al']?></td>
  </tr>
<?php }
 ?>
