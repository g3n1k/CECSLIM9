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