<?php

/**
 * created 16  juni 2019
 * author indra sadik
 * tanpa halaman ini, pencarian data yg lebih dari 10
 * tidak dapat menampilkan record 11 - end 
 * halaman ini tidak ada
 */
define('INDEX_AUTH', '1');
	require '../../sysconfig.inc.php';
	// IP based access limitation
	require LIB.'ip_based_access.inc.php';
	do_checkIP('opac');
	// member session params
	require LIB.'member_session.inc.php';
	// custom function 
	require LIB.'custom.inc.php';

	require 'translation.php';
	
	# lets make this patch simple to understand
	if(isset($_GET)) $_GET 	= uri_secure($_GET , $sysconf['permitted_inp']);

	if(isset($_POST)) $_POST= uri_secure($_POST, $sysconf['permitted_inp']);

	// make it simple to debug and build
	$_p = array(); // kata=otonomi&offset=10&jenis=cari atau cari_local
	
	foreach($_POST as $_var=>$_val) $_p[$_var] = $_val;
	
$query = " SELECT korupsi.biblio_id as bid,
korupsi.title as title,
korupsi.call_number as cm,
korupsi.image as img,
mst_author.author_id as aid,
mst_author.author_name as ana,
mst_publisher.publisher_id as pid,
mst_publisher.publisher_name as pna,
mst_topic.topic_id as tid,
mst_topic.topic as topic,
MATCH (korupsi.title, korupsi.call_number) AGAINST ('".$_p['kata']."' IN NATURAL LANGUAGE MODE) as score
FROM korupsi
LEFT JOIN korupsi_author ON korupsi.biblio_id = korupsi_author.biblio_id
LEFT JOIN mst_author ON korupsi_author.author_id = mst_author.author_id
LEFT JOIN korupsi_topic ON korupsi.biblio_id = korupsi_topic.biblio_id
LEFT JOIN mst_topic ON korupsi_topic.topic_id = mst_topic.topic_id
LEFT JOIN mst_publisher ON korupsi.publisher_id = mst_publisher.publisher_id
WHERE
MATCH (korupsi.title, korupsi.call_number) AGAINST ('".$_p['kata']."' IN NATURAL LANGUAGE MODE) OR
MATCH (mst_author.author_name) AGAINST ('".$_p['kata']."' IN NATURAL LANGUAGE MODE) OR
MATCH (mst_publisher.publisher_name) AGAINST ('".$_p['kata']."' IN NATURAL LANGUAGE MODE) OR
MATCH (mst_topic.topic) AGAINST ('".$_p['kata']."' IN NATURAL LANGUAGE MODE) 

GROUP BY title
ORDER BY score DESC
  ";
$_qq = $query.' LIMIT 10 OFFSET '. $_p['offset'];

$datanya = $dbs->query($_qq);

$title = '';

$bid = '';

foreach ($datanya as $value) {
  $bid = $value['bid'];
  if ($title != $value['title']) { ?>
	<div class="col-md-12" style="">
	  <div class="row">
		<div class="col-md-2 center" style="padding-top:20px;">
		  <?php if (empty($value['img'])) {?>
			<img src="images/docs/book.png" alt="" style="border-radius:10px; width:150px; height:220px;">
		  <?php } else {?>
			<img src="images/docs/<?php echo $value['img'] ?>" alt="" style="border-radius:10px; width:150px; height:220px;">
		  <?php }
		   ?>

		</div>
		<div class="col-md-10">
		  <a href="index.php?h=show_detail_plu&id=<?php echo $value['bid'] ?>">
			<h4 style=""><?php echo $value['title'] ?></h4>
		  </a>
		  <div class="row">
			<div class="col-md-12">
			  <div class="col-md-6">
				<small><?php echo e("Penerbit") ?> :</small>
				<br>
				<?php echo $value['pna']?>
				<hr id="whatsapp">
			  </div>
			  <div class="col-md-6">
				<small><?php echo e("No. Panggilan") ?> : </small>
				<br>
				<?php echo $value['cm'] ?>
			  </div>
			  <div class="col-md-12">
				<hr>
			  </div>
			</div>
			<div class="col-md-12">
			  <div class="col-md-6">
				<small><?php echo e("Penulis") ?> :</small>
				<br>
				<small>
				  <?php
				  $query_author = "SELECT mst_author.author_id as author_id,
										  mst_author.author_name as author_name,
										  mst_author.authority_type as authority_type
										  FROM biblio_author
										  JOIN mst_author ON biblio_author.author_id = mst_author.author_id
										  WHERE biblio_author.biblio_id = '$bid' ";
				  $e_query_quthor = $dbs->query($query_author);
				  $no_author = 1;
				  foreach ($e_query_quthor as $author) {
					if ($no_author > 1 ) {
					  echo ' <br> ';
					}
					?>
					<a href="index.php?h=cari&kata=<?php echo $author['author_name']?>"><?php echo $author['author_name']?></a>
					-
					<?php
					if ($author['authority_type'] == 'p') {
					  echo "Personal Name";
					} elseif ($author['authority_type'] == 'o') {
					  echo "Organizational Body";
					} else {
					  echo "Conference";
					}
					?>
				  <?php
				  $no_author++; }
				   ?>
				</small>
				<hr id="whatsapp">
			  </div>
			  <div class="col-md-6">
				<small><?php echo e("Subjek") ?> :</small>
				<br>
				<?php
				$no = 1;
				$subject_query = $dbs->query("SELECT mst_topic.topic as topic,mst_topic.topic_id as topic_id
											  FROM biblio_topic
											  LEFT JOIN mst_topic ON biblio_topic.topic_id = mst_topic.topic_id
											  WHERE biblio_topic.biblio_id = '$bid'");
				foreach ($subject_query as $subject) {
				  if ($no > 1) {
					echo ',';
				  }
				  ?>
				  <a href="index.php?h=cari&kata=<?php echo $subject['topic'];?>"><?php echo addslashes($subject['topic']);?></a>
				<?php $no++; }
				 ?>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
	<div class="col-md-12">
	  <hr>
	</div>
  <?php  }

} ?>