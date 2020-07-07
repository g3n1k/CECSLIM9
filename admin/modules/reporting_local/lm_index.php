<?php
/**
 * Collection general report
 * Copyright (C) 2007,2008  Arie Nugraha (dicarve@yahoo.com
 *
 * Copyright (C) 2008 Arie Nugraha (dicarve@yahoo.com)
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
 *
 */

/* Reporting section */


// key to authentication
define('INDEX_AUTH', '1');

if (!defined('SB')) {
    // main system configuration
    require '../../../sysconfig.inc.php';
    // start the session
    require SB.'admin/default/session.inc.php';
}

// IP based access limitation
require LIB.'ip_based_access.inc.php';
do_checkIP('smc');
do_checkIP('smc-reporting');

require SB.'admin/default/session_check.inc.php';
require SIMBIO.'simbio_GUI/table/simbio_table.inc.php';

// privileges checking
$can_read = utility::havePrivilege('reporting', 'r');
$can_write = utility::havePrivilege('reporting', 'w');

if (!$can_read) {
    die('<div class="errorBox">'.__('You don\'t have enough privileges to access this area!').'</div>');
}?>

<?php
/* collection statistic */
$table = new simbio_table();
$table->table_attr = 'align="center" class="border" cellpadding="5" cellspacing="0"';

// total number of titles
$stat_query = $dbs->query('SELECT COUNT(biblio_id) FROM korupsi');
$stat_data = $stat_query->fetch_row();
$collection_stat[__('Total Titles')] = $stat_data[0].' (including titles that still don\'t have items yet)';

// total number of titles
$stat_query = $dbs->query('SELECT DISTINCT korupsi.biblio_id FROM korupsi INNER JOIN item ON korupsi.biblio_id = item.biblio_id');
$stat_data = $stat_query->num_rows;
$collection_stat[__('Total Hits Collection')] = $stat_data.' (only titles that have items)';

// // total number of items
// $stat_query = $dbs->query('SELECT item.item_code FROM item,korupsi WHERE item.biblio_id=korupsi.biblio_id');
// $stat_data = $stat_query->num_rows;
// $collection_stat[__('Total Items/Copies')] = $stat_data;

// // total number of checkout items
// $biblio_id_korupsi = $dbs->query('SELECT biblio_id FROM korupsi');
// $stat_query = $dbs->query('SELECT COUNT(item_id) FROM item AS i
//     LEFT JOIN loan AS l ON i.item_code=l.item_code
//     WHERE is_lent=1 AND is_return=0 AND i.biblio_id IN (SELECT biblio_id FROM korupsi)');
// $stat_data = $stat_query->fetch_row();
// $collection_stat[__('Total Checkout Items')] = $stat_data[0];

// // total number of items in library
// $collection_stat[__('Total Items In Library')] = $collection_stat[__('Total Items/Copies')]-$collection_stat[__('Total Checkout Items')];

// // total titles by GMD/medium
// $stat_query = $dbs->query('SELECT gmd_name, COUNT(biblio_id) AS total_titles
//     FROM `korupsi` AS b
//     INNER JOIN mst_gmd AS gmd ON b.gmd_id = gmd.gmd_id
//     GROUP BY b.gmd_id HAVING total_titles>0 ORDER BY COUNT(biblio_id) DESC');
// $stat_data = '<div class="chartLink"><a class="notAJAX openPopUp" href="'.MWB.'reporting_local/charts_report.php?chart=total_title_gmd" width="700" height="470" title="'.__('Total Titles By Medium/GMD').'">'.__('Show in chart/plot').'</a></div>';
// while ($data = $stat_query->fetch_row()) {
//     $stat_data .= '<strong>'.$data[0].'</strong> : '.$data[1];
//     $stat_data .= ', ';
// }
// $collection_stat[__('Total Titles By Medium/GMD')] = $stat_data;

