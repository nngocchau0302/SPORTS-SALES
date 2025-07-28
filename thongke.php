<?php 
use Carbon\Carbon;
require('../../carbon/autoload.php');
include('../../admincf/config/config.php');

$subdays = Carbon::now('Asia/Ho_Chi_Minh')->subdays(365)->toDateString();
$now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

if (isset($_POST['thoigian'])) {
    $thoigian = $_POST['thoigian'];

    if ($thoigian == '7ngay') {
        $subdays = Carbon::now('Asia/Ho_Chi_Minh')->subdays(7)->toDateString();
    } elseif ($thoigian == '28ngay') {
        $subdays = Carbon::now('Asia/Ho_Chi_Minh')->subdays(28)->toDateString();
    } elseif ($thoigian == '90ngay') {
        $subdays = Carbon::now('Asia/Ho_Chi_Minh')->subdays(90)->toDateString();
    } elseif ($thoigian == '365ngay') {
        $subdays = Carbon::now('Asia/Ho_Chi_Minh')->subdays(365)->toDateString();
    }
}

$sql = "SELECT * FROM tbl_thongke WHERE ngaydat BETWEEN '$subdays' AND '$now' ORDER BY ngaydat ASC";
$sql_query = mysqli_query($mysqli, $sql);

$char_data = array();
if (mysqli_num_rows($sql_query) > 0) {
while ($val = mysqli_fetch_array($sql_query)) {
    $char_data[] = array( 
        'date' => $val['ngaydat'],
        'order' => $val['donhang'],
        'sales' => $val['doanhthu'],
        'quantity' => $val['soluongban'],
    );
}
}else{
    echo json_encode([]);
    exit();
}

echo json_encode($char_data);
?>