<?php
define('INDEX_AUTH', '1');
// key to get full database access
//define('DB_ACCESS', 'fa');

// main system configuration
require '../../sysconfig.inc.php';
// IP based access limitation
//require LIB.'ip_based_access.inc.php';
//do_checkIP('smc');
//do_checkIP('smc-bibliography');
// start the session
require SB.'admin/default/session.inc.php';

$id = $_POST['iddia'];
$sosmed = $_POST['sosmedia'];
$type = $_POST['typedia'];

$query = "INSERT INTO `sharing` (`id_share`, `content_id`, `tipe`, `sosmed`) VALUES (NULL, '$id', '$type', '$sosmed')";
$insert = $dbs->query($query);
 ?>
