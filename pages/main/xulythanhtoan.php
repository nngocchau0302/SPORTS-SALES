<?php
session_start();
include('../../admincf/config/config.php');
require('../../mail/sendmail.php');
require('../../carbon/autoload.php');
require_once('config_vnpay.php');
header('Content-type: text/html; charset=utf-8');


use Carbon\Carbon;

$now = Carbon::now('Asia/Ho_Chi_Minh');

// Danh sách loại sản phẩm không có size
$khong_co_size = [1, 3, 4]; // vợt, túi, balo

$id_khachhang = isset($_SESSION['id_khachhang']) ? $_SESSION['id_khachhang'] : 0; // Kiểm tra xem ID khách hàng có trong session không
$code_order = rand(1000, 9999); // Để tránh mã đơn hàng là 0
if (!isset($_POST['payment'])) {
    die("Không có thông tin thanh toán.");
}
$cart_payment = $_POST['payment'];
$payment = $cart_payment;
// Lấy ID thông tin vận chuyển
$id_dangky = $id_khachhang;
$sql_get_vanchuyen = mysqli_query($mysqli, "SELECT * FROM tbl_shipping WHERE id_dangky = '$id_dangky' LIMIT 1");

if (!$sql_get_vanchuyen) {
    die("Lỗi truy vấn vận chuyển: " . mysqli_error($mysqli));
}

$row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);
$id_shipping = $row_get_vanchuyen['id_shipping'] ?? null; // Sử dụng null coalescing để tránh lỗi nếu không tìm thấy
$tongtien = 0;

foreach ($_SESSION['cart'] as $key => $value) {
    $thanhtien =  $value['soluong'] * $value['giasp'];
    $tongtien += $thanhtien;
}

