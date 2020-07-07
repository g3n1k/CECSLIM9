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

$title = addslashes($_POST['title']);
$deskripsi = $_POST['deskrip'];
$file = $_FILES['pile'];

// print_r($file);
$tgl = date('Ymdhis');
$tgl_buat = date('Y-m-d');
$target_dir = IMGBS."sarasehan/";
// $target_file = $target_dir .$tgl. basename($_FILES["pile"]["name"]);
$nmimage = $_FILES["pile"]["name"];

$uploadOk = 1;
$imageFileType = pathinfo($nmimage,PATHINFO_EXTENSION);
$target_file = $target_dir .$tgl.'.'. $imageFileType;

$filenya = $tgl.'.'.$imageFileType;

//
//
move_uploaded_file($_FILES["pile"]["tmp_name"], $target_file);

$query = "INSERT INTO sarasehan VALUES(NULL,'$title','$filenya','$deskripsi','$tgl_buat','$tgl_buat',0)";
$insert = $dbs->query($query);

// Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
//     $check = getimagesize($_FILES["pile"]["tmp_name"]);
//     if($check !== false) {
//         echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//     } else {
//         echo "File is not an image.";
//         $uploadOk = 0;
//     }
// }
// Check if file already exists
// if (file_exists($target_file)) {
//     echo "Sorry, file already exists.";
//     $uploadOk = 0;
// }
// Check file size
// if ($_FILES["fileToUpload"]["size"] > 500000) {
//     echo "Sorry, your file is too large.";
//     $uploadOk = 0;
// }
// // Allow certain file formats
// if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
// && $imageFileType != "gif" ) {
//     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//     $uploadOk = 0;
// }
// // Check if $uploadOk is set to 0 by an error
// if ($uploadOk == 0) {
//     echo "Sorry, your file was not uploaded.";
// // if everything is ok, try to upload file
// } else {
//     if (move_uploaded_file($_FILES["pile"]["tmp_name"], $target_file)) {
//         echo "The file ". basename( $_FILES["pile"]["name"]). " has been uploaded.";
//     } else {
//         echo "Sorry, there was an error uploading your file.";
//     }
// }
// $id = $_POST['id'];
// $jumlah = count($id);
//
// $value = '';
// $tgl = date("Y-m-d");
// $no = 0;
// for ($i=0; $i < $jumlah; $i++) {
//   if ($no > 0) {
//     $value .= ',';
//   }
//   $value .= "(NULL,'".$id[$i]."','".$tgl."')";
//   $no++;
// }
//
//
// $query = "INSERT INTO `baca` (`id_baca`, `biblio_id`, `tgl_baca`) VALUES ".$value;
// $simpan = $dbs->query($query);

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
