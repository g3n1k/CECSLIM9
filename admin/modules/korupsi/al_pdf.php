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


/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

 $keyword = $_POST['kata'];

 $tgl_awal = $_POST['tgl_awal'];
 $tgl_akhir = $_POST['tgl_akhir'];

 if ($tgl_awal > $tgl_akhir) {
   $akhir = date('Y-m-d', strtotime($tgl_awal. '+1 day'));
   $awal = date('Y-m-d', strtotime($tgl_akhir. '-1 day'));
 } else {
   $awal = date('Y-m-d', strtotime($tgl_awal. '+1 day'));
   $akhir = date('Y-m-d', strtotime($tgl_akhir. '-1 day'));
 }


 $query = "SELECT biblio.isbn_issn as isbn_issn,
           biblio.title as title,
           biblio.biblio_id as biblio_id,
           item.item_code as item_code,
           (SELECT COUNT(id_pengunjung) FROM pengunjung WHERE pengunjung.biblio_id = biblio.biblio_id AND tgl_kunjungan BETWEEN '$awal' AND '$akhir' ) as visitor,
           (SELECT COUNT(id_baca) FROM baca WHERE baca.biblio_id = biblio.biblio_id AND tgl_baca BETWEEN '$awal' AND '$akhir') as pembaca,
           (SELECT COUNT(loan_id) FROM loan WHERE loan.item_code = item.item_code AND loan_date BETWEEN '$awal' AND '$akhir') as pinjam,
           (SELECT COUNT(comment_id) FROM comments WHERE comments.biblio_id = biblio.biblio_id AND input_date BETWEEN '$awal' AND '$akhir') as komentar,
           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND tgl BETWEEN '$awal' AND '$akhir') as sharing,
           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'facebook' AND tgl BETWEEN '$awal' AND '$akhir') as fb,
           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'twitter' AND tgl BETWEEN '$awal' AND '$akhir') as tw,
           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'google' AND tgl BETWEEN '$awal' AND '$akhir') as goo,
           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'linkedin' AND tgl BETWEEN '$awal' AND '$akhir') as lin,
           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'tumblr' AND tgl BETWEEN '$awal' AND '$akhir') as tum,
           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'whatsapp' AND tgl BETWEEN '$awal' AND '$akhir') as wha
           FROM biblio
           LEFT JOIN item ON biblio.biblio_id = item.biblio_id
           WHERE biblio.title LIKE '%$keyword%'
           GROUP BY title
           ";

$data = $dbs->query($query);
$no = 1;
?>
  <table style="width:100%;">
    <tr>
      <td>No</td>
      <td>Title</td>
      <td>Visit</td>
      <td>Read</td>
      <td>Loan</td>
      <td>Comment</td>
      <td>Sharing</td>
      <td>Facebook</td>
      <td>Twitter</td>
      <td>GooglePlus</td>
      <td>Linkedin</td>
      <td>Tumblr</td>
      <td>Whatsapp</td>
    </tr>
    <?php
      foreach ($data as $value) {
        ?>
        <tr>
          <td><?php echo $no ?></td>
          <td style="width:40%;"><?php echo $value['title'] ?></td>
          <td><?php echo $value['visitor'] ?></td>
          <td><?php echo $value['pembaca'] ?></td>
          <td><?php echo $value['pinjam'] ?></td>
          <td><?php echo $value['komentar'] ?></td>
          <td><?php echo $value['sharing'] ?></td>
          <td><?php echo $value['fb'] ?></td>
          <td><?php echo $value['tw'] ?></td>
          <td><?php echo $value['goo'] ?></td>
          <td><?php echo $value['lin'] ?></td>
          <td><?php echo $value['tum'] ?></td>
          <td><?php echo $value['wha'] ?></td>
        </tr>
        <?php $no++;
      }
     ?>
  </table>
<?php

echo "<script>window.print();</script>";