// // total items by Collection Type
// $stat_query = $dbs->query('SELECT coll_type_name, COUNT(item_id) AS total_items
//     FROM `item` AS i
//     INNER JOIN mst_coll_type AS ct ON i.coll_type_id = ct.coll_type_id
//     WHERE i.biblio_id IN (SELECT biblio_id FROM korupsi)
//     GROUP BY i.coll_type_id
//     HAVING total_items >0
//     ORDER BY COUNT(item_id) DESC');
// $stat_data = '<div class="chartLink"><a class="notAJAX openPopUp" href="'.MWB.'reporting_local/charts_report.php?chart=total_title_colltype" width="700" height="470" title="'.__('Total Items By Collection Type').'">'.__('Show in chart/plot').'</a></div>';
// while ($data = $stat_query->fetch_row()) {
//     $stat_data .= '<strong>'.$data[0].'</strong> : '.$data[1];
//     $stat_data .= ', ';
// }
// $collection_stat[__('Total Items By Collection Type')] = $stat_data;

// popular titles
$stat_query = $dbs->query('SELECT b.title,b.biblio_id AS total_loans FROM `loan` AS l
    LEFT JOIN item AS i ON l.item_code=i.item_code
    LEFT JOIN korupsi AS b ON i.biblio_id=b.biblio_id
    GROUP BY b.biblio_id ORDER BY COUNT(l.loan_id) DESC LIMIT 10');
$stat_data = '<ul>';
while ($data = $stat_query->fetch_row()) {
    $stat_data .= '<li>'.$data[0].'</li>';
}
$stat_data .= '</ul>';
$collection_stat[__('10 Most Popular Titles')] = $stat_data;

// table header
$table->setHeader(array(__('Collection Statistic Summary')));
$table->table_header_attr = 'class="dataListHeader"';
$table->setCellAttr(0, 0, 'colspan="3"');
// initial row count
$row = 1;
foreach ($collection_stat as $headings=>$stat_data) {
    $table->appendTableRow(array($headings, ':', $stat_data));
    // set cell attribute
    $table->setCellAttr($row, 0, 'class="alterCell" valign="top" style="width: 170px;"');
    $table->setCellAttr($row, 1, 'class="alterCell" valign="top" style="width: 1%;"');
    $table->setCellAttr($row, 2, 'class="alterCell2" valign="top" style="width: auto;"');
    // add row count
    $row++;
}

// if we are in print mode
if (isset($_GET['print'])) {
    // html strings
    $html_str = '<!DOCTYPE html>';
    $html_str .= '<html><head><title>'.$sysconf['library_name'].' '.__('Collection Statistic Report').'</title>';
    $html_str .= '<style type="text/css">'."\n";
    $html_str .= 'body {padding: 0.2cm}'."\n";
    $html_str .= 'body * {color: black; font-size: 11pt;}'."\n";
    $html_str .= 'table {border: 1px solid #000000;}'."\n";
    $html_str .= '.dataListHeader {background-color: #000000; color: white; font-weight: bold;}'."\n";
    $html_str .= '.alterCell {border-bottom: 1px solid #666666; background-color: #CCCCCC;}'."\n";
    $html_str .= '.alterCell2 {border-bottom: 1px solid #666666; background-color: #FFFFFF;}'."\n";
    $html_str .= '</style>'."\n";
    $html_str .= '</head>';
    $html_str .= '<body>'."\n";
    $html_str .= '<h3>'.$sysconf['library_name'].' - '.__('Collection Statistic Report').'</h3>';
    $html_str .= '<hr size="1" />';
    $html_str .= $table->printTable();
    $html_str .= '<script type="text/javascript">self.print();</script>'."\n";
    $html_str .= '</body></html>';
    // write to file
    $file_write = @file_put_contents(REPBS.'biblio_stat_print_result.html', $html_str);
    if ($file_write) {
        // open result in new window
        echo '<script type="text/javascript">top.$.colorbox({href: "'.SWB.FLS.'/'.REP.'/biblio_stat_print_result.html", height: 800,  width: 500})</script>';
    } else { utility::jsAlert(str_replace('{directory}', REPBS, __('ERROR! Collection Statistic Report failed to generate, possibly because {directory} directory is not writable'))); }
    exit();
}

?>
<style media="print">
  body * {
    visibility: hidden;
  }
  #lm_lm_lm, #lm_lm_lm *,.tile {
    visibility: visible;
  }
  .tile {
    margin:-70px 0px 0px 0px;
  }
  #lm_lm_lm {
    margin:0px 0px 0px 0px;
  }
