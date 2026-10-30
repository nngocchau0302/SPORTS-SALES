<?php
include('../../admincf/config/config.php');

if (isset($_GET['idyeuthich'])) {
    $id = $_GET['idyeuthich'];
    
    // Kiểm tra xem sản phẩm có trong danh sách yêu thích không
    $sql_check = "SELECT * FROM tbl_yeuthich WHERE product_id = '$id' LIMIT 1";
    $query_check = mysqli_query($mysqli, $sql_check);
    
    if (mysqli_num_rows($query_check) > 0) {
        // Xóa sản phẩm khỏi bảng yêu thích
        $sql_delete = "DELETE FROM tbl_yeuthich WHERE product_id = '$id'";
        if (mysqli_query($mysqli, $sql_delete)) {
            echo "<script>alert('Sản phẩm đã được xóa khỏi danh sách yêu thích!'); window.location.href='http://localhost:3000/index.php?quanly=yeuthichsp';</script>";
        } else {
            echo "<script>alert('Lỗi khi xóa sản phẩm!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Sản phẩm không tồn tại trong danh sách yêu thích!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Thiếu ID sản phẩm!'); window.history.back();</script>";
}
?>
