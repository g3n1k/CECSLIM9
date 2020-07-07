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


$mem_id = $_POST['biblio_id'];

$tgl_awal = strtotime($_POST['tgl_awal']);
$tgl_akhir = strtotime($_POST['tgl_akhir']);

if ($tgl_awal > $tgl_akhir) {
  $akhir = $_POST['tgl_awal'];
  $awal = $_POST['tgl_akhir'];
} else {
  $awal = $_POST['tgl_awal'];
  $akhir = $_POST['tgl_akhir'];
}

$query = "SELECT title FROM biblio WHERE biblio_id = '$mem_id'";
$data = $dbs->query($query);

$q_item =  "SELECT item_code FROM item WHERE biblio_id = '$mem_id'";
$d_item = $dbs->query($q_item);
$no = 1;
foreach ($d_item as $item) {
  $icode = $item['item_code'];
  $q_loan = "SELECT member.member_id as mid,
              member.member_name as mna,
              loan.loan_date as ld,
              loan.due_date as due,
              loan.renewed as ren,
              loan.is_lent as lent,
              loan.is_return as ret,
              loan.return_date as rda
              FROM loan
              LEFT JOIN member ON loan.member_id = member.member_id
              WHERE loan.item_code = '$icode'
              AND loan_date BETWEEN '$awal' AND '$akhir'
              ORDER BY ld DESC
              ";
  $d_loan = $dbs->query($q_loan);
  $jml_loannya = $d_loan->num_rows;
  if ($no > 1) {
    echo "<br><hr>";
  }
  ?>

    <h2><?php echo __('ITEM CODE'); ?> - <?php echo $item['item_code'];?></h2>
    <hr>
    <?php
    if ($jml_loannya < 1) {
      echo "No Loan Data Yet";
    } else {?>
      <table align="center"  cellpadding="5" cellspacing="0" style="width:100%;">
        <tr class="dataListHeader" style="font-weight: bold; cursor: pointer; background-color: rgb(49, 53, 62);">
          <td><?php echo __('Member ID'); ?></td>
          <td><?php echo __('Member Name'); ?></td>
          <td><?php echo __('Loan Date'); ?></td>
          <td><?php echo __('Due Date'); ?></td>
          <td><?php echo __('Renewed'); ?></td>
          <td><?php echo __('Is Lent?'); ?></td>
          <td><?php echo __('Is Returned?'); ?></td>
          <td><?php echo __('Return Date'); ?></td>
        </tr>
        <?php
        foreach ($d_loan as $loan) { ?>
          <tr>
            <td><?php echo $loan['mid'] ?></td>
            <td><?php echo $loan['mna'] ?></td>
            <td><?php echo $loan['ld'] ?></td>
            <td><?php echo $loan['due'] ?></td>
            <td><?php echo $loan['ren'] ?></td>
            <td>
              <?php
              if ($loan['lent'] == '1') {
                echo "YES";
              }else {
                echo "NO";
              }
                ?>
            </td>
            <td>
              <?php
              if ($loan['ret'] == '1') {
                echo "YES";
              }else {
                echo "NO";
              }
              ?>
            </td>
            <td><?php echo $loan['rda'] ?></td>
          </tr>
        <?php
        }
        ?>
      </table>
    <?php
  }?>
<?php
$no++;
}
?>
