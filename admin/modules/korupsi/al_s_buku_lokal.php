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

$id = $_POST['id'];
$jumlah = count($id);

$value = '';
$tgl = date("Y-m-d");
$no = 0;
for ($i=0; $i < $jumlah; $i++) {
  if ($no > 0) {
    $value .= ',';
  }
  $value .= "(NULL,'".$id[$i]."','".$tgl."')";
  $no++;
}

// echo "$value";

$query = "INSERT INTO `baca` (`id_baca`, `biblio_id`, `tgl_baca`) VALUES ".$value;
echo "$query";
$simpan = $dbs->query($query);

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
// // $datanya['biblio'] = $data;
//
// echo json_encode($data);
 ?>
