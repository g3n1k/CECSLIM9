<?php
/**
 * Template for OPAC
 *
 * Copyright (C) 2015 Arie Nugraha (dicarve@gmail.com)
 * Create by Eddy Subratha (eddy.subratha@slims.web.id)
 *
 * Slims 8 (Akasia)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

// be sure that this file not accessed directly
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('INDEX_AUTH')) {
  die("can not access this file directly");
} elseif (INDEX_AUTH != 1) {
  die("can not access this file directly");
}

# lets make this patch simple to understand
if(isset($_GET)) $_GET 		= uri_secure($_GET , $sysconf['permitted_inp']);
if(isset($_POST)) $_POST 	= uri_secure($_POST, $sysconf['permitted_inp']);
?>
<!-- custom
==========================================================================
   ___  __    ____  __  __  ___      __    _  _    __    ___  ____    __
  / __)(  )  (_  _)(  \/  )/ __)    /__\  ( )/ )  /__\  / __)(_  _)  /__\
  \__ \ )(__  _)(_  )    ( \__ \   /(__)\  )  (  /(__)\ \__ \ _)(_  /(__)\
  (___/(____)(____)(_/\/\_)(___/  (__)(__)(_)\_)(__)(__)(___/(____)(__)(__)

==========================================================================
-->
<!DOCTYPE html>
<html lang="<?php echo substr($sysconf['default_lang'], 0, 2); ?>" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
<head>

<?php

$base_lang="id"; // Set the default language

  if(isset($_COOKIE["lang"])){
   $base_lang=$_COOKIE["lang"]; // Get language from cookie
  }

  if(isset($_GET["lang"])){
	setcookie("lang",strip_tags($_GET["lang"]),strtotime('+30 days'),'/', NULL, 0);
	$base_lang=strip_tags($_GET["lang"]); // Or set cookie and new language
  };



if(isset($_GET['search']) || isset($_GET['p'])) {
	include "partials/meta.php";
} else {
	include "partials/meta_new.php";
}
// Meta Template


?>

</head>

<body itemscope="itemscope" itemtype="http://schema.org/WebPage">
  <?php if(isset($_GET['search']) || isset($_GET['p'])): ?>
	<?php
	// Header
	include "partials/header.php";
	?>

	<?php
	// Navigation
	include "partials/nav.php";
	?>
  <?php endif; ?>
<!--[if lt IE 9]>
<div class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</div>
<![endif]-->



<?php
include "translation.php"
// Content
?>
<?php if(isset($_GET['search']) || isset($_GET['p'])): ?>
<section  id="content" class="s-main-page" role="main">

  <!-- Search on Front Page
  ============================================= -->
  <div class="s-main-search">
	<?php
	if(isset($_GET['p'])) {
	  switch ($_GET['p']) {
	  case ''             : $page_title = __('Collections'); break;
	  case 'show_detail'  : $page_title = __("Record Detail"); break;
	  case 'member'       : $page_title = __("Member Area"); break;
	  case 'member'       : $page_title = __("Member Area"); break;
	  default             : $page_title; break; }
	} else {
	  $page_title = __('Collections');
	}
	?>
	<h1 class="s-main-title animated fadeInUp delay1"><?php echo $page_title ?></h1>
	<form action="index.php" method="get" autocomplete="off">
	  <input type="text" id="keyword" class="s-search animated fadeInUp delay4" name="keywords" value="" lang="<?php echo $sysconf['default_lang']; ?>" role="search">
	  <button type="submit" name="search" value="search" class="s-btn animated fadeInUp delay4"><?php echo __('Search'); ?></button>
	</form>
	<a href="#" class="s-search-advances" width="800" height="500" title="<?php echo __('Advanced Search') ?>"><?php echo __('Advanced Search') ?></a>
  </div>

  <!-- Main
  ============================================= -->
  <div class="s-main-content container">
	<div class="row">

	  <!-- Show Result
	  ============================================= -->
	  <div class="col-lg-8 col-sm-9 col-xs-12 animated fadeInUp delay2">

		<?php
		  // Generate Output
		  // catch empty list
		  if(strlen($main_content) == 7) {
			echo '<h2>' . __('No Result') . '</h2><hr/><p>' . __('Please try again') . '</p>';
		  } else {
			echo $main_content;
		  }

		  // Somehow we need to hack the layout
		  if(isset($_GET['search']) || (isset($_GET['p']) && $_GET['p'] != 'member')){
			echo '</div>';
		  } else {
			if(isset($_SESSION['mid'])) {
			  echo  '</div></div>';
			}
		  }

		?>

	  <div class="col-lg-4 col-sm-3 col-xs-12 animated fadeInUp delay4">
		<?php if(isset($_GET['search'])) : ?>
		<h2><?php echo __('Search Result'); ?></h2>
		<hr>
		<?php echo $search_result_info; ?>
		<?php endif; ?>

		<br>

		<!-- If Member Logged
		============================================= -->
		<h2><?php echo __('Information'); ?></h2>
		<hr/>
		<p><?php echo (utility::isMemberLogin()) ? $header_info : $info; ?></p>
		<br/>

		<!-- Show if clustering search is enabled
		============================================= -->
		<?php
		  if(isset($_GET['keywords']) && (!empty($_GET['keywords']))) :
			if (($sysconf['enable_search_clustering'])) : ?>
			<h2><?php echo __('Search Cluster'); ?></h2>

			<hr/>

			<div id="search-cluster">
			  <div class="cluster-loading"><?php echo __('Generating search cluster...');  ?></div>
			</div>

			<script type="text/javascript">
			  $('document').ready( function() {
				$.ajax({
				  url     : 'index.php?p=clustering&q=<?php echo urlencode($criteria); ?>',
				  type    : 'GET',
				  success : function(data, status, jqXHR) { $('#search-cluster').html(data); }
				});
			  });
			</script>

			<?php endif; ?>
		  <?php endif; ?>
	  </div>
	</div>
  </div>

</section>

<?php else: ?>

<!-- Homepage
============================================= -->
<div class="scrollup">
	<i class="ion-ios-arrow-up"></i>
</div>

<div class="web-in fade-in">
  <nav id="light" class="transparent start-light regular">
	  <div class="container">
		  <div class="row">
			  <div class="col-md-12">
				  <div class="nav-ui n_">
					  <div>
						  <!-- <a href="#" class="btn hidden-xs start-project"><span>Download the App</span></a> -->
						  <!-- <a href="#" class="search">
							  <img class="white-icon" src="img/search.svg" alt="">
							  <img class="dark-icon" src="img/search-dark.svg" alt="">
						  </a> -->
						  <div id="menu-icon"><span></span></div>
					  </div>
				  </div>
				  <div class="logo-holder n_">
					  <div>
						  <a href="index.php">
							  <img class="logo" src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/lokok.png" style="height:80px;width:80px; border-radius:80px; margin-top:10px;" alt="" >
							  <img class="logo alt" src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/loko.png" style="height:80px;width:80px;" alt="">
						  </a>
					  </div>
				  </div>
				  <div class="nav-content n_ pull-right">
					  <ul>
						  <li class="tet"><a href="index.php" style="font-size:16px;"><?php echo e("beranda");?></a></li>
						  <li><a href="index.php?h=koleksi" style="font-size:16px;"><?php echo e("koleksi") ?></a></li>
						  <li><a href="index.php?h=plu" style="font-size:16px;"><?php echo e("publikasi lokal universitas") ?></a></li>
						  <li><a href="index.php?h=sarasehan" style="font-size:16px;"><?php echo e("aktivitas") ?></a></li>
						  <li><a href="index.php?h=newsletter" style="font-size:16px;"><?php echo e("newsletter") ?></a></li>
						  
						  <li>
							<?php
							if ($base_lang == "eng") { ?>
							  <a href="?lang=id" style="font-size:16px;">
								<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/indonesia.png" alt="" style="height:20px; width:20px; border-radius:100px;">
							  </a>
							<?php
						  } else {?>
							<a href="?lang=eng" style="font-size:16px;">
							  <img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/inggris.jpg" alt="" style="height:20px; width:20px; border-radius:100px;">
							</a>
						  <?php
						  }
							?>
							<!-- <ul class="dropdown mob-show-1" style="width:100px;">
								  <li class="nav-col">
									  <ul>
										  <li class="sub-nav-header">
											  <ul>
												  <li>
													<center><a href="#"><img src="img/inggris.jpg" alt="" style="height:18px; width:30px;"></a></center>
												  </li>
												  <li><center><a href="#"><img src="img/indonesia.png" alt="" style="height:18px; width:30px;"></a></center>
												  </li>
											  </ul>
										  </li>
									  </ul>
								  </li>
							  </ul> -->
						  </li>
					  </ul>
				  </div>
				  <div class="clearfix"></div>
			  </div>
		  </div>
	  </div>
  </nav>
  <!-- Paging -->
  <?php if (isset($_GET['h'])): ?>

	<?php if ($_GET['h'] == 'newsletter') { ?>

		<header id="sp3" class="center" data-overlay="8">
		  <div class="parallax" id="12">
			<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/bg0.jpg" alt="" class="" style="display:none;width:100%;height:100%;object-fit: cover;">
		  </div>
		  <div class="row">
			  <div class="col-md-6 col-md-offset-3">
				<form method="GET" action="index.php?">
					<input type="hidden" name="h" value="cari">
					<input name="kata" style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; color:white;" type="text" placeholder="" class="col-md-12 elastik2 ac">
					<input type="submit" name="" value="<?php echo e("Cari")?>" style="background-color:white;border-color:white;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
				</form>
			  </div>
		  </div>
		</header>
		<section class="iconblock gallery center" style="padding-top:50px;">
			<div class="container border">
				<div class="row">
					<div class="col-md-12">
					  <div class="pull-left">
						<h2 class=""><?php echo e("Newsletter") ?></h2>
					  </div>
					</div>
					<div class="col-md-12">
					  <hr>
					</div>
				</div>
				<div class="row m-space ">
					<div class="col-md-12 ">
						 <div class="col-xs-12">
							  <div class="">
								  <div class="form-group row">
									  <?php $_detail_link = SWB.'index.php?h=newsletter&newsletter_year='; ?>
									  <input type="hidden" value="<?php echo $_detail_link;?>" id="encoded_url"/>
									  <select id="newsletter_slc_year" class="form-control form-control-lg">
										  
											<?php 
												$selected_year = isset($_GET['newsletter_year']) ? $_GET['newsletter_year'] : date('Y');
												
												$query_newsletter_year = "select distinct(year) year from newsletter order by year DESC";
												
												$newsletter_years = $dbs->query($query_newsletter_year);
												
												if(!$newsletter_years->num_rows) 
													echo "<option value='".date('Y')."' selected> ".date('Y')."</option>";
													
												else {
													
													# catch if current/selected year dont have newsletter
													$_tmp_year = array();
													foreach ($newsletter_years as $_) $_tmp_year[] = $_['year'];
													if(!in_array($selected_year, $_tmp_year) ) $selected_year =  $_tmp_year[0];
													
													# now lets write the option
													foreach ($newsletter_years as $_) 
														echo "<option value='".$_['year']."'".($selected_year == $_['year'] ? ' selected':'').">".$_['year']."</option>";
												}
											?>
										</select>
								  </div>
							  </div>
						 </div>
					</div>
					<div class="col-md-12 ">
						<?php
						
							$_detail_link = SWB.'index.php?h=newsletter&newsletter_year='.$selected_year;
							$_detail_link_encoded = urlencode('http://'.$_SERVER['SERVER_NAME'].$_detail_link);
									
							$query_newsletter = "select * from newsletter where year=".$selected_year." order by month";
							$_data = $dbs->query($query_newsletter);
							foreach ($_data as $_){ ?>
								
								<div class="col-md-4" style="min-height:500px;">
								<a href="newsletter/<?php echo $_['attachment'] ?>">
								  <center>
									<i>
									  <?php 
										$_cover = !empty($_['cover']) ? "newsletter/".$_['cover'] : "images/docs/book.png";
										echo "<img src='".$_cover."' alt='' class='img-thumbnail' style='width: 200px;min-height: 250px;'>";
									   ?>
									</i>
								  </center>
									<h3>
									  <?php echo $_['title'];?>
									</h3>
								</a>
								  
								  <div class="team-profile">
									<?php echo e("Bagikan"); $id_biblio = ''; ?>
									<div class="social" style="">
										<a class="sharing" data-type="biblio" data-sosmed="facebook" data-id="<?php echo $id_biblio;?>" href="http://www.facebook.com/sharer.php?u=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-facebook" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="biblio" data-sosmed="twitter" data-id="<?php echo $id_biblio;?>" href="http://twitter.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-twitter" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="biblio" data-sosmed="google" data-id="<?php echo $id_biblio;?>" href="https://plus.google.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-googleplus" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="biblio" data-sosmed="linkedin" data-id="<?php echo $id_biblio;?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-linkedin-outline" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="biblio" data-sosmed="whatsapp" data-id="<?php echo $id_biblio;?>" href="whatsapp://send?text=<?php echo $_detail_link_encoded ?>" target="_blank" id="whatsapp">
										  <i class="ion-social-whatsapp-outline" style="color:black;"></i>
										</a>
									</div>
								  </div>
								</div>
					<?php	} ?>
					</div>
				</div>
			</div>
		</section>
	
				
	<?php } ?>
	<?php
	  if ($_GET['h'] == 'koleksi') {

		?>
		<header id="sp3" class="center" data-overlay="8">
		  <div class="parallax" id="12">
			<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/bg0.jpg" alt="" class="" style="display:none;width:100%;height:100%;object-fit: cover;">
		  </div>
		  <div class="row">
			  <div class="col-md-6 col-md-offset-3">
				<form method="GET" action="index.php?">
					<input type="hidden" name="h" value="cari">
					<input name="kata" style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; color:white;" type="text" placeholder="" class="col-md-12 elastik2 ac">
					<input type="submit" name="" value="<?php echo e("Cari")?>" style="background-color:white;border-color:white;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
				</form>
			  </div>
		  </div>
		</header>
		<section class="iconblock gallery center" style="padding-top:50px;">
			<div class="container border">
				<div class="row">
					<div class="col-md-12">
					  <div class="pull-left">
						<h2 class=""><?php echo e("Koleksi") ?></h2>
					  </div>
					</div>
					<div class="col-md-12">
					  <hr>
					</div>
				</div>
				<div class="row m-space ">
				  <div id="" class="area" style="padding-top:10px;">
					  <div class="col-md-12 ">
							<?php
							if (isset($_GET['sp'])) {
								$sp = $_GET['sp'];
								$page = $_GET['sp'];
							} else {
							  $sp = 1;
							  $page = $_GET['sp'];
							}

							if ($sp < 2) {
							  $batas = 0;
							}else {
							  $batas = $sp * 12;
							}

							$query_pop = "SELECT biblio_id, title, image FROM biblio ORDER BY biblio_id DESC LIMIT 12 OFFSET $batas";
							$data_pop = $dbs->query($query_pop);

							$datanya = "SELECT biblio_id, title, image FROM biblio";
							$data_ori = $dbs->query($datanya);
							$jml = $data_ori->num_rows;

							?>

							<div class="col-xs-12">
							  <div class="pagination pull-right">
							  <!-- LINK FIRST AND PREV -->
							  <?php
							  if($sp == 1){ // Jika page adalah page ke 1, maka disable link PREV
							  ?>
								<a href="#" class="disabled"><?php echo e("Awal") ?></a> &nbsp;
								<a href="#" class="disabled">&laquo;</a>&nbsp;
							  <?php
							  }else{ // Jika page bukan page ke 1
								$link_prev = ($sp > 1)? $sp - 1 : 1;
							  ?>
								<a href="index.php?h=koleksi&sp=1"><?php echo e("Awal") ?></a>&nbsp;
								<a href="index.php?h=koleksi&sp=<?php echo $link_prev; ?>">&laquo;</a>&nbsp;
							  <?php
							  }
							  ?>

							  <!-- LINK NUMBER -->
							  <?php

							  $jumlah_page = (ceil($jml / 12))-1; // Hitung jumlah halamannya
							  $jumlah_number = 2; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
							  $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
							  $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number

							  for($i = $start_number; $i <= $end_number; $i++){
								$link_active = ($page == $i)? ' class="active"' : '';
							  ?>
								<a<?php echo $link_active; ?> href="index.php?h=koleksi&sp=<?php echo $i; ?>"><?php echo $i; ?></a>&nbsp;
							  <?php
							  }
							  ?>

							  <!-- LINK NEXT AND LAST -->
							  <?php
							  // Jika page sama dengan jumlah page, maka disable link NEXT nya
							  // Artinya page tersebut adalah page terakhir
							  if($page == $jumlah_page){ // Jika page terakhir
							  ?>
								<a class="disabled" href="#">&raquo;</a>&nbsp;
								<a class="disabled" href="#"><?php echo e("Akhir") ?></a>
							  <?php
							  }else{ // Jika Bukan page terakhir
								$link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
							  ?>
								<a href="index.php?h=koleksi&sp=<?php echo $link_next; ?>">&raquo;</a>
								<a href="index.php?h=koleksi&sp=<?php echo $jumlah_page; ?>"><?php echo e("Akhir") ?></a>
							  <?php
							  }
							  ?>
							  </div>
							  <br><br>
							</div>
							<div class="col-xs-12">
							  <?php
							  foreach ($data_pop as $value) {?>

								<div class="col-md-4" style="min-height:500px;">
								  <center>
									<i>
									  <?php if (empty($value['image'])) {?>
										<img src="images/docs/book.png" alt="" style="border-radius:10px; width:150px; height:220px;">
									  <?php } else {?>
										<img src="images/docs/<?php echo $value['image'] ?>" alt="" style="border-radius:10px; width:150px; height:220px;">
									  <?php }
									   ?>
									</i>
								  </center>
								  <a href="index.php?h=show_detail&id=<?php echo $value['biblio_id'] ?>">
									<h3>
									  <?php echo substr($value['title'], 0,35);
									  if (strlen($value['title']) > 35) {
										echo "...";
									  }
									   ?>
									</h3>
								  </a>
								  <p style="">
									<?php
									$id_biblio = $value['biblio_id'];
									$query_author = "SELECT mst_author.author_name as author
													  FROM biblio_author
													  LEFT JOIN mst_author ON biblio_author.author_id = mst_author.author_id
													  WHERE biblio_author.biblio_id = '$id_biblio'
													  ";
									$data_author = $dbs->query($query_author);
									$no_author = 0;
									$author = '';
									foreach ($data_author as $value_author) {
									  if ($no_author > 0){
										$author .= ' | ';
									  };
									  $author .= $value_author['author'];
									  $no_author++;
									};
									echo substr($author,0,30);

									echo '<br><center>'. showBadge($id).'</center><br />';

									$_detail_link = SWB.'index.php?p=show_detail&id='.$id_biblio;
									$_detail_link_encoded = urlencode('http://'.$_SERVER['SERVER_NAME'].$_detail_link);
									 ?>
								  </p>
								  <div class="team-profile">
									<?php echo e("Bagikan") ?>
									<div class="social" style="">
										<a class="sharing" data-type="biblio" data-sosmed="facebook" data-id="<?php echo $id_biblio?>" href="http://www.facebook.com/sharer.php?u=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-facebook" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="biblio" data-sosmed="twitter" data-id="<?php echo $id_biblio?>" href="http://twitter.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-twitter" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="biblio" data-sosmed="google" data-id="<?php echo $id_biblio?>" href="https://plus.google.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-googleplus" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="biblio" data-sosmed="linkedin" data-id="<?php echo $id_biblio?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-linkedin-outline" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="biblio" data-sosmed="whatsapp" data-id="<?php echo $id_biblio?>" href="whatsapp://send?text=<?php echo $_detail_link_encoded ?>" target="_blank" id="whatsapp">
										  <i class="ion-social-whatsapp-outline" style="color:black;"></i>
										</a>
									</div>
									<br>
									<br>
								  </div>
								</div>
							  <?php
							  }
							  ?>
							</div>
							<div class="col-xs-12">
							  <div class=" pagination pull-right">
							  <!-- LINK FIRST AND PREV -->
							  <?php
							  if($sp == 1){ // Jika page adalah page ke 1, maka disable link PREV
							  ?>
								<a href="#" class="disabled">Awal</a> &nbsp;
								<a href="#" class="disabled">&laquo;</a>&nbsp;
							  <?php
							  }else{ // Jika page bukan page ke 1
								$link_prev = ($sp > 1)? $sp - 1 : 1;
							  ?>
								<a href="index.php?h=koleksi&sp=1">Awal</a>&nbsp;
								<a href="index.php?h=koleksi&sp=<?php echo $link_prev; ?>">&laquo;</a>&nbsp;
							  <?php
							  }
							  ?>

							  <!-- LINK NUMBER -->
							  <?php

							  $jumlah_page = (ceil($jml / 12))-1; // Hitung jumlah halamannya
							  $jumlah_number = 2; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
							  $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
							  $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number

							  for($i = $start_number; $i <= $end_number; $i++){
								$link_active = ($page == $i)? ' class="active"' : '';
							  ?>
								<a<?php echo $link_active; ?> href="index.php?h=koleksi&sp=<?php echo $i; ?>"><?php echo $i; ?></a>&nbsp;
							  <?php
							  }
							  ?>

							  <!-- LINK NEXT AND LAST -->
							  <?php
							  // Jika page sama dengan jumlah page, maka disable link NEXT nya
							  // Artinya page tersebut adalah page terakhir
							  if($page == $jumlah_page){ // Jika page terakhir
							  ?>
								<a class="disabled" href="#">&raquo;</a>&nbsp;
								<a class="disabled" href="#"><?php echo e("Akhir") ?></a>
							  <?php
							  }else{ // Jika Bukan page terakhir
								$link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
							  ?>
								<a href="index.php?h=koleksi&sp=<?php echo $link_next; ?>">&raquo;</a>
								<a href="index.php?h=koleksi&sp=<?php echo $jumlah_page; ?>"><?php echo e("Akhir") ?></a>
							  <?php
							  }
							  ?>
							  <br><br>
							  </div>
							</div>
					  </div>
				  </div>
				</div>
			</div>
		</section>

		<!-- Halaman index publikasi lokal universitas -->
	  <?php } elseif ($_GET['h'] == 'plu') { ?>
		<header id="sp3" class="center" data-overlay="8">
		  <div class="parallax" id="12">
			<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/bg0.jpg" alt="" class="" style="display:none;width:100%;height:100%;object-fit: cover;">
		  </div>
		  <div class="row">
			  <div class="col-md-6 col-md-offset-3">
				<form method="GET" action="index.php?">
					<input type="hidden" name="h" value="cari_lokal">
					<input name="kata" style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; color:white;" type="text" placeholder="" class="col-md-12 elastik5 ac">
					<input type="submit" name="" value="<?php echo e("Cari")?>" style="background-color:white;border-color:white;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
				</form>
			  </div>
		  </div>
		</header>
		<section class="iconblock gallery center" style="padding-top:50px;">
			<div class="container border">
				<div class="row">
					<div class="col-md-12">
					  <div class="pull-left">
						<h2 class=""><?php echo e("Publikasi Lokal Universitas") ?></h2>
					  </div>
					</div>
					<div class="col-md-12">
					  <hr>
					</div>
				</div>
				<div class="row m-space ">
				  <div id="" class="area" style="padding-top:10px;">
					  <div class="col-md-12 ">
							<?php
							if (isset($_GET['sp'])) {
								$sp = $_GET['sp'];
								$page = $_GET['sp'];
							} else {
							  $sp = 1;
							  $page = $_GET['sp'];
							}

							if ($sp < 2) {
							  $batas = 0;
							}else {
							  $batas = $sp * 12;
							}

							$query_pop = "SELECT biblio_id, title, image FROM korupsi ORDER BY biblio_id DESC LIMIT 12 OFFSET $batas";
							$data_pop = $dbs->query($query_pop);

							$datanya = "SELECT biblio_id, title, image FROM korupsi";
							$data_ori = $dbs->query($datanya);
							$jml = $data_ori->num_rows;

							?>

							<div class="col-xs-12">
							  <div class="pagination pull-right">
							  <!-- LINK FIRST AND PREV -->
							  <?php
							  if($sp == 1){ // Jika page adalah page ke 1, maka disable link PREV
							  ?>
								<a href="#" class="disabled"><?php echo e("Awal") ?></a> &nbsp;
								<a href="#" class="disabled">&laquo;</a>&nbsp;
							  <?php
							  }else{ // Jika page bukan page ke 1
								$link_prev = ($sp > 1)? $sp - 1 : 1;
							  ?>
								<a href="index.php?h=plu&sp=1"><?php echo e("Awal") ?></a>&nbsp;
								<a href="index.php?h=plu&sp=<?php echo $link_prev; ?>">&laquo;</a>&nbsp;
							  <?php
							  }
							  ?>

							  <!-- LINK NUMBER -->
							  <?php

							  $jumlah_page = (ceil($jml / 12))-1; // Hitung jumlah halamannya
							  $jumlah_number = 2; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
							  $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
							  $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number

							  for($i = $start_number; $i <= $end_number; $i++){
								$link_active = ($page == $i)? ' class="active"' : '';
							  ?>
								<a<?php echo $link_active; ?> href="index.php?h=plu&sp=<?php echo $i; ?>"><?php echo $i; ?></a>&nbsp;
							  <?php
							  }
							  ?>

							  <!-- LINK NEXT AND LAST -->
							  <?php
							  // Jika page sama dengan jumlah page, maka disable link NEXT nya
							  // Artinya page tersebut adalah page terakhir
							  if($page == $jumlah_page){ // Jika page terakhir
							  ?>
								<a class="disabled" href="#">&raquo;</a>&nbsp;
								<a class="disabled" href="#"><?php echo e("Akhir") ?></a>
							  <?php
							  }else{ // Jika Bukan page terakhir
								$link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
							  ?>
								<a href="index.php?h=plu&sp=<?php echo $link_next; ?>">&raquo;</a>
								<a href="index.php?h=plu&sp=<?php echo $jumlah_page; ?>"><?php echo e("Akhir") ?></a>
							  <?php
							  }
							  ?>
							  <br><br>
							  <br>
							  </div>
							</div>
							<div class="col-xs-12">
							  <?php
							  foreach ($data_pop as $value) {?>

								<div class="col-md-4" style="min-height:450px;">
								  <center>
									<i>
									  <?php if (empty($value['image'])) {?>
										<img src="images/docs/book.png" alt="" style="border-radius:10px; width:150px; height:220px;">
									  <?php } else {?>
										<img src="images/docs/<?php echo $value['image'] ?>" alt="" style="border-radius:10px; width:150px; height:220px;">
									  <?php }
									   ?>
									</i>
								  </center>
								  <a href="index.php?h=show_detail_plu&id=<?php echo $value['biblio_id'] ?>">
									<h3>
									  <?php echo substr($value['title'], 0,35);
									  if (strlen($value['title']) > 35) {
										echo "...";
									  }
									   ?>
									</h3>
								  </a>
								  <p style="">
									<?php
									$id_biblio = $value['biblio_id'];
									$query_author = "SELECT mst_author.author_name as author
													  FROM korupsi_author
													  LEFT JOIN mst_author ON korupsi_author.author_id = mst_author.author_id
													  WHERE korupsi_author.biblio_id = '$id_biblio'
													  ";
									$data_author = $dbs->query($query_author);
									$no_author = 0;
									$author = '';
									foreach ($data_author as $value_author) {
									  if ($no_author > 0){
										$author .= ' | ';
									  };
									  $author .= $value_author['author'];
									  $no_author++;
									};
									echo substr($author,0,30);

									$_detail_link = SWB.'index.php?p=show_detail&id='.$id_biblio;
									$_detail_link_encoded = urlencode('http://'.$_SERVER['SERVER_NAME'].$_detail_link);
									 ?>
								  </p>
								  <div class="team-profile">
									<?php echo e("Bagikan") ?>
									<div class="social" style="">
										<a class="sharing" data-type="lokal" data-sosmed="facebook" data-id="<?php echo $id_biblio?>" href="http://www.facebook.com/sharer.php?u=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-facebook" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="lokal" data-sosmed="twitter" data-id="<?php echo $id_biblio?>" href="http://twitter.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-twitter" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="lokal" data-sosmed="google" data-id="<?php echo $id_biblio?>" href="https://plus.google.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-googleplus" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="lokal" data-sosmed="linkedin" data-id="<?php echo $id_biblio?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $_detail_link_encoded ?>" target="_blank">
										  <i class="ion-social-linkedin-outline" style="color:black;"></i>
										</a>
										<a class="sharing" data-type="lokal" data-sosmed="whatsapp" data-id="<?php echo $id_biblio?>" href="whatsapp://send?text=<?php echo $_detail_link_encoded ?>" target="_blank" id="whatsapp">
										  <i class="ion-social-whatsapp-outline" style="color:black;"></i>
										</a>
									</div>
									<br>
									<br>
								  </div>
								</div>
							  <?php
							  }
							  ?>
							</div>
							<div class="col-xs-12">
							  <div class="pagination pull-right">
							  <!-- LINK FIRST AND PREV -->
							  <?php
							  if($sp == 1){ // Jika page adalah page ke 1, maka disable link PREV
							  ?>
								<a href="#" class="disabled"><?php echo e("Akhir") ?></a> &nbsp;
								<a href="#" class="disabled">&laquo;</a>&nbsp;
							  <?php
							  }else{ // Jika page bukan page ke 1
								$link_prev = ($sp > 1)? $sp - 1 : 1;
							  ?>
								<a href="index.php?h=plu&sp=1"><?php echo e("Awal") ?></a>&nbsp;
								<a href="index.php?h=plu&sp=<?php echo $link_prev; ?>">&laquo;</a>&nbsp;
							  <?php
							  }
							  ?>

							  <!-- LINK NUMBER -->
							  <?php

							  $jumlah_page = (ceil($jml / 12))-1; // Hitung jumlah halamannya
							  $jumlah_number = 2; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
							  $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
							  $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number

							  for($i = $start_number; $i <= $end_number; $i++){
								$link_active = ($page == $i)? ' class="active"' : '';
							  ?>
								<a<?php echo $link_active; ?> href="index.php?h=plu&sp=<?php echo $i; ?>"><?php echo $i; ?></a>&nbsp;
							  <?php
							  }
							  ?>

							  <!-- LINK NEXT AND LAST -->
							  <?php
							  // Jika page sama dengan jumlah page, maka disable link NEXT nya
							  // Artinya page tersebut adalah page terakhir
							  if($page == $jumlah_page){ // Jika page terakhir
							  ?>
								<a class="disabled" href="#">&raquo;</a>&nbsp;
								<a class="disabled" href="#"><?php echo e("Akhir") ?></a>
							  <?php
							  }else{ // Jika Bukan page terakhir
								$link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
							  ?>
								<a href="index.php?h=plu&sp=<?php echo $link_next; ?>">&raquo;</a>
								<a href="index.php?h=plu&sp=<?php echo $jumlah_page; ?>"><?php echo e("Akhir") ?></a>
							  <?php
							  }
							  ?>
							  <br><br>
							  </div>
							</div>
					  </div>
				  </div>
				</div>
			</div>
		</section>

		<!-- Halaman index Sarasehan -->
	  <?php
	} elseif ($_GET['h'] == 'sarasehan') {?>
		<header id="sp3" class="center" data-overlay="8">
		  <div class="parallax" id="12">
			<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/bg0.jpg" alt="" class="" style="display:none;width:100%;height:100%;object-fit: cover;">
		  </div>
		  <div class="row">
			  <!-- <div class="col-md-6 col-md-offset-3">
				<form method="GET" action="index.php?">
					<input type="hidden" name="h" value="sarasehan">
					<input name="kata" style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; color:white;" type="text" placeholder="" class="col-md-12 elastik9 ac">
					<input type="submit" name="" value="Cari" style="background-color:white;border-color:white;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
				</form>
			  </div> -->
		  </div>
		</header>

		<section>
			<div class="container">
				<div class="row">
					<!-- Blog -->
					<div class="col-md-12">
					  <div class="pull-left">
						<h2 class=""><?php echo e("Aktivitas") ?></h2>
					  </div>
					  <div class="row">
						  <div class="col-md-6 pull-right">
							<form method="GET" action="index.php?">
								<input type="hidden" name="h" value="sarasehan">
								<input name="kata" style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px;" type="text" placeholder="" class="col-md-12 elastik9 ac">
								<input type="submit" name="" value="<?php echo e("Cari") ?>" style="background-color:#E9ECEF;border-color:#E9ECEF;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
							</form>
						  </div>
					  </div>
					</div>
					<div class="col-md-12">
					  <hr>
					</div>
						  <?php
						  if (isset($_GET['sp'])) {
							  $sp = $_GET['sp'];
							  $page = $_GET['sp'];
						  } else {
							$sp = 1;
							$page = $_GET['sp'];
						  }

						  if ($sp < 2) {
							$batas = 0;
						  }else {
							$batas = $sp * 12;
						  }
						  $kata = '';
						  if (isset($_GET['kata'])) {
							$kata = utility::filter_string($_GET['kata']);
						  }

						  $query_pop = "SELECT id, ss_title, ss_pic, ss_desk, tgl_update FROM sarasehan WHERE ss_title LIKE '%$kata%' ORDER BY id DESC LIMIT 12 OFFSET $batas";
						  $data_pop = $dbs->query($query_pop);

						  $datanya = "SELECT id FROM sarasehan WHERE ss_title LIKE '%$kata%'";
						  $data_ori = $dbs->query($datanya);
						  $jml = $data_ori->num_rows;

						  ?>

						  <div class="col-xs-12">
							<div class="pagination pull-right">
							<!-- LINK FIRST AND PREV -->
							<?php
							if($sp == 1){ // Jika page adalah page ke 1, maka disable link PREV
							?>
							  <a href="#" class="disabled"><?php echo e("Awal") ?></a> &nbsp;
							  <a href="#" class="disabled">&laquo;</a>&nbsp;
							<?php
							}else{ // Jika page bukan page ke 1
							  $link_prev = ($sp > 1)? $sp - 1 : 1;
							?>
							  <a href="index.php?h=sarasehan&kata=<?php echo $kata ?>&sp=1"><?php echo e("Awal") ?></a>&nbsp;
							  <a href="index.php?h=sarasehan&kata=<?php echo $kata ?>&sp=<?php echo $link_prev; ?>">&laquo;</a>&nbsp;
							<?php
							}
							?>

							<!-- LINK NUMBER -->
							<?php

							$jumlah_page = (ceil($jml / 12))-1; // Hitung jumlah halamannya
							$jumlah_number = 2; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
							$start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
							$end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number

							for($i = $start_number; $i <= $end_number; $i++){
							  $link_active = ($page == $i)? ' class="active"' : '';
							?>
							  <a<?php echo $link_active; ?> href="index.php?h=sarasehan&kata=<?php echo $kata ?>&sp=<?php echo $i; ?>"><?php echo $i; ?></a>&nbsp;
							<?php
							}
							?>

							<!-- LINK NEXT AND LAST -->
							<?php
							// Jika page sama dengan jumlah page, maka disable link NEXT nya
							// Artinya page tersebut adalah page terakhir
							if($page == $jumlah_page){ // Jika page terakhir
							?>
							  <a class="disabled" href="#">&raquo;</a>&nbsp;
							  <a class="disabled" href="#"><?php echo e("Akhir") ?></a>
							<?php
							}else{ // Jika Bukan page terakhir
							  $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
							?>
							  <a href="index.php?h=sarasehan&kata=<?php echo $kata ?>&sp=<?php echo $link_next; ?>">&raquo;</a>
							  <a href="index.php?h=sarasehan&kata=<?php echo $kata ?>&sp=<?php echo $jumlah_page; ?>"><?php echo e("Akhir") ?></a>
							<?php
							}
							?>
							<br><br>
							<br>
							</div>
						  </div>

						  <!--  -->
						  <div class="col-xs-12">
							<div class="blog three-col">
								<div class="blog-sizer"></div>
								<?php
								foreach ($data_pop as $value) { ?>
								  <div class="blog-item">
									  <div class="thumb1">
										  <a href="index.php?h=sarasehan_detail&id=<?php echo $value['id']?>">
											  <img src="images/sarasehan/<?php echo $value['ss_pic']?>" alt="">
										  </a>
									  </div>
									  <div class="article">
										  <a href="index.php?h=sarasehan_detail&id=<?php echo $value['id']?>">
											  <p><?php echo date("d M Y", strtotime($value['tgl_update'])); ?></p>
											  <h3><?php echo $value['ss_title'] ?></h3>
										  </a>
										  <p>
											<?php echo substr($value['ss_desk'],0,150).'...' ?>
										  </p>
									  </div>
								  </div>
								<?php }
								 ?>
							</div>
						  </div>
						  <!--  -->

						  <div class="col-xs-12">
							<div class=" pagination pull-right">
							<!-- LINK FIRST AND PREV -->
							<?php
							if($sp == 1){ // Jika page adalah page ke 1, maka disable link PREV
							?>
							  <a href="#" class="disabled"><?php echo e("Awal") ?></a> &nbsp;
							  <a href="#" class="disabled">&laquo;</a>&nbsp;
							<?php
							}else{ // Jika page bukan page ke 1
							  $link_prev = ($sp > 1)? $sp - 1 : 1;
							?>
							  <a href="index.php?h=sarasehan&sp=1">Awal</a>&nbsp;
							  <a href="index.php?h=sarasehan&sp=<?php echo $link_prev; ?>">&laquo;</a>&nbsp;
							<?php
							}
							?>

							<!-- LINK NUMBER -->
							<?php

							$jumlah_page = (ceil($jml / 12))-1; // Hitung jumlah halamannya
							$jumlah_number = 2; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
							$start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
							$end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number

							for($i = $start_number; $i <= $end_number; $i++){
							  $link_active = ($page == $i)? ' class="active"' : '';
							?>
							  <a<?php echo $link_active; ?> href="index.php?h=sarasehan&sp=<?php echo $i; ?>"><?php echo $i; ?></a>&nbsp;
							<?php
							}
							?>

							<!-- LINK NEXT AND LAST -->
							<?php
							// Jika page sama dengan jumlah page, maka disable link NEXT nya
							// Artinya page tersebut adalah page terakhir
							if($page == $jumlah_page){ // Jika page terakhir
							?>
							  <a class="disabled" href="#">&raquo;</a>&nbsp;
							  <a class="disabled" href="#"><?php echo e("Akhir") ?></a>
							<?php
							}else{ // Jika Bukan page terakhir
							  $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
							?>
							  <a href="index.php?h=sarasehan&sp=<?php echo $link_next; ?>">&raquo;</a>
							  <a href="index.php?h=sarasehan&sp=<?php echo $jumlah_page; ?>"><?php echo e("Akhir") ?></a>
							<?php
							}
							?>
							<br><br>
							</div>
						  </div>
				</div>
			</div>
		</section>
	  <?php } elseif ($_GET['h'] == 'sarasehan_detail') {
		$idnya = $_GET['id'];
		$query = "SELECT *,
				  (SELECT COUNT(id_share) FROM sharing WHERE content_id = '$idnya' AND tipe = 'sarasehan' AND sosmed = 'facebook') AS fb,
				  (SELECT COUNT(id_share) FROM sharing WHERE content_id = '$idnya' AND tipe = 'sarasehan' AND sosmed = 'twitter') AS tw,
				  (SELECT COUNT(id_share) FROM sharing WHERE content_id = '$idnya' AND tipe = 'sarasehan' AND sosmed = 'google') AS goo,
				  (SELECT COUNT(id_share) FROM sharing WHERE content_id = '$idnya' AND tipe = 'sarasehan' AND sosmed = 'linkedin') AS li,
				  (SELECT COUNT(id_share) FROM sharing WHERE content_id = '$idnya' AND tipe = 'sarasehan' AND sosmed = 'whatsapp') AS wa
				  FROM sarasehan WHERE id = '$idnya'";
		$data = $dbs->query($query);
		foreach ($data as $value) {
		  $title = $value['ss_title'];
		  $tgl = $value['tgl_update'];
		  $isi = $value['ss_desk'];
		  $gbr = $value['ss_pic'];
		  $dilihat = $value['dilihat']+1;
		  $fbdia = $value['fb'];
		  $twdia = $value['tw'];
		  $goodia = $value['goo'];
		  $lidia = $value['li'];
		  $wadia = $value['wa'];
		}
		$qupdate = "UPDATE sarasehan SET dilihat = '$dilihat' WHERE id = '$idnya'";
		$eupdate = $dbs->query($qupdate);
		?>
		<header id="sp3" class="center" data-overlay="8">
		  <div class="parallax" id="12">
			<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/bg0.jpg" alt="" class="" style="display:none;width:100%;height:100%;object-fit: cover;">
		  </div>
		</header>
		<section>
			<div class="container">
				<div class="col-md-9">
				  <div class="row m-space">
					  <div class="col-md-12">
						<center>
						  <img src="images/sarasehan/<?php echo $gbr ?>" alt="" style="max-height:350px;max-width:100%;">
						</center>
					  </div>
				  </div>
					<div class="row">
						<div class="col-md-12">
							<h2><?php echo $title ?></h2>
							<small><?php echo date('d M Y',strtotime($tgl)) ?> | <?php echo e("Dilihat") ?> : <?php echo $dilihat.' '.e("kali") ?></small>
							<?php echo $isi ?>
						</div>
					</div>
					<div class="blog-social">
						<div class="row">
							<div class="col-sm-6 hidden-xs">

							</div>
							<div class="col-sm-6 col-xs-12 right xs-center">
								  <small>
									<center>
									  <table style="width:100%;">
										<tr>
										  <td style="width:16%; text-align:center;">
											<center>
											  <a class="sharing" data-type="sarasehan" data-sosmed="facebook" data-id="<?php echo $idnya?>" href="http://www.facebook.com/sharer.php?u=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
												<i class="ion-social-facebook"></i>
												<br>
												<sup><small><?php echo $fbdia ?></small></sup>
											  </a>
											</center>
										  </td>
										  <td style="width:16%; text-align:center;">
											<a class="sharing" data-type="sarasehan" data-sosmed="twitter" data-id="<?php echo $idnya?>" href="http://twitter.com/share?url=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
											  <i class="ion-social-twitter" ></i>
											  <br>
											  <sup><small><?php echo $twdia ?></small></sup>
											</a>
										  </td>
										  <td style="width:16%; text-align:center;">
											<a class="sharing" data-type="sarasehan" data-sosmed="google" data-id="<?php echo $idnya?>" href="https://plus.google.com/share?url=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
											  <i class="ion-social-googleplus" ></i>
											  <br>
											  <sup><small><?php echo $goodia ?></small></sup>
											</a>
										  </td>
										  <td style="width:16%; text-align:center;">
											<a class="sharing" data-type="sarasehan" data-sosmed="linkedin" data-id="<?php echo $idnya?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
											  <i class="ion-social-linkedin-outline" ></i>
											  <br>
											  <sup><small><?php echo $lidia ?></small></sup>
											</a>
										  </td>
										  <td style="width:16%; text-align:center;">
											<a class="sharing" data-type="sarasehan" data-sosmed="whatsapp" data-id="<?php echo $idnya?>" href="whatsapp://send?text=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank" id="whatsapp">
											  <i class="ion-social-whatsapp-outline" ></i>
											  <br>
											  <sup><small><?php echo $wadia ?></small></sup>
											</a>
										  </td>
										</tr>
									  </table>
									</center>
								  </small>

							</div>
						</div>
						<!-- <div class="row m-space blog-controls hidden-xs">
							<div class="col-sm-6">
								<a class="prev-post" href="#"><i class="ion-chevron-left"></i>Section off your development crew</a>
							</div>
							<div class="col-sm-6 right">
								<a class="next-post" href="#">Know how to boost your SEO<i class="ion-chevron-right"></i></a>
							</div>
						</div> -->
					</div>
				</div>
				<div class="col-md-3 sidebar">
					<div class="item">
					  <div class="row">
						  <div class="col-md-12">
							<form method="GET" action="index.php?">
								<input type="hidden" name="h" value="sarasehan">
								<input name="kata" style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px;" type="text" placeholder="" class="col-md-12 elastik10 ac">
								<input type="submit" name="" value="<?php echo e("Cari")?>" style="background-color:#E9ECEF;border-color:#E9ECEF;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
							</form>
						  </div>
					  </div>
						<h3><?php echo e("Terbaru") ?></h3>
						<hr>
						<?php
						  $tquery = "SELECT ss_pic, ss_title, id, tgl_update, dilihat FROM sarasehan ORDER BY id DESC LIMIT 5";
						  $etquery = $dbs->query($tquery);
						  foreach ($etquery as $value) { ?>
							<div class="row">
								<a href="index.php?h=sarasehan_detail&id=<?php echo $value['id']?>">
									<div class="col-xs-4 image-fw">
										<img src="images/sarasehan/<?php echo $value['ss_pic']?>" alt="" style="height: 50px;object-fit:cover;">
									</div>
									<div class="col-xs-8">
										<h3 class="lp-title"><?php echo substr($value['ss_title'],0,30).'...'; ?></h3>
									</div>
								</a>
							</div>
						  <?php }
						 ?>
					</div>
				</div>
			</div>
		</section>

		<!-- Halaman Detail bibliography umum -->
	  <?php } elseif ($_GET['h'] == 'show_detail') {
		$id = $_GET['id'];
		$tgl = date('Y-m-d');
		$query_pengunjung = "INSERT INTO pengunjung VALUES(NULL,'$id',".date('Y').",'$tgl','0')";
		$e_query_pengunjung = $dbs->query($query_pengunjung);

		// dapatkan jumlah pengunjung
		$query_jml_pengunjung = "SELECT id_pengunjung FROM pengunjung WHERE biblio_id = '$id' AND type = '0'";
		$data_jml_pengunjung = $dbs->query($query_jml_pengunjung);
		$jml_pengunjung = $data_jml_pengunjung->num_rows;

		// dapatkan jumlah pembaca
		$q_baca = "SELECT id_baca FROM baca WHERE biblio_id = '$id'";
		$d_baca = $dbs->query($q_baca);
		$jml_baca = $d_baca->num_rows;

		// update jumlah visit & baca pada table biblio, dpnt know why they using table baca and peng
		$query_visit_baca = "UPDATE biblio set visit = ".$jml_pengunjung.", baca=".$jml_baca."  WHERE biblio_id = '$id'";
		$dbs->query($query_visit_baca);

		// dapatkan jumlah peminjam
		$q_pinjam = "SELECT loan_id FROM loan
					JOIN item ON loan.item_code = item.item_code
					WHERE item.biblio_id = '$id'";
		$d_pinjam = $dbs->query($q_pinjam);
		$jml_pinjam = $d_pinjam->num_rows;


		// dapatkan data buku
		$query_buku = "SELECT *,
					  (SELECT COUNT(id_share) FROM sharing WHERE content_id = '$id' AND tipe = 'biblio' AND sosmed = 'facebook') AS fb,
					  (SELECT COUNT(id_share) FROM sharing WHERE content_id = '$id' AND tipe = 'biblio' AND sosmed = 'twitter') AS tw,
					  (SELECT COUNT(id_share) FROM sharing WHERE content_id = '$id' AND tipe = 'biblio' AND sosmed = 'google') AS goo,
					  (SELECT COUNT(id_share) FROM sharing WHERE content_id = '$id' AND tipe = 'biblio' AND sosmed = 'linkedin') AS li,
					  (SELECT COUNT(id_share) FROM sharing WHERE content_id = '$id' AND tipe = 'biblio' AND sosmed = 'whatsapp') AS wa
					  FROM biblio WHERE biblio_id = '$id'";
		$data_buku = $dbs->query($query_buku);
		foreach ($data_buku as $buku) {
		?>
		<header id="sp3" class="center" data-overlay="8">
		  <div class="parallax" id="12">
			<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/bg0.jpg" alt="" class="" style="display:none;width:100%;height:100%;object-fit: cover;">
		  </div>
		  <div class="row">
			  <div class="col-md-6 col-md-offset-3">
				<form method="GET" action="index.php?">
					<input type="hidden" name="h" value="cari">
					<input name="kata" style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; color:white;" type="text" placeholder="" class="col-md-12 elastik3 ac">
					<input type="submit" name="" value="<?php echo e("Cari")?>" style="background-color:white;border-color:white;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
				</form>
			  </div>
		  </div>
		</header>
		<section>
		  <div class="container">
			<div class="row">
			  <div class="col-md-8">
				<div class="col-md-4 center" style="padding-top:20px;">
				  <?php
					if (empty($buku['image'])) {?>
					  <img src="images/docs/book.png" alt="" style="width:200px;height:250px; border-radius:10px;">
					<?php } else { ?>
						<img src="images/docs/<?php echo $buku['image']?>" alt="" style="width:200px;height:250px;border-radius:10px;">
					<?php }
					?>
				  <br>
					<center><?php echo showBadge($id);?></center>
				  <p>
					<?php
					// dapatkan GMD buku
					$gmd_id = $buku['gmd_id'];
					$query_gmd = "SELECT gmd_name FROM mst_gmd WHERE gmd_id = '$gmd_id'";
					$data_gmd = $dbs->query($query_gmd);
					foreach ($data_gmd as $gmd) {
					  echo $gmd['gmd_name'];
					}
					 ?>
				  </p>
				  <small>
					<center>
					  <table>
						<tr>
						  <td style="width:16%; text-align:center;">
							<a class="sharing" data-type="biblio" data-sosmed="facebook" data-id="<?php echo $id?>" href="http://www.facebook.com/sharer.php?u=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
							  <i class="ion-social-facebook"></i>
							  <br>
							  <sup><small><?php echo $buku['fb']?></small></sup>
							</a>
						  </td>
						  <td style="width:16%;text-align:center;">
							<a class="sharing" data-type="biblio" data-sosmed="twitter" data-id="<?php echo $id?>" href="http://twitter.com/share?url=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
							  <i class="ion-social-twitter" ></i>
							  <br>
							  <sup><small><?php echo $buku['tw']?></small></sup>
							</a>
						  </td>
						  <td style="width:16%;text-align:center;">
							<a class="sharing" data-type="biblio" data-sosmed="google" data-id="<?php echo $id?>" href="https://plus.google.com/share?url=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
							  <i class="ion-social-googleplus" ></i>
							  <br>
							  <sup><small><?php echo $buku['goo']?></small></sup>
							</a>
						  </td>
						  <td style="width:16%;text-align:center;">
							<a class="sharing" data-type="biblio" data-sosmed="linkedin" data-id="<?php echo $id?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
							  <i class="ion-social-linkedin-outline" ></i>
							  <br>
							  <sup><small><?php echo $buku['li']?></small></sup>
							</a>
						  </td>						
						  <td style="width:16%;text-align:center;" id="whatsapp">
							<a class="sharing" data-type="biblio" data-sosmed="whatsapp" data-id="<?php echo $id?>" href="whatsapp://send?text=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank" id="whatsapp">
							  <i class="ion-social-whatsapp-outline" ></i>
							  <br>
							  <sup><small><?php echo $buku['wa']?></small></sup>
							</a>
						  </td>
						</tr>
					  </table>
					</center>
				  </small>
				</div>
				<div class="col-md-8">
				  <h3><?php echo $buku['title']?></h3>
				  <p class="pagination">
					<small>
					  <?php echo e("Dipinjam") ?> : <?php echo $jml_pinjam ?> | &nbsp;
					  <?php echo e("Dibaca") ?> : <?php echo $jml_baca ?> | &nbsp;
					  <?php echo e("Dikunjungi") ?> : <?php echo $jml_pengunjung ?>
					</small>
				  </p>
				  <br>
				  <!-- Dapatkan data author -->
				  <p class="pagination">
					<small><?php echo e("Penulis") ?></small>
					<br>
					<small>
					  <?php
					  $query_author = "SELECT mst_author.author_id as author_id,
											  mst_author.author_name as author_name,
											  mst_author.authority_type as authority_type
											  FROM biblio_author
											  JOIN mst_author ON biblio_author.author_id = mst_author.author_id
											  WHERE biblio_author.biblio_id = '$id' ";
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
				  </p>
				</div>
				<div class="col-md-12">
				  <p style="font-style:italic;">
					<?php echo $buku['notes']; ?>
				  </p>
				</div>
				<div class="col-md-12">
				  <hr>
				  <h3><?php echo e("Ketersediaan") ?></h3>
				  <hr>
					<!-- Dapatkan data item -->
					<?php
					$q_item = "SELECT item.*,mst_location.location_name as location_name
									  FROM item
									  JOIN mst_location ON item.location_id = mst_location.location_id
									  WHERE biblio_id = '$id'";
					$d_item = $dbs->query($q_item);
					if (!empty($d_item)) { ?>
					  <p>
						<table>
						  <?php
							foreach ($d_item as $item) {
							  $i_code = $item['item_code'];
							  $item_status_id = $item['item_status_id'];
							  $item_status = $dbs->query("SELECT item_status_name FROM mst_item_status WHERE item_status_id = '$item_status_id'");
							  ?>
							  <tr>
								<td style="padding:5px; background-color:#999999; color:#fff;"><?php if (!empty($item['item_code'])) {echo $item['item_code'];}else{echo ' - ';} ?></td>
								<td style="padding:5px"><?php if(!empty($item['call_number'])){echo $item['call_number'];}else{echo " - ";}?></td>
								<td style="padding:5px; background-color:#999999; color:#fff;"><?php if(!empty($item['location_name'])){echo $item['location_name'];}else{echo "-";} ?></td>
								<td>
								  <!-- Cek status peminjaman -->
								  <?php
								  $loan_stat_q = $dbs->query("SELECT due_date FROM loan WHERE item_code = '$i_code' AND is_lent=1 AND is_return=0");
								  if ($loan_stat_q->num_rows > 0) {
									  $loan_stat_d = $loan_stat_q->fetch_row();
									  echo '<td style="padding:5px;color:red; width:40%;">'.e('Sedang dipinjam (Sampai dengan').' '.date('d M Y', strtotime($loan_stat_d[0])).')</td>'; //mfc
								  } elseif ($item_status->num_rows > 0) {
									  $itemnya = $item_status->fetch_row();
									  echo '<td style="padding:5px;color:red; width:40%;">'.e('Tersedia tapi tidak untuk dipinjamkan').$itemnya['item_status_name'].'</td>'; //mfc
								  } else {
									echo '<td style="padding:5px;color:green; width:40%;">'.e('Tersedia').'</td>';
								  }
								   ?>
								</td>
							  </tr>
							<?php }
						   ?>
						</table>
					  </p>
					<?php }
					?>
					<hr>
					<h3><?php echo e("Informasi Terperinci") ?></h3>
					<hr>
					<div class="col-md-12">
					  <dl class="">
						<dt><?php echo e("NO PANGGILAN") ?> <b>:</b></dt>
						<dd><?php if(!empty($buku['call_number'])){echo $buku['call_number'];}else{echo '-';} ?></dd><div class="clearfix"></div>
						<dt><?php echo e("PENERBIT") ?> <b>:</b></dt>
						<dd><?php
								  $pub_id = $buku['publisher_id'];
								  $loc_id = $buku['publish_place_id'];
								  $p_query = $dbs->query("SELECT publisher_name FROM mst_publisher WHERE publisher_id = '$pub_id'");
								  foreach ($p_query as $pub) {
									echo $pub['publisher_name'];
								  }
								  $l_query = $dbs->query("SELECT place_name FROM mst_place WHERE place_id = '$loc_id'");
								  foreach ($l_query as $loc) {
									echo ' : '.$loc['place_name'] .', '.$buku['publish_year'];
								  }
							  ?>
						</dd><div class="clearfix"></div>
						<dt><?php echo e("RINCIAN") ?> <b>:</b></dt>
						<dd><?php if(!empty($buku['collation'])){echo $buku['collation'];}else{echo " - ";}?></dd><div class="clearfix"></div>
						<dt><?php echo e("BAHASA") ?> <b>:</b></dt>
						<dd><?php
								  $lang_id = $buku['language_id'];
								  $lang_query = $dbs->query("SELECT language_name FROM mst_language WHERE language_id = '$lang_id'");
								  foreach ($lang_query as $lang) {
									if(!empty($lang['language_name'])){echo $lang['language_name'];}else{echo " - ";}
								  }
							   ?>
						</dd><div class="clearfix"></div>
						<dt>ISBN / ISSN <b>:</b></dt>
						<dd><?php if(!empty($buku['isbn_issn'])){echo $buku['isbn_issn'];}else{echo " - ";}?></dd><div class="clearfix"></div>
					  </dl>
					  <dl class="hid a" style="display:none;">
						<dt><?php echo e("KLASIFIKASI") ?> <b>:</b></dt>
						<dd><?php if(!empty($buku['classification'])){echo $buku['classification'];}else{echo " - ";} ?></dd><div class="clearfix"></div>
						<dt><?php echo e("JENIS KONTEN") ?> <b>:</b></dt>
						<dd><?php if(!empty($buku['content_type_id'])){echo $buku['content_type_id'];}else{echo " - ";}?></dd><div class="clearfix"></div>
						<dt><?php echo e("TIPE MEDIA") ?> <b>:</b></dt>
						<dd><?php if(!empty($buku['media_type_id'])){echo $buku['media_type_id'];}else{echo " - ";}?></dd><div class="clearfix"></div>
						<dt><?php echo e("TIPE BAWAAN") ?> <b>:</b></dt>
						<dd><?php if(!empty($buku['carrier_type_id'])){echo $buku['carrier_type_id'];}else{echo " - ";}?></dd><div class="clearfix"></div>
						<dt><?php echo e("EDISI") ?> <b>:</b></dt>
						<dd><?php if(!empty($buku['series_title'])){echo $buku['series_title'];}else{echo " - ";}?></dd><div class="clearfix"></div>
						<dt><?php echo e("JUDUL SERI") ?> <b>:</b></dt>
						<dd><?php if(!empty($buku['edition'])){echo $buku['edition'];}else{echo " - ";}?></dd><div class="clearfix"></div>
						<dt><?php echo e("SUBJEK") ?> <b>:</b></dt>
						<dd><?php
								  $subject_query = $dbs->query("SELECT mst_topic.topic as topic,mst_topic.topic_id as topic_id
																FROM biblio_topic
																LEFT JOIN mst_topic ON biblio_topic.topic_id = mst_topic.topic_id
																WHERE biblio_topic.biblio_id = '$id'");
								  $nos = 1;
								  foreach ($subject_query as $subject) {
									?>
									<a href="index.php?h=cari&kata=<?php echo addslashes($subject['topic']) ?>">
									  <?php
										  if ($nos > 1) {
											echo ",  ";
										  }
										  echo addslashes($subject['topic'])
									  ?>
									</a>
							  <?php $nos++;}
							   ?>
						</dd><div class="clearfix"></div>
						<dt><?php echo e("INFO LEBIH DETIL") ?></dt>
						<dd>: <?php if(!empty($buku['spec_detail_info'])){echo $buku['spec_detail_info'];}else{echo " - ";}?></dd><div class="clearfix"></div>
					  </dl>
					  <center>
						<a href="javascript:void(0)" class="ab smw">
						  <?php echo e('Selengkapnya');?>
						</a>
						<a href="javascript:void(0)" class="ab smw" style="display:none;">
						  <?php echo e('Sembunyikan');?>
						</a>
					  </center>
					</div>
					<div class="col-md-12">
					  <hr>
					  <h3><?php echo e("Versi lain / relasi") ?></h3>
					  <hr>
					  <table>
						<?php
						$ov_query = $dbs->query("SELECT biblio.biblio_id as ov_id,
											biblio.title as ov_title
											FROM biblio_relation
											LEFT JOIN biblio ON biblio_relation.rel_biblio_id = biblio.biblio_id
											WHERE biblio_relation.biblio_id = '$id'");
						$jml_ov = $ov_query->fetch_row();
						if ($jml_ov < 1) {?>
								<tr>
								  <td style="color:red;"><?php echo e("Tidak ada versi lain yang terkait") ?>.</td>
								</tr>
						<?php } else {
								foreach ($ov_query as $ov) { ?>
								  <tr>
									<td style="padding:10px;">
									  <a href="index.php?h=show_detail&id=<?php echo $ov['ov_id']?>"><?php echo $ov['ov_title'] ?></a>
									</td>
								  </tr>
								<?php }
							  }
						 ?>
					  </table>
					</div>
					<div class="col-md-12">
					  <hr>
						<h3><?php echo e("Lampiran") ?></h3>
					  <hr>
					  <?php
					  $att = $dbs->query("SELECT files.mime_type as mime_type, files.file_title as file_title,
												files.file_id as file_id, files.file_desc as file_desc,
												files.file_url as file_url, biblio_attachment.biblio_id as biblio_id,
                                                files.file_url as file_dir, files.file_name as file_name
												FROM biblio_attachment
												LEFT JOIN files ON biblio_attachment.file_id = files.file_id
												WHERE biblio_attachment.biblio_id = '$id'");
					  $jml_att = $att->fetch_row();
					  if (empty($jml_att)) {
						echo '<span style="color:red">'.e("Tidak ada lampiran").'</span>';
					  }
					  $_output = '';
					  $_output .= '<ul class="attachList">';
					  foreach ($att as $attachment_d) {
						if ($attachment_d['mime_type'] == 'application/pdf') {
						  $_output .= '<li class="attachment-pdf" style="" itemscope itemtype="http://schema.org/MediaObject"><a itemprop="name" property="name" class="openPopUp" title="'.$attachment_d['file_title'].'" href="./index.php?p=fstream&fid='.$attachment_d['file_id'].'&bid='.$attachment_d['biblio_id'].'" width="780" height="520">'.$attachment_d['file_title'].'</a>';
						  $_output .= '<div class="attachment-desc" itemprop="description" property="description">'.$attachment_d['file_desc'].'</div>';
						  if (trim($attachment_d['file_url']) != '') { $_output .= '<div><a href="'.trim($attachment_d['file_url']).'" itemprop="url" property="url" title="Other Resource related to this book" target="_blank">Other Resource Link</a></div>'; }
						  $_output .= '</li>';
						} else if (preg_match('@(video)/.+@i', $attachment_d['mime_type'])) {
						  $_output .= '<li class="attachment-audio-video" itemprop="video" property="video" itemscope itemtype="http://schema.org/VideoObject" style="">'
							.'<a itemprop="name" property="name" class="openPopUp" title="'.$attachment_d['file_title'].'" href="./index.php?p=multimediastream&fid='.$attachment_d['file_id'].'&bid='.$attachment_d['biblio_id'].'" width="640" height="480">'.$attachment_d['file_title'].'</a>';
						  $_output .= '<div class="attachment-desc" itemprop="description" property="description">'.$attachment_d['file_desc'].'</div>';
						  if (trim($attachment_d['file_url']) != '') { $_output .= '<div><a href="'.trim($attachment_d['file_url']).'" itemprop="url" property="url" title="Other Resource Link" target="_blank">Other Resource Link</a></div>'; }
						  $_output .= '</li>';
						} else if (preg_match('@(audio)/.+@i', $attachment_d['mime_type'])) {
						  $_output .= '<li class="attachment-audio-audio" itemprop="audio" property="audio" itemscope itemtype="http://schema.org/AudioObject" style="">'
							.'<a itemprop="name" property="name" class="openPopUp" title="'.$attachment_d['file_title'].'" href="./index.php?p=multimediastream&fid='.$attachment_d['file_id'].'&bid='.$attachment_d['biblio_id'].'" width="640" height="480">'.$attachment_d['file_title'].'</a>';
						  $_output .= '<div class="attachment-desc" itemprop="description" property="description">'.$attachment_d['file_desc'].'</div>';
						  if (trim($attachment_d['file_url']) != '') { $_output .= '<div><a href="'.trim($attachment_d['file_url']).'" itemprop="url" property="url" title="Other Resource Link" target="_blank">Other Resource Link</a></div>'; }
						  $_output .= '</li>';
						} else if ($attachment_d['mime_type'] == 'text/uri-list') {
						  $_output .= '<li class="attachment-url-list" style="" itemscope itemtype="http://schema.org/MediaObject"><a itemprop="name" property="name"  href="'.trim($attachment_d['file_url']).'" title="Click to open link" target="_blank">'.$attachment_d['file_title'].'</a><div class="attachment-desc">'.$attachment_d['file_desc'].'</div></li>';
						} else if (preg_match('@(image)/.+@i', $attachment_d['mime_type'])) {
						  $file_loc = REPOBS.'/'.$attachment_d['file_dir'].'/'.$attachment_d['file_name'];
						  $imgsize = GetImageSize($file_loc);
						  $imgwidth = $imgsize[0] + 16;
						  if ($imgwidth > 600) {
							$imgwidth = 600;
						  }
						  $imgheight = $imgsize[1] + 16;
						  if ($imgheight > 400) {
							$imgheight = 400;
						  }
						  $_output .= '<li class="attachment-image" style="" itemprop="image" itemscope itemtype="http://schema.org/ImageObject"><a itemprop="name" property="name" class="openPopUp" title="'.$attachment_d['file_title'].'" href="index.php?p=fstream&fid='.$attachment_d['file_id'].'&bid='.$attachment_d['biblio_id'].'" width="'.$imgwidth.'" height="'.$imgheight.'">'.$attachment_d['file_title'].'</a>';
						  if (trim($attachment_d['file_url']) != '') { $_output .= ' [<a href="'.trim($attachment_d['file_url']).'" itemprop="url" property="url" title="Other Resource related to this file" target="_blank" style="font-size: 90%;">Other Resource Link</a>]'; }
						  $_output .= '<div class="attachment-desc" itemprop="description" property="description">'.$attachment_d['file_desc'].'</div></li>';
						} else {
						  $_output .= '<li class="attachment-image" style="" itemscope itemtype="http://schema.org/MediaObject"><a itemprop="name" property="name" title="Click To View File" href="index.php?p=fstream&fid='.$attachment_d['file_id'].'&bid='.$attachment_d['biblio_id'].'" target="_blank">'.$attachment_d['file_title'].'</a>';
						  if (trim($attachment_d['file_url']) != '') { $_output .= ' [<a href="'.trim($attachment_d['file_url']).'" itemprop="url" property="url" title="Other Resource related to this file" target="_blank" style="font-size: 90%;">Other Resource Link</a>]'; }
						  $_output .= '<div class="attachment-desc" itemprop="description" property="description">'.$attachment_d['file_desc'].'</div></li>';
						}
					  }
					  $_output .= '</ul>';
					  echo $_output;
					   ?>
					</div>
				</div>
				<div class="comment-list">
					<div class="row">
						<div class="col-md-12" style="text-align:left;">
						  <hr>
							<div class="title">
							  <h2><?php echo e("Kolom Komentar") ?></h2>
							  <?php
							   $q_comment = $dbs->query("SELECT * FROM comments WHERE biblio_id = '$id' and status='published'");
							   $jml_comment = $q_comment->fetch_row();
							   $jml = $q_comment->num_rows;
							   if ($jml_comment > 1) {
								 echo "<h4>".$jml.' '.e("Komentar")."</h4>";?>
								 <section style="max-height:500px;overflow:auto;padding-top:0px;">
								   <?php
								   foreach ($q_comment as $comment) { ?>
									 <div class="comment" style="padding-left:45px;text-align:left;">
										 <h4><?php echo $comment['name'] ?></h4>
										 <p class="date-posted" style="text-align:left;"><?php echo $comment['last_update'] ?></p>
										 <p><?php echo $comment['comment'] ?></p>
									 </div>
								   <?php }
									?>
								 </section>
							   <?php }
							   ?>
							</div>
						</div>
					</div>
				</div>
				<div id="c_contact">
					<div class="row">
						<div class="col-md-12">
							<form  id='i-recaptcha' class="" method="post" action="index.php?h=komentar">
							  <section class="contact-form">
								<input type="hidden" name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
								<input type="hidden" name="id" value="<?php echo $id; ?>">
								<input type="hidden" name="token" value="<?php echo utility::set_token();?>">
								  <div class="input-field col-sm-12">
									  <div class="form-group">
										  <input id="form-name" name="name" type="text" placeholder="<?php echo e("Nama")?> *" required="required"
											  data-error="<?php echo e("Nama tidak boleh kosong") ?>.">
										  <div class="help-block with-errors"></div>
									  </div>
								  </div>
								  <div class="input-field col-sm-12">
									  <div class="form-group">
										  <input id="form-email" name="email" type="email" placeholder="<?php echo e("Alamat Email")?> *" required="required"
											  data-error="<?php echo e("Email tidak boleh kosong")?>.">
										  <div class="help-block with-errors"></div>
									  </div>
								  </div>
								  <div class="input-field col-sm-12">
									  <div class="form-group">
										  <textarea id="form-textarea" name="Message" id="" cols="30" rows="5" placeholder="<?php echo e("Ketik komentar disini")?> *"
											  required="required" data-error="<?php echo e("Komentar tidak boleh kosong")?>."></textarea>
										  <div class="help-block with-errors"></div>
									  </div>
								  </div>
<!-- recaptcha -->

<?php
if ($sysconf['captcha']['smc']['enable']) {
	if ($sysconf['captcha']['smc']['type'] == "recaptcha") {
		require_once LIB.$sysconf['captcha']['smc']['folder'].'/'.$sysconf['captcha']['smc']['incfile'];
		$publickey = $sysconf['captcha']['smc']['publickey'];
		echo '<div class="input-field col-sm-12">';
		echo recaptcha_get_html($publickey);
		echo '</div>';
	}
}
?>

<?php if( $sysconf['captcha']['type'] == 'captcha' ){ /* //?> 
								  <div class="input-field col-sm-12">
									  <div class="form-group">
									  	<img src="lib/captcha.php?<?php echo time();?>" />
									  </div>								 
								  </div>
	
								  <div class="input-field col-sm-12">
									  <div class="form-group">
									  		<input id="form-captcha" name="captcha" type="text" placeholder="<?php echo e("Captcha")?>" required="required"
											  data-error="<?php echo e("Captcha tidak boleh kosong")?>.">
										  <div class="help-block with-errors"></div>
									  </div>
								  </div>
<?php } */ } ?>								 
	
								  <div class="col-sm-12">
									<?php if( $sysconf['captcha']['type'] == 'captcha' ){ ?> 
									  <input type="submit" value="<?php echo e("Kirim Komentar")?>">
									<?php } else { ?>
									  <button class="g-recaptcha" data-sitekey="<?php echo e($sysconf['recaptcha']['site']);?>" data-callback="onSubmit">
										Submit
									  </button>
									<?php } ?>
									  <div class="messages"></div>
								  </div>

							  </section>
							</form>
						</div>
					</div>
				</div>
			  </div>