if ($payment == 'tienmat' || $payment == 'chuyenkhoan') {
    if ($id_shipping) {
        // Insert đơn hàng
        $insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping) 
                        VALUES ('$id_khachhang', '$code_order', 1, '$now', '$cart_payment', '$id_shipping')";
        if (!mysqli_query($mysqli, $insert_cart)) die("Lỗi thêm đơn hàng: " . mysqli_error($mysqli));

        foreach ($_SESSION['cart'] as $value) {
            $id_sanpham = $value['id'];
            $soluong = $value['soluong'];
            $size = $value['size'];
            $tensanpham = $value['tensanpham'];

            mysqli_query($mysqli, "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua, size) 
                                    VALUES ('$id_sanpham', '$code_order', '$soluong','$size')");
            $sql_check = "SELECT * FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1";
            $row_product = mysqli_fetch_assoc(mysqli_query($mysqli, $sql_check));

            if ($row_product['id_danhmuc'] == 1) {
                $query_seri = mysqli_query($mysqli, "SELECT * FROM tbl_seri WHERE id_sanpham = '$id_sanpham' AND tinhtrang = 0 LIMIT $soluong");
                $seri_count = mysqli_num_rows($query_seri);
                if ($seri_count < $soluong) die("Không đủ seri cho '$tensanpham'.");
                while ($row_seri = mysqli_fetch_assoc($query_seri)) {
                    $seri = $row_seri['seri'];
                    mysqli_query($mysqli, "INSERT INTO tbl_serial_racket(code_cart, id_sanpham, seri) VALUES ('$code_order', '$id_sanpham', '$seri')");
                    mysqli_query($mysqli, "UPDATE tbl_seri SET tinhtrang = 1 WHERE seri = '$seri' AND id_sanpham = '$id_sanpham'");
                }
                $soluongcon = $row_product['soluong'] - $seri_count;
                $soluongbanra = $row_product['soluongban'] + $seri_count;
            } else {
                if (!in_array($row_product['id_danhmuc'], $khong_co_size) && !empty($size)) {
                    $sql_kichthuoc = "SELECT soluong FROM tbl_kichthuoc WHERE id_sanpham = '$id_sanpham' AND size = '$size' LIMIT 1";
                    $row_kichthuoc = mysqli_fetch_assoc(mysqli_query($mysqli, $sql_kichthuoc));
                    if (!$row_kichthuoc || $row_kichthuoc['soluong'] < $soluong)
                        die("Không đủ kho size '$size' cho '$tensanpham'.");
                    $soluong_size_con = $row_kichthuoc['soluong'] - $soluong;
                    mysqli_query($mysqli, "UPDATE tbl_kichthuoc SET soluong = '$soluong_size_con' WHERE id_sanpham = '$id_sanpham' AND size = '$size'");
                }
                $soluongcon = $row_product['soluong'] - $soluong;
                $soluongbanra = $row_product['soluongban'] + $soluong;
            }
            mysqli_query($mysqli, "UPDATE tbl_sanpham SET soluong = '$soluongcon', soluongban = '$soluongbanra' WHERE id_sanpham = '$id_sanpham'");
        }
    }
    // Gửi email xác nhận đơn hàng
    $tieude = "Đặt hàng Website DCSPORT thành công";
    $noidung = "
     <html>
     <head>
         <style>
             body {
                 font-family: Arial, sans-serif;
                 background-color: #f4f4f4;
                 margin: 0;
                 padding: 20px;
             }
             .container {
                 background-color: #ffffff;
                 padding: 20px;
                 border-radius: 5px;
                 box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
             }
             h4 {
                 color: #333;
             }
             ul {
                 list-style-type: none;
                 padding: 0;
             }
             li {
                 border-bottom: 1px solid #ddd;
                 padding: 10px 0;
             }
             .total {
                 font-weight: bold;
                 margin-top: 20px;
             }
         </style>
     </head>
     <body>
         <div class='container'>
             <p>Cảm ơn quý khách đã đặt hàng của chúng tôi với mã đơn hàng là: <strong>$code_order</strong></p>
             <p>Vui lòng chú ý điện thoại, đơn hàng sẽ sớm được giao đến bạn. ❤️ </p>
             <h4>Đơn hàng đã đặt bao gồm:</h4>
             <ul>";

    foreach ($_SESSION['cart'] as $key => $val) {
        $noidung .= "<li>
        <strong>Tên sản phẩm:</strong> " . $val['tensanpham'] . "<br>
        <strong>Mã sản phẩm:</strong> " . $val['masp'] . "<br>
        <strong>Giá:</strong> " . number_format($val['giasp'], 0, ',', '.') . " VNĐ<br>
        <strong>Số lượng:</strong> " . $val['soluong'] . "<br>";

        // Nếu là vợt cầu lông (id_danhmuc = 1) thì truy vấn seri
        $id_sanpham = $val['id'];
        $sql_danhmuc = mysqli_query($mysqli, "SELECT id_danhmuc FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1");
        $row_danhmuc = mysqli_fetch_array($sql_danhmuc);
        if ($row_danhmuc && $row_danhmuc['id_danhmuc'] == 1) {
            $sql_seri = mysqli_query($mysqli, "SELECT seri FROM tbl_serial_racket WHERE code_cart = '$code_order' AND id_sanpham = '$id_sanpham'");
            $noidung .= "<strong>Số seri vợt:</strong><ul>";
            while ($row_seri = mysqli_fetch_array($sql_seri)) {
                $noidung .= "<li>" . $row_seri['seri'] . "</li>";
            }
            $noidung .= "</ul>";
        }

        $noidung .= "</li>";
    }
    $noidung .= "</ul>";



    // Tính tổng giá trị đơn hàng
    $total = 0;
    foreach ($_SESSION['cart'] as $val) {
        $total += $val['giasp'] * $val['soluong'];
    }
    // Lấy phí vận chuyển
    $sql_shipping = mysqli_query($mysqli, "SELECT s.shipping_fee 
    FROM tbl_cart AS c 
    JOIN tbl_shipping AS s ON c.cart_shipping = s.id_shipping 
    WHERE c.code_cart = '$code_order' 
    LIMIT 1");
    $row_shipping = mysqli_fetch_array($sql_shipping);
    $shipping_fee = isset($row_shipping['shipping_fee']) ? (int)$row_shipping['shipping_fee'] : 0;

    $total_with_shipping = $total + $shipping_fee;

    // Hiển thị phí và tổng tiền trong email
    $noidung .= "<p class='total'>Phí vận chuyển: " . number_format($shipping_fee, 0, ',', '.') . " VNĐ</p>";
    $noidung .= "<p class='total'>Tổng thanh toán: " . number_format($total_with_shipping, 0, ',', '.') . " VNĐ</p>";

    // $noidung .= "<p class='total'>Tổng giá trị đơn hàng: " . number_format($total, 0, ',', '.') . " VNĐ</p>";

    $noidung .= "</div></body></html>";

    $maildathang = isset($_SESSION['email']) ? $_SESSION['email'] : '';

    if (!empty($maildathang)) {
        $mail = new Mailer();
        $mail->dathangmail($tieude, $noidung, $maildathang);
    }
    //  unset($_SESSION['cart']);

    //  exit();
    unset($_SESSION['cart']);
    echo "<script>alert('Thanh toán thành công!'); window.location.href='http://localhost:3000/index.php?quanly=donhangdadat';</script>";
}
//========== THANH TOÁN VNPAY ==========
elseif ($cart_payment == 'vnpay') {
      // Xử lý tổng tiền + phí ship
    $total = 0;
    foreach ($_SESSION['cart'] as $val) {
        $total += $val['giasp'] * $val['soluong'];
    }

    // Lấy phí vận chuyển
    $sql_shipping = mysqli_query($mysqli, "SELECT shipping_fee FROM tbl_shipping WHERE id_shipping = '$id_shipping' LIMIT 1");
    $row_shipping = mysqli_fetch_array($sql_shipping);
    $shipping_fee = isset($row_shipping['shipping_fee']) ? (int)$row_shipping['shipping_fee'] : 0;

    $tongtien = $total + $shipping_fee;

    //Tích hợp VNPay
    $vnp_TxnRef = $code_order; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
    $vnp_OrderInfo = 'Thanh toán đơn hàng đặt tại web';
    $vnp_OrderType = 'billpayment';
    $vnp_Amount = $tongtien * 100;
    $vnp_Locale = 'vn';
    $vnp_BankCode = 'NCB';
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
    //Add Params of 2.0.1 Version
    $vnp_ExpireDate = $expire;

    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
        "vnp_ExpireDate" => $vnp_ExpireDate

    );

    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
        $inputData['vnp_BankCode'] = $vnp_BankCode;
    }
    // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
    //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
    // }

    //var_dump($inputData);
    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
        $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }
    $returnData = array(
        'code' => '00',
        'message' => 'success',
        'data' => $vnp_Url
    );
    if (isset($_POST['redirect'])) {
        $_SESSION['code_cart'] = $code_order;

        if ($id_shipping) {
            // Insert đơn hàng
            $insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping) 
                        VALUES ('$id_khachhang', '$code_order', 1, '$now', '$cart_payment', '$id_shipping')";
            if (!mysqli_query($mysqli, $insert_cart)) die("Lỗi thêm đơn hàng: " . mysqli_error($mysqli));

            foreach ($_SESSION['cart'] as $value) {
                $id_sanpham = $value['id'];
                $soluong = $value['soluong'];
                $size = $value['size'];
                $tensanpham = $value['tensanpham'];

                mysqli_query($mysqli, "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua, size) 
                                    VALUES ('$id_sanpham', '$code_order', '$soluong','$size')");
                $sql_check = "SELECT * FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1";
                $row_product = mysqli_fetch_assoc(mysqli_query($mysqli, $sql_check));

                if ($row_product['id_danhmuc'] == 1) {
                    $query_seri = mysqli_query($mysqli, "SELECT * FROM tbl_seri WHERE id_sanpham = '$id_sanpham' AND tinhtrang = 0 LIMIT $soluong");
                    $seri_count = mysqli_num_rows($query_seri);
                    if ($seri_count < $soluong) die("Không đủ seri cho '$tensanpham'.");
                    while ($row_seri = mysqli_fetch_assoc($query_seri)) {
                        $seri = $row_seri['seri'];
                        mysqli_query($mysqli, "INSERT INTO tbl_serial_racket(code_cart, id_sanpham, seri) VALUES ('$code_order', '$id_sanpham', '$seri')");
                        mysqli_query($mysqli, "UPDATE tbl_seri SET tinhtrang = 1 WHERE seri = '$seri' AND id_sanpham = '$id_sanpham'");
                    }
                    $soluongcon = $row_product['soluong'] - $seri_count;
                    $soluongbanra = $row_product['soluongban'] + $seri_count;
                } else {
                    if (!in_array($row_product['id_danhmuc'], $khong_co_size) && !empty($size)) {
                        $sql_kichthuoc = "SELECT soluong FROM tbl_kichthuoc WHERE id_sanpham = '$id_sanpham' AND size = '$size' LIMIT 1";
                        $row_kichthuoc = mysqli_fetch_assoc(mysqli_query($mysqli, $sql_kichthuoc));
                        if (!$row_kichthuoc || $row_kichthuoc['soluong'] < $soluong)
                            die("Không đủ kho size '$size' cho '$tensanpham'.");
                        $soluong_size_con = $row_kichthuoc['soluong'] - $soluong;
                        mysqli_query($mysqli, "UPDATE tbl_kichthuoc SET soluong = '$soluong_size_con' WHERE id_sanpham = '$id_sanpham' AND size = '$size'");
                    }
                    $soluongcon = $row_product['soluong'] - $soluong;
                    $soluongbanra = $row_product['soluongban'] + $soluong;
                }
                mysqli_query($mysqli, "UPDATE tbl_sanpham SET soluong = '$soluongcon', soluongban = '$soluongbanra' WHERE id_sanpham = '$id_sanpham'");
            }
        }

        // Gửi email xác nhận đơn hàng
        $tieude = "Đặt hàng Website DCSPORT thành công";
        $noidung = "
     <html>
     <head>
         <style>
             body {
                 font-family: Arial, sans-serif;
                 background-color: #f4f4f4;
                 margin: 0;
                 padding: 20px;
             }
             .container {
                 background-color: #ffffff;
                 padding: 20px;
                 border-radius: 5px;
                 box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
             }
             h4 {
                 color: #333;
             }
             ul {
                 list-style-type: none;
                 padding: 0;
             }
             li {
                 border-bottom: 1px solid #ddd;
                 padding: 10px 0;
             }
             .total {
                 font-weight: bold;
                 margin-top: 20px;
             }
         </style>
     </head>
     <body>
         <div class='container'>
             <p>Cảm ơn quý khách đã đặt hàng của chúng tôi với mã đơn hàng là: <strong>$code_order</strong></p>
             <p>Vui lòng chú ý điện thoại, đơn hàng sẽ sớm được giao đến bạn. ❤️ </p>
             <h4>Đơn hàng đã đặt bao gồm:</h4>
             <ul>";

        foreach ($_SESSION['cart'] as $key => $val) {
            $noidung .= "<li>
        <strong>Tên sản phẩm:</strong> " . $val['tensanpham'] . "<br>
        <strong>Mã sản phẩm:</strong> " . $val['masp'] . "<br>
        <strong>Giá:</strong> " . number_format($val['giasp'], 0, ',', '.') . " VNĐ<br>
        <strong>Số lượng:</strong> " . $val['soluong'] . "<br>";

            // Nếu là vợt cầu lông (id_danhmuc = 1) thì truy vấn seri
            $id_sanpham = $val['id'];
            $sql_danhmuc = mysqli_query($mysqli, "SELECT id_danhmuc FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1");
            $row_danhmuc = mysqli_fetch_array($sql_danhmuc);
            if ($row_danhmuc && $row_danhmuc['id_danhmuc'] == 1) {
                $sql_seri = mysqli_query($mysqli, "SELECT seri FROM tbl_serial_racket WHERE code_cart = '$code_order' AND id_sanpham = '$id_sanpham'");
                $noidung .= "<strong>Số seri vợt:</strong><ul>";
                while ($row_seri = mysqli_fetch_array($sql_seri)) {
                    $noidung .= "<li>" . $row_seri['seri'] . "</li>";
                }
                $noidung .= "</ul>";
            }

            $noidung .= "</li>";
        }
        //  $noidung .= "</ul>";

        // Tính tổng giá trị đơn hàng
        $total = 0;
        foreach ($_SESSION['cart'] as $val) {
            $total += $val['giasp'] * $val['soluong'];
        }

        // Lấy phí vận chuyển
        $sql_shipping = mysqli_query($mysqli, "SELECT s.shipping_fee 
    FROM tbl_cart AS c 
    JOIN tbl_shipping AS s ON c.cart_shipping = s.id_shipping 
    WHERE c.code_cart = '$code_order' 
    LIMIT 1");
        $row_shipping = mysqli_fetch_array($sql_shipping);
        $shipping_fee = isset($row_shipping['shipping_fee']) ? (int)$row_shipping['shipping_fee'] : 0;

        $total_with_shipping = $total + $shipping_fee;

        // Hiển thị phí và tổng tiền trong email
        $noidung .= "<p class='total'>Phí vận chuyển: " . number_format($shipping_fee, 0, ',', '.') . " VNĐ</p>";
        $noidung .= "<p class='total'>Tổng thanh toán: " . number_format($total_with_shipping, 0, ',', '.') . " VNĐ</p>";



        // $noidung .= "<p class='total'>Tổng giá trị đơn hàng: " . number_format($total, 0, ',', '.') . " VNĐ</p>";





        $noidung .= "</div></body></html>";

        $maildathang = isset($_SESSION['email']) ? $_SESSION['email'] : '';

        if (!empty($maildathang)) {
            $mail = new Mailer();
            $mail->dathangmail($tieude, $noidung, $maildathang);
        }
        unset($_SESSION['cart']);
        header('Location: ' . $vnp_Url);
        die();
    } else {
        echo json_encode($returnData);
    }
}