// /** Error reporting */
// error_reporting(E_ALL);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);
// date_default_timezone_set('Europe/London');
//
// if (PHP_SAPI == 'cli')
// 	die('This example should only be run from a Web Browser');
//
// /** Include PHPExcel */
// require_once dirname(__FILE__) . '/export/excel/Classes/PHPExcel.php';
//
// $rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
// //$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
// //$rendererLibrary = 'tcPDF5.9';
// // $rendererLibrary = 'mPDF5.4';
// //$rendererLibrary = 'domPDF0.6.0beta3';
// $rendererLibraryPath = dirname(__FILE__).'\export\vendor\dompdf\dompdf\lib\Cpdf.php';
// // Create new PHPExcel object
//
// $objPHPExcel = new PHPExcel();
//
// /**autosize*/
// // for ($col = 'A'; $col != 'P'; $col++) {
// //     $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
// // }
//
// //No gridlines
// // $objPHPExcel->getActiveSheet()->setShowGridlines(false);
//
// $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
//
// // Set document properties
// $objPHPExcel->getProperties()->setCreator("Perpustakaan KPK")
// 							 ->setLastModifiedBy("Perpustakaan KPK")
// 							 ->setTitle("Office 2007 XLSX Test Document")
// 							 ->setSubject("Office 2007 XLSX Test Document")
// 							 ->setDescription("Data Export Excel Index Aktivitas Buku.")
// 							 ->setKeywords("office 2007 openxml php")
// 							 ->setCategory("File Hasil Olahan");
//
//
// // Add some data
// $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('G2', 'Biblio Activity Data')
//             ->setCellValue('C4', 'Keyword')
//             ->setCellValue('D4', ': '.$keyword)
//             ->setCellValue('C5', 'Periode')
//             ->setCellValue('D5', ': '.$awal.' - '.$akhir)
//             ;
//
// $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A7', 'No.')
//             ->setCellValue('B7', 'Title')
//             ->setCellValue('C7', 'Visit')
//             ->setCellValue('D7', 'Read')
//             ->setCellValue('E7', 'Loan')
//             ->setCellValue('F7', 'Comments')
//             ->setCellValue('G7', 'Sharing')
//             ->setCellValue('H7', 'Facebook')
//             ->setCellValue('I7', 'Twitter')
//             ->setCellValue('J7', 'GooglePlus')
//             ->setCellValue('K7', 'Linkedin')
//             ->setCellValue('L7', 'Tumblr')
//             ->setCellValue('M7', 'Whatsapp')
//             ;
//
// $query = "SELECT biblio.isbn_issn as isbn_issn,
//           biblio.title as title,
//           biblio.biblio_id as biblio_id,
//           item.item_code as item_code,
//           (SELECT COUNT(id_pengunjung) FROM pengunjung WHERE pengunjung.biblio_id = biblio.biblio_id AND tgl_kunjungan BETWEEN '$awal' AND '$akhir' ) as visitor,
//           (SELECT COUNT(id_baca) FROM baca WHERE baca.biblio_id = biblio.biblio_id AND tgl_baca BETWEEN '$awal' AND '$akhir') as pembaca,
//           (SELECT COUNT(loan_id) FROM loan WHERE loan.item_code = item.item_code AND loan_date BETWEEN '$awal' AND '$akhir') as pinjam,
//           (SELECT COUNT(comment_id) FROM comments WHERE comments.biblio_id = biblio.biblio_id AND input_date BETWEEN '$awal' AND '$akhir') as komentar,
//           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND tgl BETWEEN '$awal' AND '$akhir') as sharing,
//           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'facebook' AND tgl BETWEEN '$awal' AND '$akhir') as fb,
//           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'twitter' AND tgl BETWEEN '$awal' AND '$akhir') as tw,
//           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'google' AND tgl BETWEEN '$awal' AND '$akhir') as goo,
//           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'linkedin' AND tgl BETWEEN '$awal' AND '$akhir') as lin,
//           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'tumblr' AND tgl BETWEEN '$awal' AND '$akhir') as tum,
//           (SELECT COUNT(id_share) FROM sharing WHERE sharing.content_id = biblio.biblio_id AND sharing.sosmed = 'whatsapp' AND tgl BETWEEN '$awal' AND '$akhir') as wha
//           FROM biblio
//           LEFT JOIN item ON biblio.biblio_id = item.biblio_id
//           WHERE biblio.title LIKE '%$keyword%'
//           GROUP BY title
//           ";
//
// $data = $dbs->query($query);
// $no = 8;
// $nomber = 1;
// $isiannya = "";
// foreach ($data as $value) {
//   $objPHPExcel->setActiveSheetIndex(0)
//   ->setCellValue('A'.$no, $nomber)
//   ->setCellValue('B'.$no, $value["title"])
//   ->setCellValue('C'.$no, $value["visitor"])
//   ->setCellValue('D'.$no, $value["pembaca"])
//   ->setCellValue('E'.$no, $value["pinjam"])
//   ->setCellValue('F'.$no, $value["komentar"])
//   ->setCellValue('G'.$no, $value["sharing"])
//   ->setCellValue('H'.$no, $value["fb"])
//   ->setCellValue('I'.$no, $value["tw"])
//   ->setCellValue('J'.$no, $value["goo"])
//   ->setCellValue('K'.$no, $value["lin"])
//   ->setCellValue('L'.$no, $value["tum"])
//   ->setCellValue('M'.$no, $value["wha"]);
//
//   $no++;
//   $nomber++;
// }
//
//
//
// // Miscellaneous glyphs, UTF-8
// // $objPHPExcel->setActiveSheetIndex(0)
// //             ->setCellValue('A4', 'Miscellaneous glyphs')
// //             ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
//
// // Rename worksheet
// $objPHPExcel->getActiveSheet()->setTitle('Data');
// $objPHPExcel->getActiveSheet()->setShowGridLines(false);
//
//
// // Set active sheet index to the first sheet, so Excel opens this as the first sheet
// $objPHPExcel->setActiveSheetIndex(0);
//
//
// // Redirect output to a client’s web browser (Excel2007)
// header('Content-Type: application/pdf');
// header('Content-Disposition: attachment;filename="01simple.pdf"');
// header('Cache-Control: max-age=0');
//
// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
// $objWriter->save('php://output');
// exit;
