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
$urut = $_POST['urut'];
$halaman = $_POST['halaman'];

$query = "SELECT member_id
          FROM `member`
          WHERE member_name LIKE '$cari'
          OR member_id LIKE '$cari'
          OR inst_name LIKE '$cari'";
$isi = $dbs->query($query);
$jml = $isi->num_rows;

$page = $_POST['halaman'];
$jumlah_page = (ceil($jml / 10))-1; // Hitung jumlah halamannya
$jumlah_number = 2; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
$start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
$end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number

for($i = $start_number; $i <= $end_number; $i++){
  $warna = '';
  if ($page == $i) {
    $warna = 'color:#42adf4;';
  }
  ?>

  <font data-page="<?php echo $i; ?>" style="cursor:pointer;<?php echo $warna?>" class="pagingnya"><?php echo $i; ?></font>&nbsp;

<?php
}
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

 ?>
