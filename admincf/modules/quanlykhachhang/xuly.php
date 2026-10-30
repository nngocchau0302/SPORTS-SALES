<?php
include('../../config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Xóa bình luận
    mysqli_query($mysqli, "DELETE FROM tbl_binhluan WHERE id_dangky = $id");

    // Xóa đơn hàng & chi tiết (nếu muốn)
    $get_carts = mysqli_query($mysqli, "SELECT code_cart FROM tbl_cart WHERE id_khachhang = $id");
    while ($row = mysqli_fetch_array($get_carts)) {
        $code = $row['code_cart'];
        mysqli_query($mysqli, "DELETE FROM tbl_cart_details WHERE code_cart = '$code'");
    }
    mysqli_query($mysqli, "DELETE FROM tbl_cart WHERE id_khachhang = $id");

    // Xóa tài khoản
    mysqli_query($mysqli, "DELETE FROM tbl_dangky WHERE id_dangky = $id");

    header("Location: ../../index.php?action=quanlykhachhang");
}
?>
