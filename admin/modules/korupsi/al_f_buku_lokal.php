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

$isbn = $_POST['isbn'];
$query = "SELECT item.item_code as i_code,
          korupsi.biblio_id as id,
          korupsi.isbn_issn as isbn,
          korupsi.title as title
          FROM item
          LEFT JOIN korupsi ON item.biblio_id = korupsi.biblio_id
          WHERE korupsi.title = '$isbn'";
$isi = $dbs->query($query);
$jml = $isi->num_rows;
// if ($jml < 1) {
//   $query = "SELECT korupsi.biblio_id as id, korupsi.isbn_issn as isbn,kourupsi.title as title,item.item_code as item
//             FROM korupsi
//             LEFT JOIN item ON korupsi.biblio_id = item.biblio_id
//             WHERE isbn_issn = '$isbn'";
//   $isi = $dbs->query($query);
// }
$data = array();
foreach ($isi as $value) {
  $data['id'] = $value['id'];
  $data['isbn'] = $value['isbn'];
  $data['title'] = $value['title'];
  $data['item'] = $value['i_code'];
}
$data['jml'] = $jml;
// $datanya['biblio'] = $data;

echo json_encode($data);
 ?>
