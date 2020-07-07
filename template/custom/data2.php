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

// $query = "SELECT biblio.biblio_id as bid,
//           biblio.title as title,
//           biblio.call_number as cm,
//           mst_author.author_id as aid,
//           mst_author.author_name as ana,
//           mst_publisher.publisher_id as pid,
//           mst_publisher.publisher_name as pna,
//           mst_topic.topic_id as tid,
//           mst_topic.topic as topic
//           FROM biblio
//           LEFT JOIN biblio_author ON biblio.biblio_id = biblio_author.biblio_id
//           LEFT JOIN mst_author ON biblio_author.author_id = mst_author.author_id
//           LEFT JOIN biblio_topic ON biblio.biblio_id = biblio_topic.biblio_id
//           LEFT JOIN mst_topic ON biblio_topic.topic_id = mst_topic.topic_id
//           LEFT JOIN mst_publisher ON biblio.publisher_id = mst_publisher.publisher_id";

$word = addslashes($_GET['query']);
$query = "SELECT korupsi.title as title
          FROM korupsi
          WHERE korupsi.title LIKE '%$word%'";

// itu yang ribet addslashes ama strreplace dibawah buat ngilangin enter, tanda kutip ama dobel kutip

$a = $dbs->query($query);
$no = 0;
$isi = '[';
foreach ($a as $value) {
  if ($no > 0) {
    $isi .= ', ';
  }
  $isi .= '"'.str_replace("\'","'",addslashes(str_replace(array('\"', "\'"), array('&quot;', "'"),$value['title']))).'"';
  $no++;
}

$query = "SELECT korupsi.call_number as cm
          FROM korupsi
          WHERE korupsi.call_number LIKE '%$word%'";
$a = $dbs->query($query);
foreach ($a as $value) {
  if ($no > 0) {
    $isi .= ', ';
  }
  $isi .= '"'.str_replace("\'","'",addslashes(str_replace(array('\"', "\'"), array('&quot;', "'"),$value['cm']))).'"';
  $no++;
}

$query = "SELECT mst_publisher.publisher_name as pna
          FROM korupsi
          JOIN mst_publisher ON korupsi.publisher_id = mst_publisher.publisher_id
          WHERE mst_publisher.publisher_name LIKE '%$word%'
          GROUP BY mst_publisher.publisher_name ";
$a = $dbs->query($query);
foreach ($a as $value) {
  if ($no > 0) {
    $isi .= ', ';
  }
  $isi .= '"'.str_replace("\'","'",addslashes(str_replace(array('\"', "\'"), array('&quot;', "'"),$value['pna']))).'"';
  $no++;
}

$query = "SELECT mst_author.author_name as ana
          FROM mst_author
          WHERE mst_author.author_name LIKE '%$word%'
          ";
$a = $dbs->query($query);
foreach ($a as $value) {
  if ($no > 0) {
    $isi .= ', ';
  }
  $isi .= '"'.str_replace("\'","'",addslashes(str_replace(array('\"', "\'"), array('&quot;', "'"),$value['ana']))).'"';
  $no++;
}

$query = "SELECT mst_topic.topic as topic
          FROM mst_topic
          WHERE mst_topic.topic LIKE '%$word%'
          ";
$a = $dbs->query($query);
foreach ($a as $value) {
  if ($no > 0) {
    $isi .= ', ';
  }
  $isi .= '"'.str_replace("\'","'",addslashes(str_replace(array('\"', "\'"), array('&quot;', "'"),$value['topic']))).'"';
  $no++;
}

$isi .=']';
echo '{"suggestions" : '.$isi.'}';
 ?>