//========= THANH TOÁN MOMO-ATM ========
elseif ($cart_payment == 'momo_atm') {
    //Thanh toán momo-atm
    // Hàm gửi POST
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

    // === Tính tổng tiền
    $amount = 0;
    foreach ($_SESSION['cart'] as $val) {
        $amount += $val['giasp'] * $val['soluong'];
    }

    if ($amount < 10000) {
        die("Số tiền phải lớn hơn 10.000 VND để thanh toán qua MoMo.");
    }

    // === MoMo thông tin ===
    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    $partnerCode = 'MOMOBKUN20180529';
    $accessKey = 'klm05TvNBzhg7h7j';
    $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    $orderInfo = "Thanh toán qua MoMo ATM";
    $orderId = time() . "";
    $redirectUrl = "http://localhost:3000/index.php?quanly=donhangdadat";
    $ipnUrl = "http://localhost:3000/index.php?quanly=donhangdadat";
    $extraData = "";
    $requestId = time() . "";
    $requestType = "payWithATM";

    // === Tạo chữ ký
    $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
    $signature = hash_hmac("sha256", $rawHash, $secretKey);

    // === Dữ liệu gửi MoMo
    $data = array(
        'partnerCode' => $partnerCode,
        'partnerName' => "Test",
        'storeId' => "MomoTestStore",
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

    // === Gửi yêu cầu sang MoMo
    $result = execPostRequest($endpoint, json_encode($data));
    $jsonResult = json_decode($result, true);

    if (isset($jsonResult['payUrl'])) {
        // Lưu dữ liệu đơn hàng vào DB (sau khi đã có payUrl)
        $id_khachhang = $_SESSION['id_khachhang'];
        $code_order = rand(1000, 9999);

        $sql_shipping = mysqli_query($mysqli, "SELECT * FROM tbl_shipping WHERE id_dangky = '$id_khachhang' LIMIT 1");
        $row_shipping = mysqli_fetch_array($sql_shipping);
        $id_shipping = $row_shipping['id_shipping'] ?? 0;

        $sql_insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping)
                        VALUES ('$id_khachhang', '$code_order', 1, '$now', 'momo', '$id_shipping')";
        mysqli_query($mysqli, $sql_insert_cart);

        // Thêm chi tiết đơn hàng
        foreach ($_SESSION['cart'] as $item) {
            $id_sanpham = $item['id'];
            $soluong = $item['soluong'];
            $size = $item['size'];
            $tensanpham = $item['tensanpham'];

            mysqli_query($mysqli, "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua, size) 
                                        VALUES ('$id_sanpham', '$code_order', '$soluong','$size')");

            $sql_check = "SELECT * FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1";
            $row_product = mysqli_fetch_assoc(mysqli_query($mysqli, $sql_check));

            if ($row_product['id_danhmuc'] == 1) {
                $query_seri = mysqli_query($mysqli, "SELECT * FROM tbl_seri WHERE id_sanpham = '$id_sanpham' AND tinhtrang = 0 LIMIT $soluong");
                if (mysqli_num_rows($query_seri) < $soluong) die("Không đủ seri cho '$tensanpham'.");
                while ($row_seri = mysqli_fetch_assoc($query_seri)) {
                    $seri = $row_seri['seri'];
                    mysqli_query($mysqli, "INSERT INTO tbl_serial_racket(code_cart, id_sanpham, seri) VALUES ('$code_order', '$id_sanpham', '$seri')");
                    mysqli_query($mysqli, "UPDATE tbl_seri SET tinhtrang = 1 WHERE seri = '$seri' AND id_sanpham = '$id_sanpham'");
                }
                $soluongcon = $row_product['soluong'] - $soluong;
                $soluongbanra = $row_product['soluongban'] + $soluong;
            } else {
                if (!empty($size)) {
                    $sql_kichthuoc = "SELECT soluong FROM tbl_kichthuoc WHERE id_sanpham = '$id_sanpham' AND size = '$size' LIMIT 1";
                    $row_kichthuoc = mysqli_fetch_assoc(mysqli_query($mysqli, $sql_kichthuoc));
                    if (!$row_kichthuoc || $row_kichthuoc['soluong'] < $soluong)
                        die("Không đủ kho size '$size' cho '$tensanpham'.");
                    $soluong_size_con = $row_kichthuoc['soluong'] - $soluong;
                    mysqli_query($mysqli, "UPDATE tbl_kichthuoc SET soluong = '$soluong_size_con' WHERE id_sanpham = '$id_sanpham' AND size = '$size'");
                }
                $soluongcon = $row_product['soluong'] - $soluong;
                $soluongbanra = $row_product['soluongban'] + $soluong;
            }

            mysqli_query($mysqli, "UPDATE tbl_sanpham SET soluong = '$soluongcon', soluongban = '$soluongbanra' WHERE id_sanpham = '$id_sanpham'");
        }

        // Xoá giỏ hàng trước khi chuyển
        // Lưu giỏ hàng tạm để dùng cho nội dung mail
        $cart_temp = $_SESSION['cart'];
        unset($_SESSION['cart']);


        // Cuối cùng: chuyển đến trang thanh toán MoMo
        header('Location: ' . $jsonResult['payUrl']);
    } else {
        echo "Không tạo được URL thanh toán MoMo.";
        echo "<pre>";
        print_r($jsonResult);
        echo "</pre>";
        exit();
    }


    // Gửi email xác nhận đơn hàng
    $tieude = "Đặt hàng Website DCSPORT thành công";
    $noidung = "
     <html>
     <head>
         <style>
             body {
                 font-family: Arial, sans-serif;
                 background-color: #f4f4f4;
                 margin: 0;
                 padding: 20px;
             }
             .container {
                 background-color: #ffffff;
                 padding: 20px;
                 border-radius: 5px;
                 box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
             }
             h4 {
                 color: #333;
             }
             ul {
                 list-style-type: none;
                 padding: 0;
             }
             li {
                 border-bottom: 1px solid #ddd;
                 padding: 10px 0;
             }
             .total {
                 font-weight: bold;
                 margin-top: 20px;
             }
         </style>
     </head>
     <body>
         <div class='container'>
             <p>Cảm ơn quý khách đã đặt hàng của chúng tôi với mã đơn hàng là: <strong>$code_order</strong></p>
             <p>Vui lòng chú ý điện thoại, đơn hàng sẽ sớm được giao đến bạn. ❤️ </p>
             <h4>Đơn hàng đã đặt bao gồm:</h4>
             <ul>";

    foreach ($cart_temp as $key => $val) {
        $noidung .= "<li>
        <strong>Tên sản phẩm:</strong> " . $val['tensanpham'] . "<br>
        <strong>Mã sản phẩm:</strong> " . $val['masp'] . "<br>
        <strong>Giá:</strong> " . number_format($val['giasp'], 0, ',', '.') . " VNĐ<br>
        <strong>Số lượng:</strong> " . $val['soluong'] . "<br>";

        // Nếu là vợt cầu lông (id_danhmuc = 1) thì truy vấn seri
        $id_sanpham = $val['id'];
        $sql_danhmuc = mysqli_query($mysqli, "SELECT id_danhmuc FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1");
        $row_danhmuc = mysqli_fetch_array($sql_danhmuc);
        if ($row_danhmuc && $row_danhmuc['id_danhmuc'] == 1) {
            $sql_seri = mysqli_query($mysqli, "SELECT seri FROM tbl_serial_racket WHERE code_cart = '$code_order' AND id_sanpham = '$id_sanpham'");
            $noidung .= "<strong>Số seri vợt:</strong><ul>";
            while ($row_seri = mysqli_fetch_array($sql_seri)) {
                $noidung .= "<li>" . $row_seri['seri'] . "</li>";
            }
            $noidung .= "</ul>";
        }

        $noidung .= "</li>";
    }
    $noidung .= "</ul>";



    // Tính tổng giá trị đơn hàng
    $total = 0;
    foreach ($cart_temp as $val) {
        $total += $val['giasp'] * $val['soluong'];
    }
    $total = $amount; // để sử dụng cho nội dung email
    $amount = 0;
    foreach ($_SESSION['cart'] as $val) {
        $amount += $val['giasp'] * $val['soluong'];
    }

    // Lấy phí vận chuyển từ tbl_cart -> tbl_shipping
    $sql_shipping = mysqli_query($mysqli, "SELECT s.shipping_fee 
    FROM tbl_cart AS c 
    JOIN tbl_shipping AS s ON c.cart_shipping = s.id_shipping 
    WHERE c.code_cart = '$code_order' 
    LIMIT 1");
    $row_shipping = mysqli_fetch_array($sql_shipping);
    $shipping_fee = isset($row_shipping['shipping_fee']) ? (int)$row_shipping['shipping_fee'] : 0;

    $total_with_shipping = $total + $shipping_fee;

    // Hiển thị tổng tiền trong mail
    // $noidung .= "<p class='total'>Tạm tính: " . number_format($total, 0, ',', '.') . " VNĐ</p>";
    $noidung .= "<p class='total'>Phí vận chuyển: " . number_format($shipping_fee, 0, ',', '.') . " VNĐ</p>";
    $noidung .= "<p class='total'>Tổng thanh toán: " . number_format($total_with_shipping, 0, ',', '.') . " VNĐ</p>";
    // $noidung .= "<p class='total'>Tổng giá trị đơn hàng: " . number_format($total, 0, ',', '.') . " VNĐ</p>";

    $noidung .= "</div></body></html>";

    $maildathang = isset($_SESSION['email']) ? $_SESSION['email'] : '';

    if (!empty($maildathang)) {
        $mail = new Mailer();
        $mail->dathangmail($tieude, $noidung, $maildathang);
    }
    //  unset($_SESSION['cart']);

    //  exit();
    unset($_SESSION['cart']);
    echo "<script>alert('Thanh toán thành công!'); window.location.href='http://localhost:3000/index.php?quanly=donhangdadat';</script>";

    // Xóa giỏ hàng
    unset($_SESSION['cart']);
    exit();
    // // Gửi đến MoMo
    // $data = array(
    //     'partnerCode' => $partnerCode,
    //     'partnerName' => "Test",
    //     'storeId' => "MomoTestStore",
    //     'requestId' => $requestId,
    //     'amount' => $amount,
    //     'orderId' => $orderId,
    //     'orderInfo' => $orderInfo,
    //     'redirectUrl' => $redirectUrl,
    //     'ipnUrl' => $ipnUrl,
    //     'lang' => 'vi',
    //     'extraData' => $extraData,
    //     'requestType' => $requestType,
    //     'signature' => $signature
    // );
    // $result = execPostRequest($endpoint, json_encode($data));
    // $jsonResult = json_decode($result, true);

    // // Kiểm tra và chuyển hướng
    // if (isset($jsonResult['payUrl'])) {
    //     unset($_SESSION['cart']); // Xoá giỏ hàng
    //     header('Location: ' . $jsonResult['payUrl']);
    //     exit();
    // } else {
    //     echo "Lỗi khi tạo URL thanh toán MoMo:";
    //     echo "<pre>";
    //     print_r($jsonResult);
    //     echo "</pre>";
    //     die();
    // }
}
//========== THANH TOÁN MOMO-QR ==========  
elseif ($cart_payment == 'momo_qr') {
    //Thanh toán MomoQR
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

    $total = $amount; // để sử dụng cho nội dung email


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
        $id_sanpham = $value['id'];
        $soluong = $value['soluong'];
        $size = $value['size'];
        $tensanpham = $value['tensanpham'];

        mysqli_query($mysqli, "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua, size) 
                                    VALUES ('$id_sanpham', '$code_order', '$soluong','$size')");
        $sql_check = "SELECT * FROM tbl_sanpham WHERE id_sanpham = '$id_sanpham' LIMIT 1";
        $row_product = mysqli_fetch_assoc(mysqli_query($mysqli, $sql_check));

        if ($row_product['id_danhmuc'] == 1) {
            $query_seri = mysqli_query($mysqli, "SELECT * FROM tbl_seri WHERE id_sanpham = '$id_sanpham' AND tinhtrang = 0 LIMIT $soluong");
            $seri_count = mysqli_num_rows($query_seri);
            if ($seri_count < $soluong) die("Không đủ seri cho '$tensanpham'.");
            while ($row_seri = mysqli_fetch_assoc($query_seri)) {
                $seri = $row_seri['seri'];
                mysqli_query($mysqli, "INSERT INTO tbl_serial_racket(code_cart, id_sanpham, seri) VALUES ('$code_order', '$id_sanpham', '$seri')");
                mysqli_query($mysqli, "UPDATE tbl_seri SET tinhtrang = 1 WHERE seri = '$seri' AND id_sanpham = '$id_sanpham'");
            }
            $soluongcon = $row_product['soluong'] - $seri_count;
            $soluongbanra = $row_product['soluongban'] + $seri_count;
        } else {
            if (!in_array($row_product['id_danhmuc'], $khong_co_size) && !empty($size)) {
                $sql_kichthuoc = "SELECT soluong FROM tbl_kichthuoc WHERE id_sanpham = '$id_sanpham' AND size = '$size' LIMIT 1";
                $row_kichthuoc = mysqli_fetch_assoc(mysqli_query($mysqli, $sql_kichthuoc));
                if (!$row_kichthuoc || $row_kichthuoc['soluong'] < $soluong)
                    die("Không đủ kho size '$size' cho '$tensanpham'.");
                $soluong_size_con = $row_kichthuoc['soluong'] - $soluong;
                mysqli_query($mysqli, "UPDATE tbl_kichthuoc SET soluong = '$soluong_size_con' WHERE id_sanpham = '$id_sanpham' AND size = '$size'");
            }
            $soluongcon = $row_product['soluong'] - $soluong;
            $soluongbanra = $row_product['soluongban'] + $soluong;
        }
        mysqli_query($mysqli, "UPDATE tbl_sanpham SET soluong = '$soluongcon', soluongban = '$soluongbanra' WHERE id_sanpham = '$id_sanpham'");
    }
    // Lấy phí vận chuyển từ tbl_cart -> tbl_shipping
    $sql_shipping = mysqli_query($mysqli, "SELECT s.shipping_fee 
    FROM tbl_cart AS c 
    JOIN tbl_shipping AS s ON c.cart_shipping = s.id_shipping 
    WHERE c.code_cart = '$code_order' 
    LIMIT 1");
    $row_shipping = mysqli_fetch_array($sql_shipping);
    $shipping_fee = isset($row_shipping['shipping_fee']) ? (int)$row_shipping['shipping_fee'] : 0;

    // Cộng phí vận chuyển vào tổng tiền gửi MoMo
    $amount += $shipping_fee;
    $total_with_shipping = $total + $shipping_fee;


    // === Gửi email xác nhận đơn hàng ===
    $tieude = "Đặt hàng Website DCSPORT thành công";
    $noidung = "
<html><body><div class='container'>
<p>Cảm ơn quý khách đã đặt hàng với mã đơn hàng: <strong>$code_order</strong></p>
<p>Vui lòng chú ý điện thoại, đơn hàng sẽ sớm được giao đến bạn. ❤️ </p>
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



    $total_with_shipping = $total + $shipping_fee;

    // Hiển thị tổng tiền trong mail
    // $noidung .= "<p class='total'>Tạm tính: " . number_format($total, 0, ',', '.') . " VNĐ</p>";
    $noidung .= "<p class='total'>Phí vận chuyển: " . number_format($shipping_fee, 0, ',', '.') . " VNĐ</p>";
    $noidung .= "<p class='total'>Tổng thanh toán: " . number_format($total_with_shipping, 0, ',', '.') . " VNĐ</p>";
    // $noidung .= "<p class='total'>Tổng giá trị đơn hàng: " . number_format($amount, 0, ',', '.') . " VNĐ</p>";
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
} else {
    echo "Không tìm thấy thông tin vận chuyển.";
}
