<?php
require_once __DIR__ . '../../../../vendor/autoload.php';
include('../../config/config.php');

use Mpdf\Mpdf;

// Lấy dữ liệu lọc
$start = $_POST['start_date'] ?? '';
$end = $_POST['end_date'] ?? '';

$where = '';
if ($start && $end) {
    $where = "WHERE DATE(tbl_cart.cart_date) BETWEEN '$start' AND '$end'";
}

// Lấy dữ liệu thống kê
$sql = "
SELECT tbl_sanpham.tensanpham, SUM(tbl_cart_details.soluongmua) AS soluongban
FROM tbl_cart_details
JOIN tbl_sanpham ON tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham
JOIN tbl_cart ON tbl_cart_details.code_cart = tbl_cart.code_cart
$where
GROUP BY tbl_cart_details.id_sanpham
ORDER BY soluongban DESC
LIMIT 10
";

$result = mysqli_query($mysqli, $sql);

// Bắt đầu nội dung HTML cho PDF
$html = '
<style>
    body { font-family: DejaVu Sans; }
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
    .logo {
        width: 100px;
        margin-bottom: 10px;
    }
    h2 {
        font-size: 20px;
        color: #2c3e50;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 14px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #3498db;
        color: white;
    }
    .footer {
        margin-top: 50px;
        font-style: italic;
        font-size: 13px;
        text-align: right;
    }
    .signature {
        margin-top: 40px;
        text-align: right;
    }
</style>

<div class="header">
    <img class="logo"  src="../../../images/logoDC2..png" />
    <h2>BÁO CÁO TOP SẢN PHẨM BÁN CHẠY</h2>
    <strong>Thời gian:</strong> ' . 
    ($start ? date('d/m/Y', strtotime($start)) : 'Tất cả') . ' đến ' . 
    ($end ? date('d/m/Y', strtotime($end)) : 'Tất cả') . '<br>
</div>

<table>
    <tr>
        <th>STT</th>
        <th>Tên sản phẩm</th>
        <th>Số lượng bán</th>
    </tr>';

$stt = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $html .= '
    <tr>
        <td>' . $stt++ . '</td>
        <td>' . $row['tensanpham'] . '</td>
        <td>' . $row['soluongban'] . '</td>
    </tr>';
}

$html .= '</table>

<div class="signature">
    <p>Ngày lập báo cáo: ' . date('d/m/Y') . '</p>
    <p><strong>Người lập báo cáo</strong><br>(Ký tên)</p>
   
</div>


';

// Tạo PDF và hiển thị
$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('baocao_sanpham.pdf', 'I');
?>