</style>
<fieldset class="menuBox">
<div class="menuBoxInner statisticIcon">
	<div class="per_title">
	  <h2><?php echo __('Report Per Member'); ?></h2>
  </div>
	<!-- <div class="infoBox">
    <form name="printForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="submitPrint" id="printForm" class="notAJAX" method="get" style="display: inline;">
    <input type="hidden" name="print" value="true" /><input type="submit" value="<?php echo __('Download Report'); ?>" class="button" />
    </form>
    <iframe name="submitPrint" style="visibility: hidden; width: 0; height: 0;"></iframe>
  </div> -->
</div>
</fieldset>
<div class="contentDesc baca">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-info">
          <div class="panel-body" style="min-height:470px;">
            <section class="lm_indexnya lm">
              <section class="search_lm" style="display: inline;"><?php echo __('Search'); ?>
                <br>
                <input class="lmfind" type="text" name="keywords" size="30" />
                <!-- <select name="field">
                  <option value="0">All Fields</option>
                  <option value="title">Title/Series Title </option>
                  <option value="subject">Topics</option>
                  <option value="author">Authors</option>
                  <option value="isbn">ISBN/ISSN</option>
                  <option value="publisher">Publisher</option>
                </select> -->
                  Perpage
                  <select class='perpage'>
                    <option value='10'>10</option>
                    <option value='20'>20</option>
                    <option value='50'>50</option>
                    <option value='100'>100</option>
                  </select>
                <br>
                <section class="filter" style="display:none;">
                  <div class="divRow">
                    <div class="divRowLabel">
                      <?php echo __('Date Range'); ?>
                    </div>
                    <div class="divRowContent">
                      <div class="dateField">
                        <input class="dateInput awal" type="date" name="startDate" id="startDate" value="2000-01-01">
                        <a class="calendarLink notAJAX" style="cursor: pointer;" onclick="javascript: dateType = 'date'; openCalendar('startDate');" title="Open Calendar"></a>
                      </div>
                      <span style="margin-top:100px;">&nbsp;&nbsp;  &nbsp;&nbsp;</span>
                      <div class="dateField">
                        <input class="dateInput akhir" type="date" name="untilDate" id="untilDate" value="<?php echo date('Y-m-d')?>" >
                        <a class="calendarLink notAJAX" style="cursor: pointer;" onclick="javascript: dateType = 'date'; openCalendar('untilDate');" title="Open Calendar"></a>
                      </div>
                    </div>
                  </div>
                </section>
                <input type="submit" value="<?php echo __('Show More Filter Options'); ?>" class="button btn btn-primary lmfil" />
                <input type="submit" value="<?php echo __('Apply Filter'); ?>" class="button btn btn-primary lmcari" />
              </section>
              
              <hr>
              <div class="biblioPaging pull-right">
                <span class="pagingList lis_lm">
                </span>
              </div>
              <br>
              <hr>
              <section class="indexing">
                <table align="center"  cellpadding="5" cellspacing="0" style="width:100%;">
                  <tr class="dataListHeader" style="font-weight: bold; cursor: pointer; background-color: rgb(49, 53, 62);">
                    <td></td>
                    <td style="cursor:pointer;" class="sort" data-value="name" data-sort="DESC"><?php echo __('Member Name'); ?></td>
                    <td style="cursor:pointer;" class="sort" data-value="instansi" data-sort="DESC"><?php echo __('Department'); ?></td>
                    <td style="cursor:pointer;" class="sort" data-value="jml_loan" data-sort="DESC"><?php echo __('Loan times'); ?></td>
                    <td style="cursor:pointer;" class="sort" data-value="alo" data-sort="DESC"><?php echo __('Active Loan'); ?></td>
                  </tr>
                  <tbody class="is_lm">

                  </tbody>
                </table>
              </section>
            </section>
            <section class="lm_detilnya lm" style="display:none;">
              <button type="button" name="button" class="lm_back button btn btn-primary"><?php echo __('Back to List'); ?></button>
              <button type="button" name="button" class="button btn btn-success" onclick="self.print()"><?php echo __('Print'); ?></button>
              <hr>
              <section id="lm_lm_lm" class="lm_lm_lm">

              </section>
            </section>
			<button class="btn-to-excel btn btn-info btn-sm"><span class="glyphicon glyphicon-save-file"></span> Export To Excel</button>
          </div>
        </div>
      </div>
	</div>
  </div>
</div>
<script type="text/javascript">
  var cari = '';
