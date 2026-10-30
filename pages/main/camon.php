<?php
include("admincf/config/config.php");
require("./carbon/autoload.php");

use Carbon\Carbon;
use Carbon\CarbonInterval;

$now = Carbon::now('Asia/Ho_Chi_Minh');

if (isset($_GET['vnp_Amount'])) {
    $vnp_Amount = $_GET['vnp_Amount'];
    $vnp_BankCode = $_GET['vnp_BankCode'];
    $vnp_OrderInfo = $_GET['vnp_OrderInfo'];
    $vnp_BankTranNo = $_GET['vnp_BankTranNo'];
    $vnp_PayDate = $_GET['vnp_PayDate'];
    $vnp_TmnCode = $_GET['vnp_TmnCode'];
    $vnp_TransactionNo = $_GET['vnp_TransactionNo'];
    $vnp_CardType = $_GET['vnp_CardType'];

    $code_cart = $_SESSION['code_cart'];


    // chèn dữ liệu vnPay
    $insert_vnpay = "INSERT INTO tbl_vnpay(vnp_amount,code_cart,vnp_bankCode,vnp_banktranno,vnp_cardtype,vnp_orderinfo,vnp_paydate,vnp_tmncode,vnp_transactionno) 
                         VALUES('" . $vnp_Amount . "', '" . $code_cart . "', '" . $vnp_BankCode . "', '" . $vnp_BankTranNo . "', '" . $vnp_CardType . "', '" . $vnp_OrderInfo . "', '" . $vnp_PayDate . "', '" . $vnp_TmnCode . "', '" . $vnp_TransactionNo . "')";
    $cart_query = mysqli_query($mysqli, $insert_vnpay);
    if ($cart_query) {
        //insert giỏ hàng
        echo '<h3>Giao dịch thanh toán VNPAY thành công</h3>';
        echo '<p>Vui lòng vào trang <a target="_blank" href="http://localhost:3000/index.php?quanly=donhangdadat"> Lịch sử đơn hàng </a> để xem chi tiết đơn hàng của bạn</p>';
    } else {
        echo 'Giao dịch thất bại';
    }
    // thanh toán momo atm
} elseif (isset($_GET['partnerCode'])) {

    $id_khachhang = isset($_SESSION['id_khachhang']) ? $_SESSION['id_khachhang'] : 0; // Kiểm tra xem ID khách hàng có trong session không
    $code_order = rand(0, 9999);
    $partnerCode = $_GET['partnerCode'];
    $orderId = $_GET['orderId'];
    $amount = $_GET['amount'];
    $orderInfo = $_GET['orderInfo'];
    $orderType = $_GET['orderType'];
    $transId = $_GET['transId'];
    $payType = $_GET['payType'];
    $cart_payment = 'momo';

    // Lấy ID thông tin vận chuyển
    $id_dangky = $id_khachhang;
    $sql_get_vanchuyen = mysqli_query($mysqli, "SELECT * FROM tbl_shipping WHERE id_dangky = '$id_dangky' LIMIT 1");

    if (!$sql_get_vanchuyen) {
        die("Lỗi truy vấn vận chuyển: " . mysqli_error($mysqli));
    }

    $row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);
    $id_shipping = $row_get_vanchuyen['id_shipping'] ?? null; // Sử dụng null coalescing để tránh lỗi nếu không tìm thấy
    // chèn dữ liệu Momo
    $insert_momo = "INSERT INTO tbl_momo(partner_code,order_id,amount,order_info,order_type,trans_id,pay_type,code_cart) 
                         VALUES('" . $partnerCode . "', '" . $orderId . "', '" . $amount . "', '" . $orderInfo . "', '" . $orderType . "', '" . $transId . "', '" . $payType . "','" . $code_order . "')";
    $cart_query = mysqli_query($mysqli, $insert_momo);

    if ($cart_query) {
        // Insert đơn hàng
        $insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping) 
             VALUES ('" . $id_khachhang . "', '" . $code_order . "', 1, '" . $now . "', '" . $cart_payment . "', '" . $id_shipping . "')";

        $cart_query = mysqli_query($mysqli, $insert_cart);

        if (!$cart_query) {
            die("Lỗi khi thêm đơn hàng: " . mysqli_error($mysqli));
        }

        // Thêm đơn hàng chi tiết
        foreach ($_SESSION['cart'] as $key => $value) {
            $id_sanpham = $value['id'];
            $soluong = $value['soluong'];
            $insert_order_details = "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua) 
                                 VALUES ('" . $id_sanpham . "', '" . $code_order . "', '" . $soluong . "')";
            mysqli_query($mysqli, $insert_order_details);
        }
        //insert giỏ hàng
        echo '<h3 class="text-lg font-roboto mb-4 text-gray-800">Giao dịch thanh toán bằng MOMO thành công</h3>';
        echo '<p  class="text-lg font-roboto mb-12 text-gray-800">Vui lòng vào trang <a target="_blank" href="#"> Lịch sử đơn hàng </a> để xem chi tiết đơn hàng của bạn</p>';
    } else {
        echo 'Giao dịch MOMO thất bại';
    }
}
?>





<div class="wrapper_sanpham">
    <div class="hinhanh_sanpham"
        style="float: left;
    width: 65%;
     height:auto;
    text-align: center;
    margin-left: 200px;
    ">

        <img width="90%" src="/images/camon2.png">
    </div>


    <!-- <form method="POST" action="">
        <div class="chitiet_sanpham"
            style="float: right;
    width: 40%;">
            <img width="90%" src="/images/camon2.png">

        </div>
    </form> -->
</div>
<style>
    .wrapper {
        height: 500px;
    }

    .wrapper_sanpham {
        margin-top: 20px;
    }
</style>