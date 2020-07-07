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

$page = $_POST['page'];
$keyword = $_POST['keyword'];
$fieldnya = $_POST['fieldnya'];
$tgl_awal = $_POST['tgl_awal'];
$tgl_akhir = $_POST['tgl_akhir'];

$query = "SELECT korupsi.biblio_id as biblio_id,
          korupsi.title as title,
          korupsi.biblio_id as biblio_id,
          item.item_code as item_code,          
          FROM korupsi
          LEFT JOIN item ON korupsi.biblio_id = item.biblio_id
          LEFT JOIN pengunjung ON korupsi.biblio_id = pengunjung.biblio_id
          WHERE korupsi.title LIKE '%$keyword%'
          GROUP BY title";
echo $query;
die;
$isi = $dbs->query($query);
$jml = $isi->num_rows;
$laman = Floor($jml/10);
$lamana = $laman + 1;
$batas_akhir = $laman - 3;
$halaman = array();
if ($page > $batas_akhir) {
  $batas = $laman - 5;
  for ($i=$batas; $i < $lamana ; $i++) {
    $halaman[] = $i;
  }
} elseif ($page < 4 ) {
  for ($i=1; $i < 6; $i++) {
    $halaman[] = $i;
  }
}
else {
  $awal = $page - 2;
  $akhir = $page + 3;
  for ($i=$awal; $i < $akhir ; $i++) {
    $halaman[] = $i;
  }
}
$data['jml'] = $jml;
$data['halaman'] = $halaman;
json_encode($data);

// $query = "SELECT biblio.isbn_issn as isbn, biblio.title as title, COUNT(baca.id_baca) as baca"

// $query = "SELECT biblio.biblio_id as id, biblio.isbn_issn as isbn,biblio.title as title,item.item_code as item
//           FROM biblio
//           LEFT JOIN item ON biblio.biblio_id = item.biblio_id
//           WHERE isbn_issn = '$isbn'";
// $isi = $dbs->query($query);
// $jml = $isi->num_rows;
// if ($jml < 1) {
//   $query = "SELECT korupsi.biblio_id as id, korupsi.isbn_issn as isbn,kourupsi.title as title,item.item_code as item
//             FROM korupsi
//             LEFT JOIN item ON korupsi.biblio_id = item.biblio_id
//             WHERE isbn_issn = '$isbn'";
//   $isi = $dbs->query($query);
// }
// $data = array();
// foreach ($isi as $value) {
//   $data['id'] = $value['id'];
//   $data['isbn'] = $value['isbn'];
//   $data['title'] = $value['title'];
//   $data['item'] = $value['item'];
// }
// $data['jml'] = $jml;
// $datanya['biblio'] = $data;

echo json_encode($data);
 ?>
