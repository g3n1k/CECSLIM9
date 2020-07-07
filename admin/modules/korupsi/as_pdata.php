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


$query = "SELECT * FROM sarasehan WHERE id = '$id'";
$isi = $dbs->query($query);
foreach ($isi as $value) {
  $data['id'] = $value['id'];
  $data['title'] = $value['ss_title'];
  $data['pic'] = $value['ss_pic'];
  $data['desk'] = $value['ss_desk'];
}
echo json_encode($data);
 ?>
