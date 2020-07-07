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
$sort = $_POST['urut'];

$perpage = isset($_POST['perpage']) ? $_POST['perpage'] : 10;

$tgl_awal = strtotime($_POST['tgl_awal']);
$tgl_akhir = strtotime($_POST['tgl_akhir']);

if ($tgl_awal > $tgl_akhir) {
  $akhir = $_POST['tgl_awal'];
  $awal = $_POST['tgl_akhir'];
} else {
  $awal = $_POST['tgl_awal'];
  $akhir = $_POST['tgl_akhir'];
}

$halaman = $_POST['halaman'];
if ($halaman > 1) {
  $batas = ($halaman * $perpage) - 1;
}else{
  $batas = 0;
}

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../korupsi/export/excel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

/**autosize*/
// for ($col = 'A'; $col != 'P'; $col++) {
//     $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
// }

//No gridlines
// $objPHPExcel->getActiveSheet()->setShowGridlines(false);

$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);

// Set document properties
$objPHPExcel->getProperties()->setCreator("Perpustakaan KPK")
							 ->setLastModifiedBy("Perpustakaan KPK")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Data Export Excel Report Per Member.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("File Hasil Olahan");


// Add some data

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G2', 'Report Data Peruser')
            ->setCellValue('C4', 'Keyword')
            ->setCellValue('D4', ': '.$cari)
            ->setCellValue('C5', 'Periode')
            ->setCellValue('D5', ': '.$awal.' - '.$akhir)
            ;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A7', 'No.')
            ->setCellValue('B7', 'Member Name')
            ->setCellValue('C7', 'Departement')
            ->setCellValue('D7', 'Loan Times')
            ->setCellValue('E7', 'Active Loan')
            ;

$query = "SELECT member.member_name as name,
			member.inst_name as instansi,
			member.member_id as member_id,
			(SELECT count(loan.loan_id) FROM loan WHERE loan.member_id = member.member_id AND loan_date BETWEEN '$awal' AND '$akhir') as jml_loan,
			(SELECT count(loan.loan_id) FROM loan WHERE loan.member_id = member.member_id AND loan.is_return = '0' AND loan_date BETWEEN '$awal' AND '$akhir') as alo
			FROM `member`
			WHERE member_name LIKE '$cari'
			OR member_id LIKE '$cari'
			OR inst_name LIKE '$cari'
			ORDER BY $sort
			LIMIT $batas, $perpage
			";

$data = $dbs->query($query);
$no = 8;
$nomber = 1;
$isiannya = "";
foreach ($data as $value) {
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A'.$no, $nomber)
  ->setCellValue('B'.$no, $value["name"])
  ->setCellValue('C'.$no, $value["instansi"])
  ->setCellValue('D'.$no, $value["jml_loan"])
  ->setCellValue('E'.$no, $value["alo"])
  ;
  $no++;
  $nomber++;
}



// Miscellaneous glyphs, UTF-8
// $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A4', 'Miscellaneous glyphs')
//             ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Data');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Report-Per-Member.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
