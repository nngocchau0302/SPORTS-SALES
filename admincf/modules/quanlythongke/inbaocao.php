<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '../../../../vendor/autoload.php';
use Mpdf\Mpdf;

$type = $_POST['filter_type'] ?? 'day';
$start = $_POST['start_date'] ?? '';
$end = $_POST['end_date'] ?? '';

$where = '';
$group_by = '';
$date_format = '';

if ($start && $end) {
    $where = "WHERE DATE(cart_date) BETWEEN '$start' AND '$end' AND tbl_cart.cart_status = 1";
} else {
    $where = "WHERE tbl_cart.cart_status = 1";
}

// Chọn định dạng ngày tháng phù hợp theo loại lọc
switch ($type) {
    case 'month':
        $date_format = '%Y-%m'; // Giữ định dạng chuẩn trong DB
        $group_by = "GROUP BY DATE_FORMAT(cart_date, '$date_format')";
        break;
    case 'year':
        $date_format = '%Y';
        $group_by = "GROUP BY DATE_FORMAT(cart_date, '$date_format')";
        break;
    default:
        $date_format = '%Y-%m-%d';
        $group_by = "GROUP BY DATE_FORMAT(cart_date, '$date_format')";
        break;
}

$sql = "
SELECT 
    DATE_FORMAT(cart_date, '$date_format') AS thoigian,
    SUM(tbl_cart_details.soluongmua) AS tongsoluong,
    SUM(tbl_cart_details.soluongmua * tbl_sanpham.giasp) AS tongdoanhthu
FROM tbl_cart_details
JOIN tbl_sanpham ON tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham
JOIN tbl_cart ON tbl_cart_details.code_cart = tbl_cart.code_cart
$where
$group_by
ORDER BY thoigian ASC
";

$result = $mysqli->query($sql);

// Tạo HTML in ra PDF
$total_all = 0;
$str_filter = ($type == 'day') ? 'Ngày' : (($type == 'month') ? 'Tháng' : 'Năm');

$html = '
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
    .logo {
        width: 100px;
    }
    h2 {
        color: #2c3e50;
        margin: 10px 0;
        font-size: 22px;
    }
    .info {
        text-align: center;
        margin-bottom: 20px;
        font-size: 13px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }
    th {
        background-color: #3498db;
        color: white;
        padding: 8px;
        text-align: center;
        font-size: 14px;
    }
    td {
        padding: 8px;
        border: 1px solid #ccc;
        text-align: center;
    }
    tr:nth-child(even) {
        background-color: #ecf0f1;
    }
    .summary {
        text-align: right;
        margin-top: 20px;
        font-weight: bold;
        font-size: 16px;
    }
    .footer {
        margin-top: 40px;
        text-align: right;
        font-style: italic;
        font-size: 13px;
    }
</style>

<div class="header">
    <img class="logo" src="../../../images/logoDC2..png" />
    <h2>BÁO CÁO DOANH THU</h2>
</div>

<div class="info">
    <strong>Thời gian:</strong> ' . 
    ($start ? date('d/m/Y', strtotime($start)) : 'Tất cả') . ' đến ' . 
    ($end ? date('d/m/Y', strtotime($end)) : 'Tất cả') . '<br>
    <strong>Lọc theo:</strong> ' . $str_filter . '
</div>

<table>
    <tr>
        <th>Thời gian</th>
        <th>Số lượng bán</th>
        <th>Doanh thu (VNĐ)</th>
    </tr>';

while ($row = $result->fetch_assoc()) {
    $thoigian_hienthi = '';

    if ($type == 'day') {
        $thoigian_hienthi = date('d/m/Y', strtotime($row['thoigian']));
    } elseif ($type == 'month') {
        $thoigian_hienthi = date('m/Y', strtotime($row['thoigian'] . '-01'));
    } else {
        $thoigian_hienthi = $row['thoigian'];
    }

    $html .= '<tr>
        <td>' . $thoigian_hienthi . '</td>
        <td>' . $row['tongsoluong'] . '</td>
        <td>' . number_format($row['tongdoanhthu'], 0, ',', '.') . '</td>
    </tr>';

    $total_all += $row['tongdoanhthu'];
}

$html .= '
</table>

<p class="summary">Tổng doanh thu: ' . number_format($total_all, 0, ',', '.') . ' VNĐ</p>

<div class="footer">
    <p>Ngày lập báo cáo: ' . date('d/m/Y') . '</p>
    <p><strong>Người lập báo cáo</strong><br>(Ký tên)</p>
</div>';

// Tạo file PDF
$mpdf = new Mpdf([
    'default_font' => 'dejavusans',
    'format' => 'A4',
]);

$mpdf->WriteHTML($html);
$mpdf->Output('baocao-doanhthu.pdf', 'I'); // Hiển thị ngay trên trình duyệt
