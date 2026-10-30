<?php
 // Bắt đầu output buffering
 ob_start();
// session_start();
include(__DIR__."/../../admincf/config/config.php");
require(__DIR__."/../../mail/sendmail.php");
require(__DIR__.'/../../carbon/autoload.php');

use Carbon\Carbon;
use Carbon\CarbonInterval;

$now = Carbon::now('Asia/Ho_Chi_Minh');


$id_khachhang = isset($_SESSION['id_khachhang']) ? $_SESSION['id_khachhang'] : 0; // Kiểm tra session có tồn tại không
$code_order = rand(1000, 9999); // Để tránh mã đơn hàng là 0

if ($id_khachhang > 0) {
    $insert_cart = "INSERT INTO tbl_cart(id_khachhang,code_cart,cart_status,cart_date) 
                    VALUES ('$id_khachhang', '$code_order', 1, '$now')";
    $cart_query = mysqli_query($mysqli, $insert_cart);

    if ($cart_query) {
        // Thêm chi tiết giỏ hàng
        foreach($_SESSION['cart'] as $key => $value) {
            $id_sanpham = $value['id'];
            $soluong = $value['soluong'];
            $insert_order_details = "INSERT INTO tbl_cart_details(id_sanpham,code_cart,soluongmua) 
                                    VALUES ('$id_sanpham', '$code_order', '$soluong')";
            mysqli_query($mysqli, $insert_order_details);
        }

        // Gửi email xác nhận đơn hàng
        $tieude = "Đặt hàng Website DCSPORT thành công";
        $noidung = "<p>Cảm ơn quý khách đã đặt hàng của chúng tôi với mã đơn hàng là: <strong>$code_order</strong></p>
        <h4>Đơn hàng đã đặt bao gồm:</h4>
        <ul style='border:1px solid blue; margin:10px'>";

        foreach($_SESSION['cart'] as $key => $val) {
            $noidung .= "<li><strong>Tên sản phẩm:</strong> " . $val['tensanpham'] . "</li>
            <li><strong>Mã sản phẩm:</strong> " . $val['masp'] . "</li>
            <li><strong>Giá:</strong> " . number_format($val['giasp'], 0, ',', '.') . " VNĐ</li>
            <li><strong>Số lượng:</strong> " . $val['soluong'] . "</li>";
        }
        $noidung .= "</ul>";

        $maildathang = isset($_SESSION['email']) ? $_SESSION['email'] : '';

        if (!empty($maildathang)) {
            $mail = new Mailer();
            $mail->dathangmail($tieude, $noidung, $maildathang);
        }
    }
    
    unset($_SESSION['cart']);
    header('Location:index.php?quanly=camon');
    // exit(); // Đảm bảo script dừng lại sau khi redirect
} else {
    echo "Lỗi: Không tìm thấy ID khách hàng.";
}

ob_end_flush();
?>
