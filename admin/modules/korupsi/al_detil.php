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

$id = $_POST['idnya'];

$query = "SELECT biblio.isbn_issn as isbn_issn,
          biblio.title as title,
          biblio.biblio_id as biblio_id,
          item.item_code as item_code,
          (SELECT COUNT(id_pengunjung) FROM pengunjung WHERE pengunjung.biblio_id = biblio.biblio_id) as visitor,
          (SELECT COUNT(id_baca) FROM baca WHERE baca.biblio_id = biblio.biblio_id) as pembaca,
          (SELECT COUNT(loan_id) FROM loan WHERE loan.item_code = item.item_code) as pinjam,
          (SELECT COUNT(comment_id) FROM comments WHERE comments.biblio_id = biblio.biblio_id) as komentar,
          (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id) as sharing,
          (SELECT COUNT(id) FROM notif_comments WHERE notif_comments.biblio_id = biblio.biblio_id) as ncm,
          (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'facebook') as fb,
          (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'twitter') as tw,
          (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'google') as goo,
          (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'linkedin') as lin,
          (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'tumblr') as tum,
          (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'whatsapp') as wha
          FROM biblio
          LEFT JOIN item ON biblio.biblio_id = item.biblio_id
          WHERE biblio.biblio_id LIKE '%$id%'
          ";

$data = $dbs->query($query);
foreach ($data as $value) {?>
  <dl>
    <dt>Title :</dt>
    <dd><?php echo $value['title']?></dd>
    <dt>Visit :</dt>
    <dd><?php echo $value['visitor']?></dd>
    <dt>Read :</dt>
    <dd><?php echo $value['pembaca']?></dd>
    <dt>Loan :</dt>
    <dd><?php echo $value['pinjam']?></dd>
    <dt>Share :</dt>
    <dd><?php echo $value['sharing']?> <sup class="det_sha" style="font-size:10px;color:red;cursor:pointer;">detail</sup></dd>
  </dl>
  <section class="sha_det" style="display:none;">
    <hr>
    <div class="col-md-2">
      <dl >
        <dt style="width:60%;">Facebook :</dt>
        <dd style="width:40%;"><?php echo $value['fb']?></dd>
      </dl>
    </div>
    <div class="col-md-2">
      <dl >
        <dt style="width:60%;">Twitter :</dt>
        <dd style="width:40%;"><?php echo $value['tw']?></dd>
      </dl>
    </div>
    <div class="col-md-2">
      <dl >
        <dt style="width:60%;">Googleplus </dt>
        <dd style="width:40%;">: <?php echo $value['goo']?></dd>
      </dl>
    </div>
    <div class="col-md-2">
      <dl >
        <dt style="width:60%;">Linkedin :</dt>
        <dd style="width:40%;"><?php echo $value['lin']?></dd>
      </dl>
    </div>
    <div class="col-md-2">
      <dl >
        <dt style="width:60%;">Tumblr :</dt>
        <dd style="width:40%;"><?php echo $value['tum']?></dd>
      </dl>
    </div>
    <div class="col-md-2">
      <dl >
        <dt style="width:60%;">Whatsapp :</dt>
        <dd style="width:40%;"><?php echo $value['wha']?></dd>
      </dl>
    </div>
    <div class="col-md-12">
      <hr>
    </div>
  </section>
  <dl>
    <dt>Comments :</dt>
    <dd><?php echo $value['komentar']?> | <font style="color:red;"><?php echo $value['ncm']?></font> New Comment(s)</dd>
  </dl>
  <div class="col-md-12">
    <table id="dataList" cellpadding="5" cellspacing="0">
      <tbody>
        <tr class="dataListHeader" style="font-weight: bold; cursor: pointer; background-color: rgb(49, 53, 62);" row="0">
          <td>DELETE</td>
          <td>NAME</td>
          <td>EMAIL</td>
          <td>COMMENTS</td>
          <td>DATE</td>
        </tr>
        <?php
        $query_com = "SELECT * FROM  comments WHERE biblio_id = '$id' ORDER BY comment_id DESC";
        $data_com = $dbs->query($query_com);
        $no = 0;
        foreach ($data_com as $val) { ?>
          <tr class="<?php if($no > 0){echo 'alterCell2';}else{echo 'alterCell';}?>">
            <td><font style="color:red;cursor:pointer;" class="del_com" data-id="<?php echo $val['comment_id']?>";>DELETE</font></td>
            <td><?php echo $val['name']?></td>
            <td><?php echo $val['email']?></td>
            <td><?php echo $val['comment']?></td>
            <td><?php echo $val['input_date']?></td>
          </tr>
        <?php
            if ($no < 0) {
              $no = 0;
            }
            $no++;
          }
        ?>
      </tbody>
    </table>
  </div>

<?php }
$querydel_notif = "DELETE FROM notif_comments WHERE biblio_id = '$id'";
$hapus_notif = $dbs->query($querydel_notif);
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
