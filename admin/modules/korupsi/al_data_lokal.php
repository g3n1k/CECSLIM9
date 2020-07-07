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

$tgl_awal = $_POST['tgl_awal'];
$tgl_akhir = $_POST['tgl_akhir'];

if ($tgl_awal > $tgl_akhir) {
  $akhir = date('Y-m-d', strtotime($tgl_awal. '+1 day'));
  $awal = date('Y-m-d', strtotime($tgl_akhir. '-1 day'));
} else {
  $awal = date('Y-m-d', strtotime($tgl_awal. '+1 day'));
  $akhir = date('Y-m-d', strtotime($tgl_akhir. '-1 day'));
}

$order = $valnya.' '.$sortnya;

$limit = ($page * 10) - 10;

$query = "SELECT korupsi.isbn_issn as isbn_issn,
          korupsi.title as title,
          korupsi.biblio_id as biblio_id,
          (SELECT COUNT(id_pengunjung) FROM pengunjung WHERE pengunjung.biblio_id = korupsi.biblio_id AND tgl_kunjungan BETWEEN '$awal' AND '$akhir' ) as visitor,
          (SELECT COUNT(comment_id) FROM comments WHERE comments.biblio_id = korupsi.biblio_id AND input_date BETWEEN '$awal' AND '$akhir') as komentar,
          (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = korupsi.biblio_id AND tgl BETWEEN '$awal' AND '$akhir') as sharing,
          (SELECT COUNT(id) FROM notif_comments WHERE notif_comments.biblio_id = korupsi.biblio_id AND tgl_comment BETWEEN '$awal' AND '$akhir') as ncm
          FROM korupsi
          WHERE korupsi.title LIKE '%$keyword%'
          GROUP BY title
          ORDER BY $order
          LIMIT 10 OFFSET $limit";
$data = $dbs->query($query);
foreach ($data as $value) {?>
  <tr>
    <td>
      <button data-id="<?php echo $value['biblio_id']; ?>" style="cursor:pointer;" class="btn btn-xs <?php if(!empty($value['ncm'])){echo "btn-primary";}else{echo "btn-success";}?> ditel">Detail</button>
    </td>
    <td><?php echo $value['item_code'] ?></td>
    <td><?php echo $value['title'] ?></td>
    <td><?php echo $value['visitor'] ?></td>
    <td><?php echo $value['sharing'] ?></td>
    <td><?php echo $value['komentar'] ?></td>
    <td></td>
  </tr>
<?php }
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