//  var urut = ' name ASC';
var urut = ' jml_loan DESC';
  var halaman = '1';
  var perpage = '10';
  var sortiran = '';
  var member_id = '';
  var tgl_awal = $('.awal').val();
  var tgl_akhir = $('.akhir').val();

  $('.lmfil').click(function() {
    $('.filter').slideToggle();
  })

  $('.is_lm').on('click','.lm_detil',function(e) {
    $('.lm').slideToggle();
    member_id = $(this).data('id');
    lm_detail();
  })
  $('.lm_detilnya').on('click','.lm_back',function(e) {
    $('.lm').slideToggle();
  })
  $('.search_lm').on('click','.lmcari',function(e) {
    cari = '%'+$('.lmfind').val()+'%';
    halaman = '1';
    tgl_awal = $('.awal').val();
    tgl_akhir = $('.akhir').val();
    perpage = $('.perpage').val();
    lm();
    lmp();
    $('.filter').slideToggle();
    $('.is_lm').empty();
    $('.lis_lm').empty();
  })
  $('.indexing').on('click','.sort',function(e) {
    sortiran = $(this).data('sort');
    urut = $(this).data('value')+' '+$(this).data('sort')+' ';
    $('.sort').data('sort','DESC');
    if (sortiran == 'DESC') {
      $(this).data('sort','ASC');
    };
    lm();
    lmp();
  })
  $('.lis_lm').on('click','.pagingnya',function(e) {
    halaman = $(this).data('page');
    lm();
    lmp();
  })
  lm();
  lmp();
  function lm_detail() {
    $.ajax({
      type : 'POST',
      url : '<?php echo MWB; ?>reporting_local/lm_data_detil.php',
      data :{
        'member_id' : member_id,
        'tgl_awal' : tgl_awal,
        'tgl_akhir' : tgl_akhir,
      },
      success : function(data) {
        $('.lm_lm_lm').empty();
        $('.lm_lm_lm').append(data);
      }
    })
  }
  function lm() {
    $.ajax({
      type : 'POST',
      url : '<?php echo MWB; ?>reporting_local/lm_data_index.php',
      data : {
        'cari' : cari,
        'urut' : urut,
        'tgl_awal' : tgl_awal,
        'tgl_akhir' : tgl_akhir,
        'halaman' : halaman,
        'perpage' : perpage
      },
      success : function(data) {
        $('.is_lm').empty();
        $('.is_lm').append(data);
      }
    })
  }
  function lmp() {
    $.ajax({
      type : 'POST',
      url : '<?php echo MWB; ?>reporting_local/lm_data_paging.php',
      data :{
        'cari' : cari,
        'urut' : urut,
        'halaman' : halaman,
        'perpage' : perpage
      },
      success : function(data) {
        $('.lis_lm').empty();
        $('.lis_lm').append(data);
      }
    })
  }

  $('.perpage').change(function() { 
	perpage = $('.perpage').val();
	lm();
  });

  $('.btn-to-excel').click(function(){

	var url = "<?php echo AWB.'modules/reporting_local/lm_excel.php';?>";
/* */	
	var $iframe,
        iframe_doc,
        iframe_html;

    if (($iframe = $('#download_iframe')).length === 0) {
		var _iframe = "<iframe id='download_iframe'"; 
		_iframe += " style='display: block'";
		_iframe += " src='about:blank'></iframe>";
                   
        $iframe = $(_iframe).appendTo("body");
    }

    iframe_doc = $iframe[0].contentWindow || $iframe[0].contentDocument;
    if (iframe_doc.document) {
        iframe_doc = iframe_doc.document;
	}
	
	iframe_html = "<html><head></head><body><form method='POST' action='" + url +"'>";
	iframe_html += "<input type='hidden' name='cari' value='" +cari+"' />";
	iframe_html += "<input type='hidden' name='urut' value='" +urut+"' />";
	iframe_html += "<input type='hidden' name='tgl_awal' value='" +tgl_awal+"' />";
	iframe_html += "<input type='hidden' name='tgl_akhir' value='" +tgl_akhir+"' />";
	iframe_html += "<input type='hidden' name='halaman' value='" +halaman+"' />";
	iframe_html += "<input type='hidden' name='perpage' value='" +perpage+"' />";
	iframe_html += "</form>" + "</body></html>";
    iframe_doc.open();
    iframe_doc.write(iframe_html);
    $(iframe_doc).find('form').submit();
/* */
  });
</script>
<?php
// echo $table->printTable();
/* collection statistic end */