<!-- ini terbaru -->              
			  <div class="col-md-4 sidebar">
				<div class="item">
					<h2><?php echo e("Terbaru") ?></h2>
					<hr>
					<?php
					  $tquery = "SELECT biblio_id, title, image FROM biblio WHERE image !='' ORDER BY biblio_id DESC LIMIT 5";
					  $etquery = $dbs->query($tquery);
					  foreach ($etquery as $value) { ?>
						<div class="row">
							<a href="index.php?h=show_detail&id=<?php echo $value['biblio_id']?>">
								<div class="col-xs-4 image-fw">
									<img src="images/docs/<?php echo $value['image']?>" alt="" style="height: 100px;object-fit:cover;">
								</div>
								<div class="col-xs-8">
									<h3 class="lp-title"><?php echo substr($value['title'],0,50).'...'; ?></h3>
								</div>
							</a>
						</div>
					  <?php }
					 ?>
				</div>
			  </div>
			</div>
		  </div>
		</section>

	  <?php } ?>
	<?php } elseif ($_GET['h'] == 'komentar') {

if ($sysconf['captcha']['smc']['enable']) {
	if ($sysconf['captcha']['smc']['type'] == 'recaptcha') {
		require_once LIB.$sysconf['captcha']['smc']['folder'].'/'.$sysconf['captcha']['smc']['incfile'];
		$privatekey = $sysconf['captcha']['smc']['privatekey'];
		$resp = recaptcha_check_answer ($privatekey,
							  $_SERVER["REMOTE_ADDR"],
							  $_POST["g-recaptcha-response"]);

		$_captcha_valid = $resp->is_valid;

	} elseif ($sysconf['captcha']['smc']['type'] == 'others') {
		# other captchas here

		if($sysconf['captcha']['enable'] == true ) {
		
			$_captcha_valid = $_SESSION[$sysconf['captcha']['variable']] == $_POST['captcha'] ? true : false;
			
		} 
	}
}		
        if( $_captcha_valid ) {
	
			require_once LIB.'custom.inc.php';

			$id       = utility::filter_number($_POST['id']);
			$nama     = utility::filter_string($_POST['name']);
			$email    = strtolower(utility::filter_email($_POST['email']));
			$komentar = utility::filter_string($_POST['Message']);
			$tgl      = date('Y-m-d h:i:s');
			$ip		  = get_client_ip();
			
			if( not_email_spam($email) ){
				$q_insert = $dbs->query("INSERT INTO comments VALUES (NULL,'$id','$nama','$email','$komentar','$tgl','$tgl','$ip', 'unpublish')");
				$last_id  = $dbs->insert_id;
				$q_notif  = $dbs->query("INSERT INTO `notif_comments` (`id`, `comment_id`, `biblio_id`, `tgl_comment`) VALUES (NULL, '$last_id', '$id', '$tgl')");
				
				$_nmail = notif_comment_to_email($nama, $email, $komentar, $tgl, $sysconf['mail']);

				if($sysconf['mail']['smtpdebug'] ) var_dump($_nmail);
			}
			
			echo "<script>alert('Komentar berhasil dikirim'); window.history.back(); </script>";
	  }

      else echo "<script>alert('invalid input or captcha'); window.open(document.referrer,'_self');</script>";
//       {
//           $_a = (string) utility::cek_token($_POST['token'])." xxx ";
//           $_a .=  (string) $_captcha_valid;
//           var_dump($_POST);
//           echo "<script>alert('".$_a."'); window.open(document.referrer,'_self');</script>";
//       }
	  
	  // halaman publikasi lokal universitas
	} elseif ($_GET['h'] == 'show_detail_plu') {
	  $id = $_GET['id'];
	  $tgl = date('Y-m-d');
	  $query_pengunjung = "INSERT INTO pengunjung VALUES(NULL,'$id',".date('Y').",'$tgl','1')";
	  $e_query_pengunjung = $dbs->query($query_pengunjung);

	  // dapatkan jumlah pengunjung
	  $query_jml_pengunjung = "SELECT id_pengunjung FROM pengunjung WHERE biblio_id = '$id' AND type = '1'";
	  $data_jml_pengunjung = $dbs->query($query_jml_pengunjung);
		$jml_pengunjung = $data_jml_pengunjung->num_rows;

		// update jumlah visit & baca pada table korupsi, dpnt know why they using table baca and peng
		$query_visit = "UPDATE korupsi set visit = ".$jml_pengunjung."  WHERE biblio_id = '$id'";
		$dbs->query($query_visit);


	  // dapatkan data buku
	  $query_buku = "SELECT *,
					(SELECT COUNT(id_share) FROM sharing WHERE content_id = '$id' AND tipe = 'lokal' AND sosmed = 'facebook') AS fb,
					(SELECT COUNT(id_share) FROM sharing WHERE content_id = '$id' AND tipe = 'lokal' AND sosmed = 'twitter') AS tw,
					(SELECT COUNT(id_share) FROM sharing WHERE content_id = '$id' AND tipe = 'lokal' AND sosmed = 'google') AS goo,
					(SELECT COUNT(id_share) FROM sharing WHERE content_id = '$id' AND tipe = 'lokal' AND sosmed = 'linkedin') AS li,
					(SELECT COUNT(id_share) FROM sharing WHERE content_id = '$id' AND tipe = 'lokal' AND sosmed = 'whatsapp') AS wa
					FROM korupsi WHERE biblio_id = '$id'";
	  $data_buku = $dbs->query($query_buku);
	  foreach ($data_buku as $buku) {
	  ?>
	  <header id="sp3" class="center" data-overlay="8">
		<div class="parallax" id="12">
		  <img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/bg0.jpg" alt="" class="" style="display:none;width:100%;height:100%;object-fit: cover;">
		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
			  <form method="GET" action="index.php?">
				  <input type="hidden" name="h" value="cari_lokal">
				  <input name="kata" style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; color:white;" type="text" placeholder="" class="col-md-12 elastik4 ac">
				  <input type="submit" name="" value="<?php echo e("Cari")?>" style="background-color:white;border-color:white;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
			  </form>
			</div>
		</div>
	  </header>
	  <section>
		<div class="container">
		  <div class="row">
			<div class="col-md-8">
			  <div class="col-md-4 center" style="padding-top:20px;">
				<?php
				  if (empty($buku['image'])) {?>
					<img src="images/docs/book.png" alt="" style="width:200px;height:250px; border-radius:10px;">
				  <?php } else { ?>
					  <img src="images/docs/<?php echo $buku['image']?>" alt="" style="width:200px;height:250px;border-radius:10px;">
				  <?php }
				  ?>
				<br>
				<p>
				  <?php
				  // dapatkan GMD buku
				  $gmd_id = $buku['gmd_id'];
				  $query_gmd = "SELECT gmd_name FROM mst_gmd WHERE gmd_id = '$gmd_id'";
				  $data_gmd = $dbs->query($query_gmd);
				  foreach ($data_gmd as $gmd) {
					echo $gmd['gmd_name'];
				  }
				   ?>
				</p>
				<small>
				  <center>
					<table>
					  <tr>
						<td style="width:16%; text-align:center;">
						  <a class="sharing" data-type="lokal" data-sosmed="facebook" data-id="<?php echo $id?>" href="http://www.facebook.com/sharer.php?u=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
							<i class="ion-social-facebook"></i>
							<br>
							<sup><small><?php echo $buku['fb']?></small></sup>
						  </a>
						</td>
						<td style="width:16%;text-align:center;">
						  <a class="sharing" data-type="lokal" data-sosmed="twitter" data-id="<?php echo $id?>" href="http://twitter.com/share?url=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
							<i class="ion-social-twitter" ></i>
							<br>
							<sup><small><?php echo $buku['tw']?></small></sup>
						  </a>
						</td>
						<td style="width:16%;text-align:center;">
						  <a class="sharing" data-type="lokal" data-sosmed="google" data-id="<?php echo $id?>" href="https://plus.google.com/share?url=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
							<i class="ion-social-googleplus" ></i>
							<br>
							<sup><small><?php echo $buku['goo']?></small></sup>
						  </a>
						</td>
						<td style="width:16%;text-align:center;">
						  <a class="sharing" data-type="lokal" data-sosmed="linkedin" data-id="<?php echo $id?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank">
							<i class="ion-social-linkedin-outline" ></i>
							<br>
							<sup><small><?php echo $buku['li']?></small></sup>
						  </a>
						</td>
						<td style="width:16%;text-align:center;" id="whatsapp">
						  <a class="sharing" data-type="lokal" data-sosmed="whatsapp" data-id="<?php echo $id?>" href="whatsapp://send?text=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank" id="whatsapp">
							<i class="ion-social-whatsapp-outline" ></i>
							<br>
							<sup><small><?php echo $buku['wa']?></small></sup>
						  </a>
						</td>
					  </tr>
					</table>
				  </center>
				</small>
			  </div>
			  <div class="col-md-8">
				<h3><?php echo $buku['title']?></h3>
				<p class="pagination">
				  <small>
					<?php echo e("Dikunjungi") ?> : <?php echo $jml_pengunjung; ?>
				  </small>
				</p>
				<br>
				<!-- Dapatkan data author -->
				<p class="pagination">
				  <small><?php echo e("Penulis") ?></small>
				  <br>
				  <small>
					<?php
					$query_author = "SELECT mst_author.author_id as author_id,
											mst_author.author_name as author_name,
											mst_author.authority_type as authority_type
											FROM korupsi_author
											JOIN mst_author ON korupsi_author.author_id = mst_author.author_id
											WHERE korupsi_author.biblio_id = '$id' ";
					$e_query_quthor = $dbs->query($query_author);
					$no_author = 1;
					foreach ($e_query_quthor as $author) {
					  if ($no_author > 1 ) {
						echo ' <br> ';
					  }
					  ?>
					  <a href="index.php?h=cari_lokal&kata=<?php echo $author['author_name']?>"><?php echo $author['author_name']?></a>
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
				</p>
			  </div>
			  <div class="col-md-12">
				<p style="font-style:italic;">
				  <?php echo $buku['notes']; ?>
				</p>
			  </div>
			  <div class="col-md-12">
				  <hr>
				  <h3><?php echo e("Informasi Terperinci") ?></h3>
				  <hr>
				  <div class="col-md-12">
					<dl class="">
					  <dt><?php echo e("NO PANGGILAN") ?> <b>:</b></dt>
					  <dd><?php if(!empty($buku['call_number'])){echo $buku['call_number'];}else{echo '-';} ?></dd><div class="clearfix"></div>
					  <dt><?php echo e("PENERBIT") ?> <b>:</b></dt>
					  <dd><?php
								$pub_id = $buku['publisher_id'];
								$loc_id = $buku['publish_place_id'];
								$p_query = $dbs->query("SELECT publisher_name FROM mst_publisher WHERE publisher_id = '$pub_id'");
								foreach ($p_query as $pub) {
								  echo $pub['publisher_name'];
								}
								$l_query = $dbs->query("SELECT place_name FROM mst_place WHERE place_id = '$loc_id'");
								foreach ($l_query as $loc) {
								  echo $loc['place_name'] .', '.$buku['publish_year'];
								}
							?>
					  </dd><div class="clearfix"></div>
					  <dt><?php echo e("RINCIAN") ?> <b>:</b></dt>
					  <dd><?php if(!empty($buku['collation'])){echo $buku['collation'];}else{echo " - ";}?></dd><div class="clearfix"></div>
					  <dt><?php echo e("BAHASA") ?> <b>:</b></dt>
					  <dd><?php
								$lang_id = $buku['language_id'];
								$lang_query = $dbs->query("SELECT language_name FROM mst_language WHERE language_id = '$lang_id'");
								foreach ($lang_query as $lang) {
								  if(!empty($lang['language_name'])){echo $lang['language_name'];}else{echo " - ";}
								}
							 ?>
					  </dd><div class="clearfix"></div>
					  <dt>ISBN / ISSN <b>:</b></dt>
					  <dd><?php if(!empty($buku['isbn_issn'])){echo $buku['isbn_issn'];}else{echo " - ";}?></dd><div class="clearfix"></div>
					</dl>
					<dl class="hid a" style="display:none;">
					  <dt><?php echo e("KLASIFIKASI") ?> <b>:</b></dt>
					  <dd><?php if(!empty($buku['classification'])){echo $buku['classification'];}else{echo " - ";} ?></dd><div class="clearfix"></div>
					  <dt><?php echo e("JENIS KONTEN") ?> <b>:</b></dt>
					  <dd><?php if(!empty($buku['content_type_id'])){echo $buku['content_type_id'];}else{echo " - ";}?></dd><div class="clearfix"></div>
					  <dt><?php echo e("TIPE MEDIA") ?> <b>:</b></dt>
					  <dd><?php if(!empty($buku['media_type_id'])){echo $buku['media_type_id'];}else{echo " - ";}?></dd><div class="clearfix"></div>
					  <dt><?php echo e("TIPE BAWAAN") ?> <b>:</b></dt>
					  <dd><?php if(!empty($buku['carrier_type_id'])){echo $buku['carrier_type_id'];}else{echo " - ";}?></dd><div class="clearfix"></div>
					  <dt><?php echo e("EDISI") ?> <b>:</b></dt>
					  <dd><?php if(!empty($buku['series_title'])){echo $buku['series_title'];}else{echo " - ";}?></dd><div class="clearfix"></div>
					  <dt><?php echo e("JUDUL SERI") ?> <b>:</b></dt>
					  <dd><?php if(!empty($buku['edition'])){echo $buku['edition'];}else{echo " - ";}?></dd><div class="clearfix"></div>
					  <dt><?php echo e("SUBJEK") ?> <b>:</b></dt>
					  <dd><?php
								$subject_query = $dbs->query("SELECT mst_topic.topic as topic,mst_topic.topic_id as topic_id
															  FROM korupsi_topic
															  LEFT JOIN mst_topic ON korupsi_topic.topic_id = mst_topic.topic_id
															  WHERE korupsi_topic.biblio_id = '$id'");
								$nos = 1;
								foreach ($subject_query as $subject) {
								  ?>
								  <a href="index.php?h=cari_lokal&kata=<?php echo addslashes($subject['topic']) ?>">
									<?php
										if ($nos > 1) {
										  echo ",  ";
										}
										echo addslashes($subject['topic'])
									?>
								  </a>
							<?php $nos++;}
							 ?>
					  </dd><div class="clearfix"></div>
					  <dt><?php echo e("INFO LEBIH DETIL") ?></dt>
					  <dd>: <?php if(!empty($buku['spec_detail_info'])){echo $buku['spec_detail_info'];}else{echo " - ";}?></dd><div class="clearfix"></div>
					</dl>
					<center>
					  <a href="javascript:void(0)" class="ab smw">
						<?php echo e('Selengkapnya');?>
					  </a>
					  <a href="javascript:void(0)" class="ab smw" style="display:none;">
						<?php echo e('Sembunyikan');?>
					  </a>
					</center>
				  </div>
				  <div class="col-md-12">
					<hr>
					  <h3><?php echo e("Lampiran") ?></h3>
					<hr>
					<?php
					$att = $dbs->query("SELECT files.mime_type as mime_type,
											  files.file_title as file_title,
											  files.file_id as file_id,
											  files.file_desc as file_desc,
											  files.file_url as file_url,
											  korupsi_attachment.biblio_id as biblio_id
											  FROM korupsi_attachment
											  LEFT JOIN files ON korupsi_attachment.file_id = files.file_id
											  WHERE korupsi_attachment.biblio_id = '$id'");
					$jml_att = $att->fetch_row();
					if (empty($jml_att)) {
					  echo '<span style="color:red">'.e("Tidak ada lampiran").'</span>';
					}
					$_output = '';
					$_output .= '<ul class="attachList">';
					foreach ($att as $attachment_d) {
					  if ($attachment_d['mime_type'] == 'application/pdf') {
						$_output .= '<li class="attachment-pdf" style="" itemscope itemtype="http://schema.org/MediaObject"><a itemprop="name" property="name" class="openPopUp" title="'.$attachment_d['file_title'].'" href="./index.php?p=fstream&fid='.$attachment_d['file_id'].'&bid='.$attachment_d['biblio_id'].'" width="780" height="520">'.$attachment_d['file_title'].'</a>';
						$_output .= '<div class="attachment-desc" itemprop="description" property="description">'.$attachment_d['file_desc'].'</div>';
						if (trim($attachment_d['file_url']) != '') { $_output .= '<div><a href="'.trim($attachment_d['file_url']).'" itemprop="url" property="url" title="Other Resource related to this book" target="_blank">Other Resource Link</a></div>'; }
						$_output .= '</li>';
					  } else if (preg_match('@(video)/.+@i', $attachment_d['mime_type'])) {
						$_output .= '<li class="attachment-audio-video" itemprop="video" property="video" itemscope itemtype="http://schema.org/VideoObject" style="">'
						  .'<a itemprop="name" property="name" class="openPopUp" title="'.$attachment_d['file_title'].'" href="./index.php?p=multimediastream&fid='.$attachment_d['file_id'].'&bid='.$attachment_d['biblio_id'].'" width="640" height="480">'.$attachment_d['file_title'].'</a>';
						$_output .= '<div class="attachment-desc" itemprop="description" property="description">'.$attachment_d['file_desc'].'</div>';
						if (trim($attachment_d['file_url']) != '') { $_output .= '<div><a href="'.trim($attachment_d['file_url']).'" itemprop="url" property="url" title="Other Resource Link" target="_blank">Other Resource Link</a></div>'; }
						$_output .= '</li>';
					  } else if (preg_match('@(audio)/.+@i', $attachment_d['mime_type'])) {
						$_output .= '<li class="attachment-audio-audio" itemprop="audio" property="audio" itemscope itemtype="http://schema.org/AudioObject" style="">'
						  .'<a itemprop="name" property="name" class="openPopUp" title="'.$attachment_d['file_title'].'" href="./index.php?p=multimediastream&fid='.$attachment_d['file_id'].'&bid='.$attachment_d['biblio_id'].'" width="640" height="480">'.$attachment_d['file_title'].'</a>';
						$_output .= '<div class="attachment-desc" itemprop="description" property="description">'.$attachment_d['file_desc'].'</div>';
						if (trim($attachment_d['file_url']) != '') { $_output .= '<div><a href="'.trim($attachment_d['file_url']).'" itemprop="url" property="url" title="Other Resource Link" target="_blank">Other Resource Link</a></div>'; }
						$_output .= '</li>';
					  } else if ($attachment_d['mime_type'] == 'text/uri-list') {
						$_output .= '<li class="attachment-url-list" style="" itemscope itemtype="http://schema.org/MediaObject"><a itemprop="name" property="name"  href="'.trim($attachment_d['file_url']).'" title="Click to open link" target="_blank">'.$attachment_d['file_title'].'</a><div class="attachment-desc">'.$attachment_d['file_desc'].'</div></li>';
					  } else if (preg_match('@(image)/.+@i', $attachment_d['mime_type'])) {
						$file_loc = REPOBS.'/'.$attachment_d['file_dir'].'/'.$attachment_d['file_name'];
						$imgsize = GetImageSize($file_loc);
						$imgwidth = $imgsize[0] + 16;
						if ($imgwidth > 600) {
						  $imgwidth = 600;
						}
						$imgheight = $imgsize[1] + 16;
						if ($imgheight > 400) {
						  $imgheight = 400;
						}
						$_output .= '<li class="attachment-image" style="" itemprop="image" itemscope itemtype="http://schema.org/ImageObject"><a itemprop="name" property="name" class="openPopUp" title="'.$attachment_d['file_title'].'" href="index.php?p=fstream&fid='.$attachment_d['file_id'].'&bid='.$attachment_d['biblio_id'].'" width="'.$imgwidth.'" height="'.$imgheight.'">'.$attachment_d['file_title'].'</a>';
						if (trim($attachment_d['file_url']) != '') { $_output .= ' [<a href="'.trim($attachment_d['file_url']).'" itemprop="url" property="url" title="Other Resource related to this file" target="_blank" style="font-size: 90%;">Other Resource Link</a>]'; }
						$_output .= '<div class="attachment-desc" itemprop="description" property="description">'.$attachment_d['file_desc'].'</div></li>';
					  } else {
						$_output .= '<li class="attachment-image" style="" itemscope itemtype="http://schema.org/MediaObject"><a itemprop="name" property="name" title="Click To View File" href="index.php?p=fstream&fid='.$attachment_d['file_id'].'&bid='.$attachment_d['biblio_id'].'" target="_blank">'.$attachment_d['file_title'].'</a>';
						if (trim($attachment_d['file_url']) != '') { $_output .= ' [<a href="'.trim($attachment_d['file_url']).'" itemprop="url" property="url" title="Other Resource related to this file" target="_blank" style="font-size: 90%;">Other Resource Link</a>]'; }
						$_output .= '<div class="attachment-desc" itemprop="description" property="description">'.$attachment_d['file_desc'].'</div></li>';
					  }
					}
					$_output .= '</ul>';
					echo $_output;
					 ?>
				  </div>
				  <div class="comment-list">
					  <div class="row">
						  <div class="col-md-12" style="text-align:left;">
							<hr>
							  <div class="title">
								<h2><?php echo e("Kolom Komentar") ?></h2>
								<?php
								 $q_comment = $dbs->query("SELECT * FROM comments WHERE biblio_id = '$id'");
								 $jml_comment = $q_comment->fetch_row();
								 $jml = $q_comment->num_rows;
								 if ($jml_comment > 1) {
								   echo "<h4>".$jml.' '.e("Komentar")."</h4>";?>
								   <section style="max-height:500px;overflow:auto;padding-top:0px;">
									 <?php
									 foreach ($q_comment as $comment) { ?>
									   <div class="comment" style="padding-left:45px;text-align:left;">
										   <h4><?php echo $comment['name'] ?></h4>
										   <p class="date-posted" style="text-align:left;"><?php echo $comment['last_update'] ?></p>
										   <p><?php echo $comment['comment'] ?></p>
									   </div>
									 <?php }
									  ?>
								   </section>
								 <?php }
								 ?>
							  </div>
						  </div>
					  </div>
				  </div>
				  <div id="c_contact">
					  <div class="row">
						  <div class="col-md-12">
							  <form class="" method="post" action="index.php?h=komentar">
								<section class="contact-form">
								  <input type="hidden" name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
								  <input type="hidden" name="id" value="<?php echo $id; ?>">
									<div class="input-field col-sm-12">
										<div class="form-group">
											<input id="form-name" name="name" type="text" placeholder="<?php echo e("Nama")?> *" required="required"
												data-error="<?php echo e("Nama tidak boleh kosong")?>.">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="input-field col-sm-12">
										<div class="form-group">
											<input id="form-email" name="email" type="email" placeholder="<?php echo e("Alamat Email")?> *" required="required"
												data-error="<?php echo e("Email tidak boleh kosong")?>.">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="input-field col-sm-12">
										<div class="form-group">
											<textarea id="form-textarea" name="Message" id="" cols="30" rows="5" placeholder="<?php echo e("Ketik komentar disini")?> *"
												required="required" data-error="<?php echo e("Komentar tidak boleh kosong")?>."></textarea>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="col-sm-12">
										<input type="submit" value="<?php echo e("Kirim Komentar")?>">
										<div class="messages"></div>
									</div>
								</section>
							  </form>
						  </div>
					  </div>
				  </div>
			  </div>
			</div>

			<div class="col-md-4 sidebar">
			  <div class="item">
				  <h2><?php echo e("Terbaru") ?></h2>
				  <hr>
				  <?php
					$tquery = "SELECT biblio_id, title, image FROM biblio WHERE image !='' ORDER BY biblio_id DESC LIMIT 5";
					$etquery = $dbs->query($tquery);
					foreach ($etquery as $value) { ?>
					  <div class="row">
						  <a href="index.php?h=show_detail&id=<?php echo $value['biblio_id']?>">
							  <div class="col-xs-4 image-fw">
								  <img src="images/docs/<?php echo $value['image']?>" alt="" style="height: 100px;object-fit:cover;">
							  </div>
							  <div class="col-xs-8">
								  <h3 class="lp-title"><?php echo substr($value['title'],0,50).'...'; ?></h3>
							  </div>
						  </a>
					  </div>
					<?php }
				   ?>
			  </div>
			</div>
		  </div>
		</div>
	  </section>
	<?php } ?>

  <?php } elseif ($_GET['h'] == 'cari') {
	$k_ori = utility::filter_string($_GET['kata']);
	$kata = addslashes(utility::filter_string($_GET['kata']));
	$query = "SELECT biblio.biblio_id as bid,
			  biblio.title as title,
			  biblio.call_number as cm,
			  biblio.image as img,
			  mst_author.author_id as aid,
			  mst_author.author_name as ana,
			  mst_publisher.publisher_id as pid,
			  mst_publisher.publisher_name as pna,
			  mst_topic.topic_id as tid,
			  mst_topic.topic as topic,

			  MATCH (biblio.title, biblio.call_number) AGAINST ('".$kata."' IN NATURAL LANGUAGE MODE) as score

			  FROM biblio
			  LEFT JOIN biblio_author ON biblio.biblio_id = biblio_author.biblio_id
			  LEFT JOIN mst_author ON biblio_author.author_id = mst_author.author_id
			  LEFT JOIN biblio_topic ON biblio.biblio_id = biblio_topic.biblio_id
			  LEFT JOIN mst_topic ON biblio_topic.topic_id = mst_topic.topic_id
			  LEFT JOIN mst_publisher ON biblio.publisher_id = mst_publisher.publisher_id
			  WHERE 
MATCH (biblio.title, biblio.call_number) AGAINST ('".$kata."' IN NATURAL LANGUAGE MODE) OR
MATCH (mst_author.author_name) AGAINST ('".$kata."' IN NATURAL LANGUAGE MODE) OR
MATCH (mst_publisher.publisher_name) AGAINST ('".$kata."' IN NATURAL LANGUAGE MODE) OR
MATCH (mst_topic.topic) AGAINST ('".$kata."' IN NATURAL LANGUAGE MODE) 
			  GROUP BY title
			  ORDER BY score DESC
				";
	$datany = $dbs->query($query);
	$jml_data_cari = $datany->num_rows;
	$datanya = $dbs->query($query.'LIMIT 10 OFFSET 0');
	$title = '';
	?>
	<header id="sp3" class="center" data-overlay="8">
	  <div class="parallax" id="12">
		<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/bg0.jpg" alt="" class="" style="display:none;width:100%;height:100%;object-fit: cover;">
	  </div>
	  <div class="row">
		  <div class="col-md-6 col-md-offset-3">
			<form method="GET" action="index.php?">
				<input type="hidden" name="h" value="cari">
				<input name="kata" style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; color:white;" type="text" placeholder="" class="col-md-12 elastik6 ac">
				<input type="submit" name="" value="<?php echo e("Cari")?>" style="background-color:white;border-color:white;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
			</form>
		  </div>
	  </div>
	</header>
	<section class="iconblock gallery" style="padding-top:50px;">
		<div class="container border">
		  <div class="row">
			<div class="col-md-12">
			  <p><?php echo e("Menampilkan hasil pencarian")?></p>
			  <h4>"<?php echo $k_ori?>"</h4>
			  <hr>
			</div>
		  </div>
		  <div class="row m-space">
			<div class="konten_cari" style="">
			  <?php
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
						<a href="index.php?h=show_detail&id=<?php echo $value['bid'] ?>">
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
				<?php
				}
				?>
			  <?php $title = $value['title']; } ?>
			</div>
			<div class="clearfix loadnya" style="display:none;"></div>
			<div class="lds-css loadnya" style="display:none;">
			  <div style="width:100%;height:100%;" class="lds-dual-ring">
				<div></div>
				<div></div>
			  </div>
			</div>
		  </div>
		</div>
	</section>


  <?php } elseif ($_GET['h'] == 'cari_lokal') {
	$k_ori = utility::filter_string($_GET['kata']);
	$kata = addslashes(utility::filter_string($_GET['kata']));
	$query = "SELECT korupsi.biblio_id as bid,
			  korupsi.title as title,
			  korupsi.call_number as cm,
			  korupsi.image as img,
			  mst_author.author_id as aid,
			  mst_author.author_name as ana,
			  mst_publisher.publisher_id as pid,
			  mst_publisher.publisher_name as pna,
			  mst_topic.topic_id as tid,
			  mst_topic.topic as topic,
			  MATCH (korupsi.title, korupsi.call_number) AGAINST ('".$kata."' IN NATURAL LANGUAGE MODE) as score
			  FROM korupsi
			  LEFT JOIN korupsi_author ON korupsi.biblio_id = korupsi_author.biblio_id
			  LEFT JOIN mst_author ON korupsi_author.author_id = mst_author.author_id
			  LEFT JOIN korupsi_topic ON korupsi.biblio_id = korupsi_topic.biblio_id
			  LEFT JOIN mst_topic ON korupsi_topic.topic_id = mst_topic.topic_id
			  LEFT JOIN mst_publisher ON korupsi.publisher_id = mst_publisher.publisher_id
			  WHERE 
MATCH (korupsi.title, korupsi.call_number) AGAINST ('".$kata."' IN NATURAL LANGUAGE MODE) OR
MATCH (mst_author.author_name) AGAINST ('".$kata."' IN NATURAL LANGUAGE MODE) OR
MATCH (mst_publisher.publisher_name) AGAINST ('".$kata."' IN NATURAL LANGUAGE MODE) OR
MATCH (mst_topic.topic) AGAINST ('".$kata."' IN NATURAL LANGUAGE MODE) 
			GROUP BY title
			ORDER BY score DESC
";
	
	$dbs->query($query.'LIMIT 10 OFFSET 0');
	$datanya = $dbs->query($query);
	$jml_data_cari = $datanya->num_rows;
	$title = '';
	
	?>
	<header id="sp3" class="center" data-overlay="8">
	  <div class="parallax" id="12">
		<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/bg0.jpg" alt="" class="" style="display:none;width:100%;height:100%;object-fit: cover;">
	  </div>
	  <div class="row">
		  <div class="col-md-6 col-md-offset-3">
			<form method="GET" action="index.php?">
				<input type="hidden" name="h" value="cari_lokal">
				<input name="kata" style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; color:white;" type="text" placeholder="" class="col-md-12 elastik7 ac">
				<input type="submit" name="" value="<?php echo e("Cari")?>" style="background-color:white;border-color:white;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
			</form>
		  </div>
	  </div>
	</header>
	<section class="iconblock gallery" style="padding-top:50px;">
		<div class="container border">
		  <div class="row">
			<div class="col-md-12">
			  <p><?php echo e("Menampilkan hasil pencarian")?></p>
			  <h4>"<?php echo $k_ori?>"</h4>
			  <hr>
			</div>
		  </div>
		  <div class="row m-space">
			<div class="" style="">
			  <?php
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
						  <div class="col-md-6">
							<small><?php echo e("Penerbit") ?> :</small>
							<br>
							<?php echo $value['pna']?>
							<hr>
							<small><?php echo e("Penulis") ?> :</small>
							<br>
							<small>
							  <?php
							  $query_author = "SELECT mst_author.author_id as author_id,
													  mst_author.author_name as author_name,
													  mst_author.authority_type as authority_type
													  FROM korupsi_author
													  JOIN mst_author ON korupsi_author.author_id = mst_author.author_id
													  WHERE korupsi_author.biblio_id = '$bid' ";
							  $e_query_quthor = $dbs->query($query_author);
							  $no_author = 1;
							  foreach ($e_query_quthor as $author) {
								if ($no_author > 1 ) {
								  echo ' <br> ';
								}
								?>
								<a href="index.php?h=cari_lokal&kata=<?php echo $author['author_name']?>"><?php echo $author['author_name']?></a>
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
							<small><?php echo e("No. Panggilan") ?> : </small>
							<br>
							<?php echo $value['cm'] ?>
							<hr>
							<small><?php echo e("Subjek") ?> :</small>
							<br>
							<?php
							$no = 1;
							$subject_query = $dbs->query("SELECT mst_topic.topic as topic,mst_topic.topic_id as topic_id
														  FROM korupsi_topic
														  LEFT JOIN mst_topic ON korupsi_topic.topic_id = mst_topic.topic_id
														  WHERE korupsi_topic.biblio_id = '$bid'");
							foreach ($subject_query as $subject) {
							  if ($no > 1) {
								echo ',';
							  }
							  ?>
							  <a href="index.php?h=cari_lokal&kata=<?php echo $subject['topic'];?>"><?php echo addslashes($subject['topic']);?></a>
							<?php $no++; }
							 ?>
							 <hr id="whatsapp">
						  </div>
						</div>
					  </div>
					</div>
				  </div>
				  <div class="col-md-12">
					<hr>
				  </div>
				<?php
				}
				?>
			  <?php $title = $value['title']; } ?>
			</div>
		  </div>
		</div>
	</section>
  <?php } ?>

  <?php else: ?>
  <!-- halaman index -->
		<header id="neutral" class="center" data-overlay="8">
		  <div class="parallax" id="12">
			<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/2.jpg" alt="" class="sld gbr1" style="width:100%;height:100%;object-fit: cover;">
			<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/bg0.jpg" alt="" class="sld gbr2" style="display:none;width:100%;height:100%;object-fit: cover;">
			<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/0.jpg" alt="" class="sld gbr3" style="display:none;width:100%;height:100%;object-fit: cover;">
			<img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/1.jpg" alt="" class="sld gbr4" style="display:none;width:100%;height:100%;object-fit: cover;">
		  </div>
			<div class="header-in">
				<div class="caption dark1">
					<h1 style="margin-top:-100px;font-family:Poppins, sans-serif;"><?php echo e("Perpustakaan KPK") ?></h1>
					<div class="row">
						<div class="col-md-6 col-md-offset-3">
							<form method="GET" action="index.php?">
								<input type="hidden" name="h" value="cari">
								<input name="kata" style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; color:white;" type="text" placeholder="" class="col-md-12 elastik ac">
								<input type="submit" name="" value="<?php echo e("Cari")?>" style="background-color:white;border-color:white;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
							</form>
						</div>
						<br class="hidden-md-up">
						<div class="col-md-6 col-md-offset-3">
						  <center>
							<p><?php echo e("Tautan Utama Pengetahuan Antikorupsi") ?>
							</p>
						  </center>
						</div>
					</div>
				</div>
				<!-- <img class="arrow" src="img/arrow.svg" alt=""> -->
			</div>
		</header>

		<section class="iconblock gallery center" style="background-color:#929191;padding-top:50px;">
			<div class="container border">
				<div class="row">
					<div class="col col-md-6 col-md-offset-3 title">
						<h2><?php echo e("Koleksi Terbaru") ?></h2>
					</div>
				</div>
				<div class="row m-space">
				  <div id="c_logos" class="area" style="padding-top:10px;">
					  <div class="col-md-12 slide">
							<?php
							$query_pop = "SELECT biblio_id, title, image FROM biblio WHERE image !='' ORDER BY biblio_id DESC LIMIT 10";
							$data_pop = $dbs->query($query_pop);
							foreach ($data_pop as $value) {?>
							  <div>
								<center>
								  <i><img src="images/docs/<?php echo $value['image'] ?>" alt="" style="border-radius:10px; width:150px; height:220px;"></i>
								</center>
								<a href="index.php?h=show_detail&id=<?php echo $value['biblio_id'] ?>">
								  <h3>
									<?php echo substr($value['title'], 0,35);
									if (strlen($value['title']) > 35) {
									  echo "...";
									}
									 ?>
								  </h3>
								</a>
								<p style="color:white;">
								  <?php
								  $id_biblio = $value['biblio_id'];
								  $query_author = "SELECT mst_author.author_name as author
													FROM biblio_author
													LEFT JOIN mst_author ON biblio_author.author_id = mst_author.author_id
													WHERE biblio_author.biblio_id = '$id_biblio'
													";
								  $data_author = $dbs->query($query_author);
								  $no_author = 0;
								  $author = '';
								  foreach ($data_author as $value_author) {
									if ($no_author > 0){
									  $author .= ' | ';
									};
									$author .= $value_author['author'];
									$no_author++;
								  };
								  echo substr($author,0,30);
								  echo "<br /><center>" . showBadge($id_biblio) . "</center><br />";

								  $_detail_link = SWB.'index.php?p=show_detail&id='.$id_biblio;
								  $_detail_link_encoded = urlencode('http://'.$_SERVER['SERVER_NAME'].$_detail_link);
								   ?>
								</p>
								<div class="team-profile">
								  <?php echo e("Bagikan") ?>
								  <div class="social" style="">
									  <a class="sharing" data-type="biblio" data-sosmed="facebook" data-id="<?php echo $id_biblio?>" href="http://www.facebook.com/sharer.php?u=<?php echo $_detail_link_encoded ?>" target="_blank">
										<i class="ion-social-facebook" style="color:black;"></i>
									  </a>
									  <a class="sharing" data-type="biblio" data-sosmed="twitter" data-id="<?php echo $id_biblio?>" href="http://twitter.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
										<i class="ion-social-twitter" style="color:black;"></i>
									  </a>
									  <a class="sharing" data-type="biblio" data-sosmed="google" data-id="<?php echo $id_biblio?>" href="https://plus.google.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
										<i class="ion-social-googleplus" style="color:black;"></i>
									  </a>
									  <a class="sharing" data-type="biblio" data-sosmed="linkedin" data-id="<?php echo $id_biblio?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $_detail_link_encoded ?>" target="_blank">
										<i class="ion-social-linkedin-outline" style="color:black;"></i>
									  </a>
									  <a class="sharing" data-type="biblio" data-sosmed="whatsapp" data-id="<?php echo $id_biblio?>" href="whatsapp://send?text=<?php echo $_detail_link_encoded ?>" target="_blank" id="whatsapp">
										<i class="ion-social-whatsapp-outline" style="color:black;"></i>
									  </a>
								  </div>
								</div>
							  </div>
							<?php
							}
							?>
					  </div>
				  </div>
				</div>
			</div>
		</section>
		<section class="gallery no-lr-pad" style="top:-80px;margin-bottom:-50px;">
			<div class="container-fluid no-pad">
				<div class="row">
					<div class="col col-md-6 col-md-offset-3 title">
						<h2><?php echo e("Koleksi Terpopuler") ?></h2>
						<h4><?php echo e("Terbanyak dipinjam, dibaca dan dikunjungi") ?></h4>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="center-loop-fade">
						  <?php
						  $query_terbaru = "SELECT biblio.biblio_id as biblio_id,
											biblio.title as title,
											biblio.image as image,
											biblio.baca as pembaca,
											biblio.visit as visitor,
											biblio.pinjam as pinjam
											FROM biblio                                            
											WHERE image !=''                                            
											ORDER BY ((pembaca * 0.18)+(visitor * 0.02)+(pinjam * 0.8))
											DESC LIMIT 10";
						  $data_terbaru = $dbs->query($query_terbaru);
						  foreach ($data_terbaru as $value) {
							$id_biblio = $value['biblio_id'];
							$_detail_link = SWB.'index.php?h=show_detail&id='.$id_biblio;
							$_detail_link_encoded = urlencode('http://'.$_SERVER['SERVER_NAME'].$_detail_link);
							?>
							<div>
							  <br>
								<center>
								  <img src="images/docs/<?php echo $value['image'] ?>" alt="" style="border-radius:10px; width:150px; height:220px;">
								</center>
								<br>
								<a href="<?php echo $_detail_link ?>" style="font-size:24px;">
								  <?php echo substr($value['title'], 0,25);
								  if (strlen($value['title']) > 25) {
									echo "...";
								  }
								   ?>
								</a>
								<br>
								<small>
								  <?php

								  $query_author = "SELECT mst_author.author_name as author
													FROM biblio_author
													LEFT JOIN mst_author ON biblio_author.author_id = mst_author.author_id
													WHERE biblio_author.biblio_id = '$id_biblio'
													";
								  $data_author = $dbs->query($query_author);
								  $no_author = 0;
								  $author = '';
								  foreach ($data_author as $value_author) {
									if ($no_author > 0){
									  $author .= ' | ';
									};
									$author .= $value_author['author'];
									$no_author++;
								  };
								  echo substr($author,0,30);
								   ?>
								</small>
								<?php echo "<br /><center>".showBadge($id_biblio).'</center>'; ?>
								<br>
								<a>
								  <small><?php echo e("Dipinjam") ?> : <?php echo $value['pinjam'] ?> <br> <?php echo e("Dibaca") ?> : <?php echo $value['pembaca'] ?> <br> <?php echo e("Dikunjungi") ?> : <?php echo $value['visitor'] ?></small>
								</a>
								<br>
							  <div class="team-profile">
								<div class="social" style="">
									<a class="sharing" data-type="biblio" data-sosmed="facebook" data-id="<?php echo $id_biblio?>" href="http://www.facebook.com/sharer.php?u=<?php echo $_detail_link_encoded ?>" target="_blank">
									  <i class="ion-social-facebook"></i>
									</a>
									<a class="sharing" data-type="biblio" data-sosmed="twitter" data-id="<?php echo $id_biblio?>" href="http://twitter.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
									  <i class="ion-social-twitter"></i>
									</a>
									<a class="sharing" data-type="biblio" data-sosmed="google" data-id="<?php echo $id_biblio?>" href="https://plus.google.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
									  <i class="ion-social-googleplus"></i>
									</a>
									<a class="sharing" data-type="biblio" data-sosmed="linkedin" data-id="<?php echo $id_biblio?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $_detail_link_encoded ?>" target="_blank">
									  <i class="ion-social-linkedin-outline"></i>
									</a>
									<a class="sharing" data-type="biblio" data-sosmed="whatsapp" data-id="<?php echo $id_biblio?>" href="whatsapp://send?text=<?php echo $_detail_link_encoded ?>" target="_blank" id="whatsapp">
									  <i class="ion-social-whatsapp-outline"></i>
									</a>
								</div>
							  </div>
							  <br>
							</div>
						  <?php
						  }
						  ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<div class="masonry four-col no-margin">
		  <?php
			  $query_ss = "SELECT sarasehan_display.img as img, sarasehan.ss_title as title, sarasehan.id as id, sarasehan.tgl_update as tgl_update
							FROM sarasehan_display
							LEFT JOIN sarasehan ON sarasehan_display.img = sarasehan.ss_pic
							ORDER BY sarasehan_display.id ASC
							";
			  $data_ss = $dbs->query($query_ss);
			  foreach ($data_ss as $value_ss) {
				$gbrna[] = $value_ss['img'];
				$title[] = $value_ss['title'];
				$id[] = $value_ss['id'];
				$tgl[] = date('d M Y',strtotime($value_ss['tgl_update']));
			  };
		   ?>
			<div class="grid-sizer"></div>
			<div class="grid-item">
				<a href="index.php?h=sarasehan_detail&id=<?php echo $id[0]?>" style="padding:2px;"><img src="images/sarasehan/<?php echo $gbrna[0] ?>" alt="" style="height:255px;object-fit: cover;">
					<div class="project-info">
						<h2 style="font-size:18px;">
						  <?php
						  if (strlen($title[0] < 20 )) {
							echo $title[0];
						  }else {
							echo substr($title[0], 0, strpos($title[0], ' ', 20)).'...' ;
						  }
						  ?>
						</h2>
						<p><?php echo $tgl[0] ?></p>
						<ul class="tags">
							<li><?php echo e("Klik Untuk Melihat Detail") ?></li>
						</ul>
					</div>
				</a>
			</div>
			<!-- end of item -->
			<div class="grid-item">
				<a href="index.php?h=sarasehan_detail&id=<?php echo $id[1]?>" style="padding:2px;"><img src="images/sarasehan/<?php echo $gbrna[1] ?>" alt="" style="height:255px;object-fit: cover;">
					<div class="project-info">
					  <h2 style="font-size:18px;">
						<?php
						if (strlen($title[1] < 20 )) {
						  echo $title[1];
						}else {
						  echo substr($title[1], 0, strpos($title[1], ' ', 20)).'...' ;
						}
						 ?>
					   </h2>
					  <p><?php echo $tgl[1] ?></p>
					  <ul class="tags">
						  <li><?php echo e("Klik Untuk Melihat Detail") ?></li>
					  </ul>
					</div>
				</a>
			</div>
			<!-- end of item -->
			<div class="grid-item hidden-xs">
				<a href="index.php?h=sarasehan_detail&id=<?php echo $id[4]?>" style="padding:2px;"><img src="images/sarasehan/<?php echo $gbrna[4] ?>" alt="" style="height:510px;object-fit: cover;">
					<div class="project-info">
					  <h2 style="font-size:18px;">
						<?php
						if (strlen($title[4] < 20 )) {
						  echo $title[4];
						}else {
						  echo substr($title[4], 0, strpos($title[4], ' ', 20)).'...' ;
						}
						?>
					  </h2>
					  <p><?php echo $tgl[4] ?></p>
					  <ul class="tags">
						  <li><?php echo e("Klik Untuk Melihat Detail") ?></li>
					  </ul>
					</div>
				</a>
			</div>
			<!-- end of item -->
			<div class="grid-item hidden-xs">
				<a href="index.php?h=sarasehan_detail&id=<?php echo $id[5]?>" style="padding:2px;"><img src="images/sarasehan/<?php echo $gbrna[5] ?>" alt="" style="height:510px;object-fit: cover;">
					<div class="project-info">
					  <h2 style="font-size:18px;">
						<?php
						if (strlen($title[5] < 20 )) {
						  echo $title[5];
						}else {
						  echo substr($title[5], 0, strpos($title[5], ' ', 20)).'...' ;
						}
						?>
					  </h2>
					  <p><?php echo $tgl[5] ?></p>
					  <ul class="tags">
						  <li><?php echo e("Klik Untuk Melihat Detail") ?></li>
					  </ul>
					</div>
				</a>
			</div>
			<!-- end of item -->
			<div class="grid-item hidden-xs">
				<a href="index.php?h=sarasehan_detail&id=<?php echo $id[2]?>" style="padding:2px;"><img src="images/sarasehan/<?php echo $gbrna[2] ?>" alt="" style="height:255px;object-fit: cover;">
					<div class="project-info">
					  <h2 style="font-size:18px;">
						<?php
						if (strlen($title[2] < 20 )) {
						  echo $title[2];
						}else {
						  echo substr($title[2], 0, strpos($title[2], ' ', 20)).'...' ;
						}
						?>
					  </h2>
					  <p><?php echo $tgl[2] ?></p>
					  <ul class="tags">
						  <li><?php echo e("Klik Untuk Melihat Detail") ?></li>
					  </ul>
					</div>
				</a>
			</div>
			<!-- end of item -->
			<div class="grid-item hidden-xs">
				<a href="index.php?h=sarasehan_detail&id=<?php echo $id[3]?>" style="padding:2px;"><img src="images/sarasehan/<?php echo $gbrna[3] ?>" alt="" style="height:255px;object-fit: cover;">
					<div class="project-info">
					  <h2 style="font-size:18px;">
						<?php
						if (strlen($title[3] < 20 )) {
						  echo $title[3];
						}else {
						  echo substr($title[3], 0, strpos($title[3], ' ', 20)).'...' ;
						}
						?>
					  </h2>
					  <p><?php echo $tgl[3] ?></p>
					  <ul class="tags">
						  <li><?php echo e("Klik Untuk Melihat Detail") ?></li>
					  </ul>
					</div>
				</a>
			</div>
			<!-- end of item -->
		</div>
		<section class="dark1">
			<div class="container">
				<div class="row m-space">
				  <div class="col-md-12 service">
					<div class="tentang_kami" style="cursor:pointer;">
					  <center>
						<h2><?php echo e("Tentang Kami") ?></h2>
						<br>
						<!-- <hr> -->
					  </center>
					</div>
					<div class="tk_detil" style="display:none;">
					  <center>
						<h4><?php echo e("Terima kasih telah berkunjung ke katalog online Perpustakaan KPK") ?></h4>
						<p>
						  <?php echo e("Perpustakaan KPK merupakan perpustakaan khusus bidang korupsi dan subjek terkait. Perpustakaan KPK mengumpulkan, menyimpan, mengelola, dan menyebarluaskan data, informasi, dan pengetahuan publik yang berasal dari tugas atau kegiatan internal KPK maupun stake holder lainnya. Perpustakaan KPK berperan mendukung pelaksanaan tugas KPK dan masyarakat luas dalam memenuhi kebutuhan literaturnya") ?>
						</p>
					  </center>
					  <div class="col-sm-6 service">
						  <i class="ion-ios-lightbulb-outline"></i>
						  <h5><?php echo e("Visi") ?></h5>
						  <p><?php echo e("Menjadi perpustakaan rujukan bidang korupsi dan subjek terkait") ?> </p>
					  </div>
					  <div class="col-sm-6 service">
						  <i class="ion-ios-barcode-outline"></i>
						  <h5><?php echo e("Misi") ?></h5>
						  <p><?php echo e("Mengumpulkan dan mengelola data, informasi, dan pengetahuan publik mengenai korupsi dan subjek terkait") ?> .</p>
					  </div>
					  <div class="col-md-12">
						<center>
						  <p>
							<?php echo e("Kami percaya bahwa masyarakat yang melek informasi mengenai korupsi membuka jalan menuju masyarakat yang anti korupsi") ?>.
							<br><br>
							<?php echo e("Salam") ?>,
							<br>
							<?php echo e("Pustakawan KPK") ?>
						  </p>
						</center>
					  </div>
					</div>
					<center>
					  <small class="tk_detil tentang_kami"><a href="javascript:void(0)"><?php echo e("Selengkapnya") ?> <br><i class="ion-ios-arrow-down"></i></a></small>
					</center>
				  </div>
				</div>
			</div>
		</section>
		<div class="container-fluid no-pad no-max">
			<div class="col-md-6 mh">
			  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.4256944914428!2d106.82789701424126!3d-6.207446395506333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f40eaa579f6f%3A0x3177a5ac88a987e8!2sGedung+Merah+Putih+KPK!5e0!3m2!1sid!2sid!4v1504597967593" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
			</div>
			<div class="col-md-3 col-sm-6 mh center extra-pad"
			   style="background-color:red;">

				<div class="holder">
					<div class="placer">
					  <p style="color:white;">
						<?php echo e("Kunjungi kami di") ?> :
						<br>
						<br>
						Gedung Merah Putih KPK <br>
						Lt. 1, Jln Kuningan Persada <br>
						Kav. 4, Setiabudi <br>
						Jakarta Selatan, 12950 <br>
						<br>

						E-mail : perpustakaan@kpk.go.id  <br>
						<?php echo e("Telp") ?>. (021) 25578300, ext. 8642 <br>
					  </p>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 mh center dark extra-pad parallax-container address-hold"
				data-overlay="8">
				<div class="parallax">
				  <img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/bg0.jpg" alt=""></div>
				  <br>
				<div class="holder">
					<div class="placer">
						<p>
						  <?php echo e("Waktu Pelayanan") ?> <br>
						  <br>
						  <?php echo e("Senin-Jumat:").'<br>'.e("08.00-17.00 WIB").'<br>'.e("(istirahat 12.00-13.00 WIB)") ?> <br><br>
						  <!-- informasi@kpk.go.id -->

							<a href="https://www.facebook.com/KomisiPemberantasanKorupsi" target="_blank"><i style="color:white;" class="ion-social-facebook"></i></a>
							&nbsp;
							<a href="https://twitter.com/KPK_RI" target="_blank"><i style="color:white;" class="ion-social-twitter-outline"></i></a>
							&nbsp;
							<a href="https://www.youtube.com/user/HUMASKPK" target="_blank"><i style="color:white;" class="ion-social-youtube-outline"></i></a>
							&nbsp;
							<a href="https://www.instagram.com/official.kpk/" target="_blank"><i style="color:white;" class="ion-social-instagram-outline"></i></a>
							&nbsp;

						</p>
					</div>
				</div>
			</div>
		</div>

  <?php endif; ?>
  <footer id="smart" class="" style="background-color:#d6d6d6;">
	  <div class="container">
		  <div class="col-md-12" style="max-height:300px;overflow:auto;">
			<center>
			  <?php
				$query_link = "SELECT * FROM link_terkait";
				$data_link = $dbs->query($query_link);
				foreach ($data_link as $value) {
				  ?>
				  <div class="col-md-3">
					<center>
					  <a href="<?php echo $value['link_address']?>" target="_blank" >
						<!-- www.kpk.go.id -->
						<img src="images/sarasehan/<?php echo $value['link_pic']?>" alt="" style="max-width:150px;max-height:65px;">
					  </a>
					  <br>
					</center>
				  </div>
				  
				  <?php
				}
			  ?>
				<div class="col-md-3">
					<center>
						<a href="https://aclc.kpk.go.id/" target="_blank" >
							<img src="images/logo/aclc-logo.png" alt="" style="max-width:150px;max-height:65px;">
						</a>
						<br>
					</center>
				</div>
			</center>
			<div class="clearfix"></div>
			<div class="col-md-12">
			  <hr>
			</div>
			  <p class="footer-text" style="width:100%">
				<span class="" style="font-size:12px;color:black;">
		   <center>
			<?php echo e("Hak Cipta") ?> &copy; <?php echo e("Perpustakaan KPK") ?>. <?php echo date('Y'); ?>
		   </center>
				</span>
			  </p>
		  </div>
	  </div>
  </footer>
</div>

<!--[if lt IE 9]>
<div class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</div>
<![endif]-->





<script type="text/javascript">
var arc = ['test','testing','testimoni'];
/* */
// untuk bibliography
$(".elastik").autocomplete({
  serviceUrl: "<?php echo $sysconf['template']['dir']; ?>/custom/data.php"
});
$(".elastik2").autocomplete({
  serviceUrl: "<?php echo $sysconf['template']['dir']; ?>/custom/data.php"
});
$(".elastik3").autocomplete({
  serviceUrl: "<?php echo $sysconf['template']['dir']; ?>/custom/data.php"
});
$(".elastik6").autocomplete({
  serviceUrl: "<?php echo $sysconf['template']['dir']; ?>/custom/data.php"
});

// untuk PLU
$(".elastik4").autocomplete({
  serviceUrl: "<?php echo $sysconf['template']['dir']; ?>/custom/data2.php"
});
$(".elastik5").autocomplete({
  serviceUrl: "<?php echo $sysconf['template']['dir']; ?>/custom/data2.php"
});
$(".elastik7").autocomplete({
  serviceUrl: "<?php echo $sysconf['template']['dir']; ?>/custom/data2.php"
});

// untuk sarasehan
$(".elastik9").autocomplete({
  serviceUrl: "<?php echo $sysconf['template']['dir']; ?>/custom/data3.php"
});
$(".elastik10").autocomplete({
  serviceUrl: "<?php echo $sysconf['template']['dir']; ?>/custom/data3.php"
});

$(document).on('click','.autocomplete-suggestions',function() {
  $('.ac').focus();
});
/* */
var nomernya = 1;
sldsld();
function myFunction() {
	setTimeout(sldsld, 6000);
}
function sldsld() {
  if (nomernya == 1) {
	$('.gbr1').fadeOut(2000);
	$('.gbr2').fadeIn(2000);
  } else if (nomernya == 2) {
	$('.gbr2').fadeOut(2000);
	$('.gbr3').fadeIn(2000);
  }else if (nomernya == 3) {
	$('.gbr3').fadeOut(2000);
	$('.gbr4').fadeIn(2000);
  }else if (nomernya == 4) {
	$('.gbr4').fadeOut(2000);
	$('.gbr1').fadeIn(2000);
	nomernya = 0;
  }
  nomernya = nomernya+1;
  myFunction();

}
$(document).on('click','.tentang_kami',function(e) {
  $('.tk_detil').slideToggle();
});
$(document).on('click','.smw',function(e) {
  $('.a').slideToggle();
  $('.ab').toggle();
});

var typedia = '';
var sosmedia = '';
var iddia = '';
$(document).on('click','.sharing',function(e) {
  typedia = $(this).data('type');
  sosmedia = $(this).data('sosmed');
  iddia = $(this).data('id');
  $.ajax({
	type : 'POST',
	url : "<?php echo $sysconf['template']['dir']; ?>/custom/sosmed.php",
	data :{
	  'typedia' : typedia,
	  'sosmedia' : sosmedia,
	  'iddia' : iddia,
	},
	success : function(data) {
	   typedia = '';
	   sosmedia = '';
	   iddia = '';
	   location.reload();
	}
  })
});
</script>
<?php
	// jquery function for lazyload on search results
	if (isset($jml_data_cari) && $jml_data_cari > 10) { ?>
	  <script type="text/javascript">
	  	var jenis = "<?php echo $_GET['h'];?>";
		var datanya = <?php echo $jml_data_cari?> - 10;
		var offset = 10;
		var kata = "<?php echo $kata; ?>";
		  $(document).scroll(function(){
			  if ($(window).scrollTop() + $(window).height() == $(document).height()) {
				if (datanya > 0) {
				  $('.loadnya').slideDown();
				  $.ajax({
					type : 'POST',
					url : "<?php echo $sysconf['template']['dir']."/custom/".strtolower($_GET['h']).".php"; ?>",
					data :{
					  'offset' : offset,
					  'kata' : kata,
					  'jenis': jenis
					},
					success : function(data) {
					  $('.loadnya').slideDown();
					  $('.konten_cari').append(data);
					  datanya = datanya - 10;
					  offset = offset + 10;
					  // hide loader jika telah sampai akhir data
					  if(datanya <= 0 ) $('.lds-dual-ring').hide();
					}
				  })
				}
			  }
		  });

	  </script>
	<?php }
 ?>

<?php endif; ?>

<?php if(isset($_GET['search']) || isset($_GET['p'])): ?>
  <?php
  // Advance Search
  include "partials/advsearch.php";

  // Footer
  include "partials/footer.php";

  // Chat Engine
  include LIB."contents/chat.php";

  // Background
  include "partials/bg.php";
  ?>

  <script>
  $(document).on('click','.tk',function(e) {
	$('.tk_detil').hide();
  })
	<?php if(isset($_GET['search']) && (isset($_GET['keywords'])) && ($_GET['keywords'] != ''))   : ?>
	$('.biblioRecord .detail-list, .biblioRecord .title, .biblioRecord .abstract, .biblioRecord .controls').highlight(<?php echo $searched_words_js_array; ?>);
	<?php endif; ?>

	//Replace blank cover
	$('.book img').error(function(){
	  var title = $(this).parent().attr('title').split(' ');
	  $(this).parent().append('<div class="s-feature-title">' + title[0] + '<br/>' + title[1] + '<br/>... </div>');
	  $(this).attr({
		src   : './template/default/img/book.png',
		title : title + title[0] + ' ' + title[1]
	  });
	});

	//Replace blank photo
	$('.librarian-image img').error(function(){
	  $(this).attr('src','./template/default/img/avatar.jpg');
	});

	//Feature list slider
	function mycarousel_initCallback(carousel)
	{
	  // Disable autoscrolling if the user clicks the prev or next button.
	  carousel.buttonNext.bind('click', function() {
		carousel.startAuto(0);
	  });

	  carousel.buttonPrev.bind('click', function() {
		carousel.startAuto(0);
	  });

	  // Pause autoscrolling if the user moves with the cursor over the clip.
	  carousel.clip.hover(function() {
		carousel.stopAuto();
	  }, function() {
		carousel.startAuto();
	  });
	};

	jQuery('#topbook').jcarousel({
		auto: 5,
		wrap: 'last',
		initCallback: mycarousel_initCallback
	});

	$(window).scroll(function() {
	  // console.log($(window).scrollTop());
	  if ($(window).scrollTop() > 50) {
		$('.s-main-search').removeClass("animated fadeIn").addClass("animated fadeOut");
	  } else {
		$('.s-main-search').removeClass("animated fadeOut").addClass("animated fadeIn");
	  }
	});

	$('.s-search-advances').click(function() {
	  $('#advance-search').animate({opacity : 1,}, 500, 'linear');
	  $('#simply-search, .s-menu, #content').hide();
	  $('.s-header').addClass('hide-header');
	  $('.s-background').addClass('hide-background');
	});

	$('#hide-advance-search').click(function(){
	  $('.s-header').toggleClass('hide-header');
	  $('.s-background').toggleClass('hide-background');
	  $('#advance-search').animate({opacity : 0,}, 500, 'linear', function(){
		$('#simply-search, .s-menu, #content').show();
	  });
	});
  </script>
<?php endif; ?>
</body>
</html>
