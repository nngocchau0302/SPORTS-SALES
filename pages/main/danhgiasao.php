<?php
include("../../admincf/config/config.php");

// Kiểm tra kết nối
if ($mysqli->connect_error) {
    die("Kết nối thất bại: " . $mysqli->connect_error);
}

// Lấy dữ liệu từ AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $ratingValue = isset($_POST['ratingValue']) ? intval($_POST['ratingValue']) : 0;

    // Kiểm tra dữ liệu hợp lệ
    if ($product_id > 0 && $ratingValue > 0 && $ratingValue <= 5) {
        // Lưu đánh giá
        $sql = "INSERT INTO tbl_danhgiasao (product_id, rating) VALUES ('$product_id', '$ratingValue')";
        echo $mysqli->query($sql) === TRUE ? 'done' : 'error';
    } else {
        echo 'invalid';
    }
}

// Đóng kết nối
$mysqli->close();
?>