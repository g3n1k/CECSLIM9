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

if (!defined('INDEX_AUTH')) {
  die("can not access this file directly");
} elseif (INDEX_AUTH != 1) {
  die("can not access this file directly");
}

?>
<!--
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
// Meta Template
include "partials/meta_new.php";
?>

</head>

<body itemscope="itemscope" itemtype="http://schema.org/WebPage">
  <div class="scrollup">
      <i class="ion-ios-arrow-up"></i>
  </div>

  <div class="web-in">
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
                            <a href="index.html">
                                <img class="logo" src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/lokok.png" style="height:80px;width:80px; border-radius:80px; margin-top:10px;" alt="" >
                                <img class="logo alt" src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/loko.png" style="height:80px;width:80px;" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="nav-content n_ pull-right">
                        <ul>
                            <li class="tet"><a href="#" style="font-size:16px;">Beranda</a></li>
                            <li><a href="#" style="font-size:16px;">Koleksi</a></li>
                            <li><a href="#" style="font-size:16px;">Publikasi Lokal Universitas</a></li>
                            <li><a href="#" style="font-size:16px;">Aktivitas</a></li>
                            <li><a href="#" style="font-size:16px;">Newsletter</a></li>
                            <li>
                              <a href="#" style="font-size:16px;">
                                <img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/indonesia.png" alt="" style="height:20px; width:20px; border-radius:100px;">
                              </a>
                              &nbsp;&nbsp;
                              <a href="#" style="font-size:16px;">
                                <img src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/img/inggris.jpg" alt="" style="height:20px; width:20px; border-radius:100px;">
                              </a>
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
      <?php if ($_GET['h'] == 'login'): ?>
        <?php include 'login_template.inc.php' ?>
      <?php else: ?>

      <?php endif; ?>
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
                      <h1 style="margin-top:-100px;font-family:Poppins, sans-serif;">Perpustakaan KPK</h1>
                      <div class="row">
                          <div class="col-md-6 col-md-offset-3">
                              <form action="post">
                                  <input style="-webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; color:white;" type="text" placeholder="Cari... " class="col-md-12">
                                  <input type="submit" name="" value="Search" style="background-color:white;border-color:white;color:black;position:absolute;top:0px; right:15px;border-top-right-radius:25px;border-bottom-right-radius:25px;">
                              </form>
                          </div>
                          <br class="hidden-md-up">
                          <div class="col-md-6 col-md-offset-3">
                            <center>
                              <p>Tautan Utama Pengetahuan Antikorupsi
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
                          <h2>Koleksi Terbaru Kami</h2>
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
                                  <a href="<?php echo $value['biblio_id'] ?>">
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

                                    $_detail_link = SWB.'index.php?p=show_detail&id='.$id_biblio;
                                    $_detail_link_encoded = urlencode('http://'.$_SERVER['SERVER_NAME'].$_detail_link);
                                     ?>
                                  </p>
                                  <div class="team-profile">
                                    Share to
                                    <div class="social" style="">
                                        <a href="http://www.facebook.com/sharer.php?u=<?php echo $_detail_link_encoded ?>" target="_blank">
                                          <i class="ion-social-facebook" style="color:black;"></i>
                                        </a>
                                        <a href="http://twitter.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
                                          <i class="ion-social-twitter" style="color:black;"></i>
                                        </a>
                                        <a href="https://plus.google.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
                                          <i class="ion-social-googleplus" style="color:black;"></i>
                                        </a>
                                        <a href="http://www.digg.com/submit?url=<?php echo $_detail_link_encoded ?>" target="_blank">
                                          <i class="ion-social-designernews" style="color:black;"></i>
                                        </a>
                                        <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $_detail_link_encoded ?>" target="_blank">
                                          <i class="ion-social-linkedin-outline" style="color:black;"></i>
                                        </a>
                                        <a href="http://reddit.com/submit?url=<?php echo $_detail_link_encoded ?>" target="_blank">
                                          <i class="ion-social-reddit" style="color:black;"></i>
                                        </a>
                                        <a href="whatsapp://send?text=<?php echo $_detail_link_encoded ?>" target="_blank" id="whatsapp">
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
          <section class="gallery no-lr-pad" style="top:-100px;margin-bottom:-50px;">
              <div class="container-fluid no-pad">
                  <div class="row">
                      <div class="col col-md-6 col-md-offset-3 title">
                          <h2>Koleksi Terpopuler</h2>
                          <h4>Terbanyak dipinjam, dibaca dan dikunjungi</h4>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="center-loop-fade">
                            <?php
                            $query_terbaru = "SELECT biblio.isbn_issn as isbn_issn,
                                              biblio.title as title,
                                              biblio.biblio_id as biblio_id,
                                              biblio.image as image,
                                              item.item_code as item_code,
                                              COUNT(pengunjung.id_pengunjung) as visitor,
                                              COUNT(baca.id_baca) as pembaca,
                                              COUNT(loan.loan_id) as pinjam,
                                              (COUNT(pengunjung.id_pengunjung) + COUNT(baca.id_baca) + COUNT(loan.loan_id)) as total
                                              FROM biblio
                                              LEFT JOIN item ON biblio.biblio_id = item.biblio_id
                                              LEFT JOIN pengunjung ON biblio.biblio_id = pengunjung.biblio_id
                                              LEFT JOIN baca ON biblio.biblio_id = baca.biblio_id
                                              LEFT JOIN loan ON item.item_code = loan.item_code
                                              WHERE image !=''
                                              GROUP BY title
                                              ORDER BY total
                                              DESC LIMIT 10";
                            $data_terbaru = $dbs->query($query_terbaru);
                            foreach ($data_terbaru as $value) {
                              $id_biblio = $value['biblio_id'];
                              $_detail_link = SWB.'index.php?p=show_detail&id='.$id_biblio;
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
                                  <br>
                                  <a>
                                    <small>Dipinjam x: <?php echo $value['pinjam'] ?> <br> Dibaca : <?php echo $value['pembaca'] ?> <br> Dikunjungi : <?php echo $value['visitor'] ?></small>
                                  </a>
                                  <br>
                                <div class="team-profile">
                                  <div class="social" style="">
                                      <a href="http://www.facebook.com/sharer.php?u=<?php echo $_detail_link_encoded ?>" target="_blank">
                                        <i class="ion-social-facebook"></i>
                                      </a>
                                      <a href="http://twitter.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
                                        <i class="ion-social-twitter"></i>
                                      </a>
                                      <a href="https://plus.google.com/share?url=<?php echo $_detail_link_encoded ?>" target="_blank">
                                        <i class="ion-social-googleplus"></i>
                                      </a>
                                      <a href="http://www.digg.com/submit?url=<?php echo $_detail_link_encoded ?>" target="_blank">
                                        <i class="ion-social-designernews"></i>
                                      </a>
                                      <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $_detail_link_encoded ?>" target="_blank">
                                        <i class="ion-social-linkedin-outline"></i>
                                      </a>
                                      <a href="http://reddit.com/submit?url=<?php echo $_detail_link_encoded ?>" target="_blank">
                                        <i class="ion-social-reddit"></i>
                                      </a>
                                      <a href="whatsapp://send?text=<?php echo $_detail_link_encoded ?>" target="_blank" id="whatsapp">
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
                  <a href="#"><img src="images/sarasehan/<?php echo $gbrna[0] ?>" alt="" style="height:255px;object-fit: cover;">
                      <div class="project-info">
                          <h2 style="font-size:18px;"><?php echo substr($title[0], 0, strpos($title[0], ' ', 20)).'...' ?></h2>
                          <p><?php echo $tgl[0] ?></p>
                          <ul class="tags">
                              <li>Klik Untuk Melihat Detail</li>
                          </ul>
                      </div>
                  </a>
              </div>
              <!-- end of item -->
              <div class="grid-item">
                  <a href="#"><img src="images/sarasehan/<?php echo $gbrna[1] ?>" alt="" style="height:255px;object-fit: cover;">
                      <div class="project-info">
                        <h2 style="font-size:18px;"><?php echo substr($title[1], 0, strpos($title[1], ' ', 20)).'...' ?></h2>
                        <p><?php echo $tgl[1] ?></p>
                        <ul class="tags">
                            <li>Klik Untuk Melihat Detail</li>
                        </ul>
                      </div>
                  </a>
              </div>
              <!-- end of item -->
              <div class="grid-item">
                  <a href="#"><img src="images/sarasehan/<?php echo $gbrna[4] ?>" alt="" style="height:510px;object-fit: cover;">
                      <div class="project-info">
                        <h2 style="font-size:18px;"><?php echo substr($title[4], 0, strpos($title[4], ' ', 20)).'...' ?></h2>
                        <p><?php echo $tgl[4] ?></p>
                        <ul class="tags">
                            <li>Klik Untuk Melihat Detail</li>
                        </ul>
                      </div>
                  </a>
              </div>
              <!-- end of item -->
              <div class="grid-item">
                  <a href="#"><img src="images/sarasehan/<?php echo $gbrna[5] ?>" alt="" style="height:510px;object-fit: cover;">
                      <div class="project-info">
                        <h2 style="font-size:18px;"><?php echo substr($title[5], 0, strpos($title[5], ' ', 20)).'...' ?></h2>
                        <p><?php echo $tgl[5] ?></p>
                        <ul class="tags">
                            <li>Klik Untuk Melihat Detail</li>
                        </ul>
                      </div>
                  </a>
              </div>
              <!-- end of item -->
              <div class="grid-item">
                  <a href="#"><img src="images/sarasehan/<?php echo $gbrna[2] ?>" alt="" style="height:255px;object-fit: cover;">
                      <div class="project-info">
                        <h2 style="font-size:18px;"><?php echo  substr($title[2], 0, strpos($title[2], ' ', 20)).'...' ?></h2>
                        <p><?php echo $tgl[2] ?></p>
                        <ul class="tags">
                            <li>Klik Untuk Melihat Detail</li>
                        </ul>
                      </div>
                  </a>
              </div>
              <!-- end of item -->
              <div class="grid-item">
                  <a href="#"><img src="images/sarasehan/<?php echo $gbrna[3] ?>" alt="" style="height:255px;object-fit: cover;">
                      <div class="project-info">
                        <h2 style="font-size:18px;"><?php echo substr($title[3], 0, strpos($title[3], ' ', 20)).'...' ?></h2>
                        <p><?php echo $tgl[3] ?></p>
                        <ul class="tags">
                            <li>Klik Untuk Melihat Detail</li>
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
                      <center>
                        <h2>Tentang Kami</h2>
                        <hr>
                        <h4>Terima kasih telah berkunjung ke katalog online Perpustakaan KPK</h4>
                        <p>
                          Perpustakaan KPK merupakan perpustakaan khusus bidang korupsi dan subjek terkait. Perpustakaan KPK mengumpulkan, menyimpan, mengelola, dan menyebarluaskan data, informasi, dan pengetahuan publik yang berasal dari tugas atau kegiatan internal KPK maupun stake holder lainnya. Perpustakaan KPK berperan mendukung pelaksanaan tugas KPK dan masyarakat luas dalam memenuhi kebutuhan literaturnya
                        </p>
                      </center>
                    </div>
                      <div class="col-sm-6 service">
                          <i class="ion-ios-lightbulb-outline"></i>
                          <h5>Visi</h5>
                          <p>Menjadi perpustakaan rujukan bidang korupsi dan subjek terkait </p>
                      </div>
                      <div class="col-sm-6 service">
                          <i class="ion-ios-barcode-outline"></i>
                          <h5>Misi</h5>
                          <p>Mengumpulkan dan mengelola data, informasi, dan pengetahuan publik mengenai korupsi dan subjek terkait .</p>
                      </div>
                      <div class="col-md-12">
                        <center>
                          <p>
                            Kami percaya bahwa masyarakat yang melek informasi mengenai korupsi membuka jalan menuju masyarakat yang anti korupsi.
                            <br><br>
                            Salam,
                            <br>
                            Pustakawan KPK
                          </p>
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
                          Kunjungi kami di :
                          <br>
                          <br>
                          Gedung Merah Putih KPK <br>
                          Lt. 1, Jln Kuningan Persada <br>
                          Kav. 4, Setiabudi <br>
                          Jakarta Selatan, 12950 <br>
                          <br>

                          E-mail : perpustakaan@kpk.go.id  <br>
                          Telp. (021) 25578300, ext. 8642 <br>
                        </p>
                      </div>
                  </div>
              </div>
              <div class="col-md-3 col-sm-6 mh center extra-pad dark parallax-container address-hold"
                  data-overlay="9">
                  <div class="parallax"><img src="assets/img/headers/subpage.jpg" alt=""></div>
                  <div class="holder">
                      <div class="placer">
                          <p>
                            Waktu Pelayanan <br>
                            <br>
                            Senin-Jumat: 08.00-17.00 WIB (istirahat 12.00-13.00 WIB) <br><br>


                            <!-- informasi@kpk.go.id -->
                          </p>
                      </div>
                  </div>
              </div>
          </div>
          <footer id="smart" class="dark1">
              <div class="container">
                      <div class="col-xs-12 xs-center" style="top:-20px;">
                          <p class="footer-text">
                            <a href="https://www.kpk.go.id/id" target="_blank">www.kpk.go.id</a>
                            <a href="https://acch.kpk.go.id" target="_blank">acch.kpk.go.id</a>
                            <a href="http://kanal.kpk.go.id/" target="_blank">kanal.kpk.go.id</a>
                            <br>
                            <a href="https://www.facebook.com/KomisiPemberantasanKorupsi" target="_blank"><i class="ion-social-facebook"></i></a>
                            <a href="https://twitter.com/KPK_RI" target="_blank"><i class="ion-social-twitter"></i></a>
                            <a href="https://www.youtube.com/user/HUMASKPK" target="_blank"><i class="ion-social-youtube"></i></a>
                            <span class="pull-right hidden-sm hidden-xs" style="font-size:12px;">
                              Hak Cipta &copy; Perpustakaan KPK.
                            </span>
                            <br>
                            <span class=" hidden-md hidden-lg" style="font-size:12px;">
                              Hak Cipta &copy; Perpustakaan KPK.
                            </span>
                          </p>
                      </div>
              </div>
          </footer>
    <?php endif; ?>

  </div>

<!--[if lt IE 9]>
<div class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</div>
<![endif]-->




<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/jquery-2.2.1.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/matchHeight-min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/contact/validator.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/contact/contact.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/pace.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/headroom/headroom.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/owl-slider/owl.carousel.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/slideshow/anime.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/slideshow/imagesloaded.pkgd.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/slideshow/main.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/parallax/materialize.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/lightbox/lity.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/tabs/jquery.tabslet.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/vendor/masonry.pkgd.min.js"></script>
<script src="<?php echo $sysconf['template']['dir']; ?>/custom/assets/js/main.js"></script>
<script type="text/javascript">
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
</script>

</body>
</html>
