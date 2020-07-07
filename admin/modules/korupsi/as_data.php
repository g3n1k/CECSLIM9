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

$keyword = $_POST['keyword'];
$page = $_POST['page'];
$sortnya = $_POST['sortnya'];
$valnya = $_POST['valnya'];
$order = $valnya.' '.$sortnya;

$limit = ($page * 10) - 10;

$query = "SELECT * FROM sarasehan
          WHERE ss_title LIKE '%$keyword%'
          ORDER BY $order
          LIMIT 10 OFFSET $limit";

$data = $dbs->query($query);?>
<?php foreach ($data as $value) {?>
  <tr>
    <td>
      <span class="def-<?php echo $value['id']?>">
        <font style="cursor:pointer;color:red;" data-id="<?php echo $value['id']?>" class="dele">Delete</font>
        &nbsp;&nbsp;
        <font style="cursor:pointer;color:#42adf4;" data-id="<?php echo $value['id']?>" class="ed">Edit</font>
      </span>
      <span class="def-<?php echo $value['id']?>" style="display:none;">
        <font style="cursor:pointer;color:green;" data-id="<?php echo $value['id']?>" class="dele">Cancel</font>
        &nbsp;&nbsp;
        <font style="cursor:pointer;color:red;" data-id="<?php echo $value['id']?>" class="del">Delete</font>
      </span>
    </td>
    <td><?php echo $value['ss_title'] ?></td>
    <td><?php echo substr(strip_tags($value['ss_desk']),0,50); ?></td>
    <td><?php echo $value['tgl_update'] ?></td>
  </tr>
<?php }?>

<?php
// $query = "SELECT biblio.isbn_issn as isbn,
//                   biblio.title as title,
//                   item.item_code as item_code,
//                   COUNT(baca.id_baca) as jmlbaca,
//                   COUNT(pengunjung.id_pengunjung) as jmlpengunjung,
//                   COUNT(loan.id) as jmlloan
//                   FROM biblio
//                   LEFT JOIN baca ON biblio.biblio_id = baca.biblio_id,
//                   LEFT JOIN pengunjung ON biblio.biblio_id = pengunjung.biblio_id,
//                   LEFT JOIN item ON biblio.biblio_id = item.biblio_id,
//                   LEFT JOIN loan ON item.item_code = loan.item_code
//                   "




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
