<?php
/**
 * Copyright (C) 2007,2008  Arie Nugraha (dicarve@yahoo.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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
 *
 */

/* Place Management section */

// key to authenticate
define('INDEX_AUTH', '1');
// key to get full database access
define('DB_ACCESS', 'fa');

// main system configuration
require '../../../sysconfig.inc.php';
// IP based access limitation
//require LIB.'ip_based_access.inc.php';
//do_checkIP('smc');
//do_checkIP('smc-masterfile');
// start the session
require SB.'admin/default/session.inc.php';
require SB.'admin/default/session_check.inc.php';
require SIMBIO.'simbio_GUI/table/simbio_table.inc.php';
require SIMBIO.'simbio_GUI/form_maker/simbio_form_table_AJAX.inc.php';
require SIMBIO.'simbio_GUI/paging/simbio_paging.inc.php';
require SIMBIO.'simbio_DB/datagrid/simbio_dbgrid.inc.php';
require SIMBIO.'simbio_DB/simbio_dbop.inc.php';
?>
<fieldset class="menuBox">
  <div class="menuBoxInner masterFileIcon">
    <div class="per_title">
        <h2><?php echo __('Book Activity Index'); ?></h2>
    </div>
  </div>
</fieldset>
<section class="indez">
  <div class="contentDesc baca">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-info">
            <div class="panel-body" style="min-height:470px;">
              <section style=""><?php echo __('Title'); ?> :
                <input class="alfind" type="text" name="keywords" size="30" />
                <section class="fil" style="display:none;">
                  <div class="divRow">
                    <div class="divRowLabel">
                      <?php echo __('Date Range'); ?>
                    </div>
                    <div class="divRowContent">
                      <div class="dateField">
                        <input class="dateInput awal" type="date" name="awal" id="startDate" value="2000-01-01">
                        <a class="calendarLink notAJAX" style="cursor: pointer;" onclick="javascript: dateType = 'date'; openCalendar('startDate');" title="Open Calendar"></a>
                      </div>
                      <span style="margin-top:100px;">&nbsp;&nbsp;  &nbsp;&nbsp;</span>
                      <div class="dateField">
                        <input class="dateInput akhir" type="date" name="akhir" id="untilDate" value="<?php echo date('Y-m-d')?>" >
                        <a class="calendarLink notAJAX" style="cursor: pointer;" onclick="javascript: dateType = 'date'; openCalendar('untilDate');" title="Open Calendar"></a>
                      </div>
                    </div>
                  </div>
                </section>
                <button type="button" name="button" class="btn btn-primary fill"><?php echo __('Show More Filter Options'); ?></button>
                <input type="submit" value="<?php echo __('Search'); ?>" class="button btn btn-primary alcari" />
              </section>
              <hr>
              <div class="biblioPaging pull-right">
                <span class="pagingList lis">
                </span>
              </div>

              <div class="biblioPaging pull-left">
                <form action="<?php echo MWB; ?>korupsi/al_excel.php" method="post" target="_blank">
                  <input class="kat" type="hidden" name="kata">
                  <input class="taw" type="hidden" name="tgl_awal">
                  <input class="tak" type="hidden" name="tgl_akhir">
                  <input type="submit" name="" value="Export Excel" class="btn btn-success" style="display:inline-block">
                </form>
              </div>

              <div class="biblioPaging pull-left">
                <form action="<?php echo MWB; ?>korupsi/al_pdf.php" method="post" target="_blank">
                  <input class="kat" type="hidden" name="kata">
                  <input class="taw" type="hidden" name="tgl_awal">
                  <input class="tak" type="hidden" name="tgl_akhir">
                  &nbsp;<input type="submit" name="" value="Export PDF" class="btn btn-danger" style="display:inline-block">
                </form>
              </div>
              <br>
              <hr>
              <table align="center" id="dataList" cellpadding="5" cellspacing="0">
                <tr class="dataListHeader" style="font-weight: bold; cursor: pointer; background-color: rgb(49, 53, 62);">
                  <td class="sort" style="cursor:pointer;" data-value="ncm" data-sort="DESC"></td>
                  <td class="sort" style="cursor:pointer;" data-value="isbn_issn" data-sort="DESC"><?php echo __('Item Code'); ?></td>
                  <td class="sort" style="cursor:pointer;" data-value="title" data-sort="DESC"><?php echo __('Title'); ?></td>
                  <td class="sort" style="cursor:pointer;" data-value="visitor" data-sort="DESC"><?php echo __('Visit'); ?></td>
                  <td class="sort" style="cursor:pointer;" data-value="pembaca" data-sort="DESC"><?php echo __('Read'); ?></td>
                  <td class="sort" style="cursor:pointer;" data-value="pinjam" data-sort="DESC"><?php echo __('Loan'); ?></td>
                  <td class="sort" style="cursor:pointer;" data-value="sharing" data-sort="DESC"><?php echo __('Share'); ?></td>
                  <td class="sort" style="cursor:pointer;" data-value="komentar" data-sort="DESC"><?php echo __('Comment'); ?></td>
                </tr>
                <tbody class="isian">

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="detil" style="display:none;">
  <div class="contentDesc">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-info">
            <div class="panel-body">
              <h4>Detail Activity</h4>
              <hr>
              <div class="col-md-12">
                <button type="button" name="button" class="btn btn-sm btn-default kembali">Back</button>
              </div>
              <hr>
              <section class="detailnya">

              </section>
              <hr>
              <div class="col-md-12">
                <br>
                <button type="button" name="button" class="btn btn-sm btn-default kembali">Back</button>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script type="text/javascript">
  var keyword = '';
  var fieldnya = '';
  var page = 1;
  var valnya = 'title';
  var sortnya = 'ASC';

  var tgl_awal = '2000-01-01';
  var tgl_akhir = '<?php echo date('Y-m-d')?>';

  var idnya = '';

  $('.sort').click(function(e) {
    valnya = $(this).data('value');
    sortnya = $(this).data('sort');
    $('.sort').data('sort','DESC');
    if (sortnya == 'DESC') {
      $(this).data('sort','ASC');
    };
    buku();
    e.preventDefault();
  })
  $(document).on('click','.alcari',function(e) {
    keyword = $('.alfind').val();
    tgl_awal = $('.awal').val();
    tgl_akhir = $('.akhir').val();

    paging();
    buku();
    e.preventDefault();
  })
  $(".alfind").on('keyup', function (e) {
      if (e.keyCode == 13) {
        keyword = $('.alfind').val();
        tgl_awal = $('.awal').val();
        tgl_akhir = $('.akhir').val();
        paging();
        buku();
        e.preventDefault();
      }
  });

  $(document).on('click','.lmn',function() {
    page = $(this).data('value');
    $('.isian').empty();
    paging();
    buku();
  })

  $('.isian').on('click','.ditel',function() {
    idnya = $(this).data('id');
    tgl_awal = $('.awal').val();
    tgl_akhir = $('.akhir').val();
    detail_act()
    $('.indez').slideUp();
    $('.detil').slideDown();
  });

  $('.kembali').click(function() {
    idnya = '';
    $('.detil').slideUp();
    $('.indez').slideDown();
  })

  function paging() {
    $('.lis').empty();
    $.ajax({
      type : 'POST',
      url : '<?php echo MWB; ?>korupsi/al_paging.php',
      dataType : 'JSON',
      data : {
        'keyword':keyword,
        'fieldnya':fieldnya,
        'page':page,
        'tgl_awal':tgl_awal,
        'tgl_akhir':tgl_akhir,
      },
      success:function(data) {
        // alert(data.halaman);
        if (data.jml > 10) {
          for (var i = 0; i < data.halaman.length; i++) {
            $('.lis').append(
            '&nbsp;<a href="javascript:void(0)" class="lmn" data-value="'+data.halaman[i]+'">'+data.halaman[i]+'</a>&nbsp;'
            )
          }
        }
      }
    })
  }
  paging();
  function buku() {
    $('.kat').val(keyword);
    $('.taw').val(tgl_awal);
    $('.tak').val(tgl_akhir);

    $('.isian').empty()
    $.ajax({
      type : 'POST',
      url : '<?php echo MWB; ?>korupsi/al_data.php',
      data : {
        'keyword' : keyword,
        'page' : page,
        'sortnya': sortnya,
        'valnya' : valnya,
        'tgl_awal':tgl_awal,
        'tgl_akhir':tgl_akhir,
      },
      success : function (data) {

        $('.isian').append(data);
      }
    })
  }
  buku();

  function detail_act() {
    $.ajax({
      type : 'POST',
      url : '<?php echo MWB; ?>korupsi/al_detil.php',
      data : {
        'idnya' : idnya,
        'tgl_awal':tgl_awal,
        'tgl_akhir':tgl_akhir,
      },
      success : function (data) {
        $('.detailnya').empty();
        $('.detailnya').append(data);
      }
    })
  }

  $('.detailnya').on('click','.det_sha',function(e) {
    $('.sha_det').slideToggle();
    paging();
    buku();
  });

  $('.detailnya').on('click','.del_com',function(e) {
    var id_com = $(this).data('id');

    $.ajax({
      type : 'POST',
      url : '<?php echo MWB; ?>korupsi/al_del_com.php',
      data : {
        'id_com' : id_com
      },
      success : function(data) {
        detail_act();
      }
    })
  });
  $('.fill').click(function(e) {
    $('.fil').slideToggle();
  })
</script>
<?php

?>
