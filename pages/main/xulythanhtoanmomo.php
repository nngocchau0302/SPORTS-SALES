<?php
session_start();
include('../../admincf/config/config.php');
require('../../mail/sendmail.php');
require('../../carbon/autoload.php');
header('Content-type: text/html; charset=utf-8');

use Carbon\Carbon;
$now = Carbon::now('Asia/Ho_Chi_Minh');

function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ));
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

// Kiểm tra session giỏ hàng
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    die("Giỏ hàng trống!");
}

// === Tính lại tổng tiền đơn hàng từ giỏ hàng ===
$amount = 0;
foreach ($_SESSION['cart'] as $val) {
    $amount += $val['giasp'] * $val['soluong'];
}

if ($amount < 1000) {
    die("Số tiền thanh toán phải lớn hơn 1.000 VND để tạo QR MoMo.");
}

// === MoMo thông tin ===
$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
$orderInfo = "Thanh toán qua mã QR MoMo";
$orderId = time() . "";
$redirectUrl = "http://localhost:3000/index.php?quanly=donhangdadat";
$ipnUrl = "http://localhost:3000/index.php?quanly=donhangdadat";
$extraData = "";
$requestId = time() . "";
$requestType = "captureWallet";

// === Đặt hàng ===
$id_khachhang = $_SESSION['id_khachhang'] ?? 0;
$code_order = rand(1000, 9999);

// Lấy địa chỉ giao hàng
$sql_shipping = mysqli_query($mysqli, "SELECT * FROM tbl_shipping WHERE id_dangky = '$id_khachhang' LIMIT 1");
$row_shipping = mysqli_fetch_array($sql_shipping);
$id_shipping = $row_shipping['id_shipping'] ?? 0;

// Thêm đơn hàng
$sql_insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping)
                    VALUES ('$id_khachhang', '$code_order', 1, '$now', 'momo_qr', '$id_shipping')";
mysqli_query($mysqli, $sql_insert_cart);

// Lưu chi tiết đơn hàng + xử lý seri vợt
foreach ($_SESSION['cart'] as $item) {
    $id_sanpham = $item['id'];
    $soluong = $item['soluong'];
    $tensanpham = $item['tensanpham'];

    // Thêm chi tiết đơn hàng
    $sql_ct = "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua)
               VALUES ('$id_sanpham', '$code_order', '$soluong')";
    mysqli_query($mysqli, $sql_ct);

    // Lấy sản phẩm
    $sql_sp = "SELECT * FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1";
    $query_sp = mysqli_query($mysqli, $sql_sp);
    $row_sp = mysqli_fetch_assoc($query_sp);

    // Nếu là vợt cầu lông (id_danhmuc = 1)
    if ($row_sp['id_danhmuc'] == 1) {
        $sql_seri = "SELECT * FROM tbl_seri WHERE id_sanpham = '$id_sanpham' AND tinhtrang = 0 LIMIT $soluong";
        $query_seri = mysqli_query($mysqli, $sql_seri);

        if (mysqli_num_rows($query_seri) < $soluong) {
            die("Không đủ số lượng seri còn hàng cho sản phẩm: $tensanpham");
        }

        while ($row_seri = mysqli_fetch_assoc($query_seri)) {
            $seri = $row_seri['seri'];
            // Lưu vào bảng serial_racket
            $sql_insert_serial = "INSERT INTO tbl_serial_racket(code_cart, id_sanpham, seri)
                                  VALUES ('$code_order', '$id_sanpham', '$seri')";
            mysqli_query($mysqli, $sql_insert_serial);

            // Cập nhật tình trạng seri
            mysqli_query($mysqli, "UPDATE tbl_seri SET tinhtrang = 1 WHERE seri = '$seri'");
        }
    }

    // Cập nhật tồn kho
    $soluongmoi = $row_sp['soluong'] - $soluong;
    $soluongban = $row_sp['soluongban'] + $soluong;
    mysqli_query($mysqli, "UPDATE tbl_sanpham SET soluong = '$soluongmoi', soluongban = '$soluongban' WHERE id_sanpham = '$id_sanpham'");
}

// === Gửi email xác nhận đơn hàng ===
$tieude = "Đặt hàng Website DCSPORT thành công";
$noidung = "
<html><body><div class='container'>
<p>Cảm ơn quý khách đã đặt hàng với mã đơn hàng: <strong>$code_order</strong></p>
<h4>Đơn hàng đã đặt bao gồm:</h4><ul>";

foreach ($_SESSION['cart'] as $val) {
    $noidung .= "<li><strong>Tên sản phẩm:</strong> {$val['tensanpham']}<br>
    <strong>Mã sản phẩm:</strong> {$val['masp']}<br>
    <strong>Giá:</strong> " . number_format($val['giasp'], 0, ',', '.') . " VNĐ<br>
    <strong>Số lượng:</strong> {$val['soluong']}<br>";

    // Seri nếu có
    $id_sanpham = $val['id'];
    $sql_danhmuc = mysqli_query($mysqli, "SELECT id_danhmuc FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1");
    $row_danhmuc = mysqli_fetch_array($sql_danhmuc);
    if ($row_danhmuc && $row_danhmuc['id_danhmuc'] == 1) {
        $sql_seri = mysqli_query($mysqli, "SELECT seri FROM tbl_serial_racket WHERE code_cart = '$code_order' AND id_sanpham = '$id_sanpham'");
        $noidung .= "<strong>Số seri:</strong><ul>";
        while ($row_seri = mysqli_fetch_array($sql_seri)) {
            $noidung .= "<li>" . $row_seri['seri'] . "</li>";
        }
        $noidung .= "</ul>";
    }

    $noidung .= "</li>";
}
$noidung .= "</ul>";
$noidung .= "<p class='total'>Tổng giá trị đơn hàng: " . number_format($amount, 0, ',', '.') . " VNĐ</p>";
$noidung .= "</div></body></html>";

$maildathang = $_SESSION['email'] ?? '';
if (!empty($maildathang)) {
    $mail = new Mailer();
    $mail->dathangmail($tieude, $noidung, $maildathang);
}

// Xóa giỏ hàng
unset($_SESSION['cart']);

// === Gửi yêu cầu tới MoMo ===
$rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
$signature = hash_hmac("sha256", $rawHash, $secretKey);

$data = array(
    'partnerCode' => $partnerCode,
    'partnerName' => "Test",
    "storeId" => "MomoTestStore",
    'requestId' => $requestId,
    'amount' => $amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'lang' => 'vi',
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature
);

$result = execPostRequest("https://test-payment.momo.vn/v2/gateway/api/create", json_encode($data));
$jsonResult = json_decode($result, true);

// Chuyển hướng đến trang thanh toán hoặc báo lỗi
if (isset($jsonResult['payUrl'])) {
    header('Location: ' . $jsonResult['payUrl']);
    exit();
} else {
    echo "Không tạo được QR thanh toán. Thông tin lỗi:<br>";
    echo "<pre>";
    print_r($jsonResult);
    echo "</pre>";
    die();
}
?>
