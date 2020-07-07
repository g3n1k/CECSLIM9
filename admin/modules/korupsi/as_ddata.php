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


$query = "SELECT * FROM sarasehan WHERE ss_pic != '' AND ss_pic NOT IN (SELECT img FROM sarasehan_display) ORDER BY id DESC LIMIT 25 OFFSET 0";
$isi = $dbs->query($query);
foreach ($isi as $value) { ?>
  <tr class="gbrnya" data-value="<?php echo $value['id']?>" data-gambar="<?php echo strtok($value['ss_pic'], '.'); ?>" data-gbr="<?php echo $value['ss_pic']?>" style="cursor:pointer;">
    <td><?php echo $value['ss_title'] ?></td>
    <td><img src="../images/sarasehan/<?php echo $value['ss_pic'] ?>" alt="" style="max-width:200px;max-height:200px;"></td>
  </tr>
<?php
}
 ?>
