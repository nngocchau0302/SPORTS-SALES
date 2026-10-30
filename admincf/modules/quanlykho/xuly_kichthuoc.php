<?php
include('../../config/config.php');

if (isset($_GET['action']) && isset($_GET['id']) && isset($_GET['idsp'])) {
    $id_kichthuoc = $_GET['id'];
    $id_sanpham = $_GET['idsp'];
    $action = $_GET['action'];

    // Lấy số lượng hiện tại của size
    $sql = "SELECT soluong FROM tbl_kichthuoc WHERE id = '$id_kichthuoc' LIMIT 1";
    $query = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($query);
    $soluong = (int)$row['soluong'];

    // Xử lý cộng/trừ
    if ($action == 'cong') {
        $soluong++;
    } elseif ($action == 'tru' && $soluong > 0) {
        $soluong--;
    }

    // Cập nhật lại bảng tbl_kichthuoc
    $sql_update = "UPDATE tbl_kichthuoc SET soluong = '$soluong' WHERE id = '$id_kichthuoc'";
    mysqli_query($mysqli, $sql_update);

    // Tính tổng lại toàn bộ số lượng các size của sản phẩm
    $sql_sum = "SELECT SUM(soluong) AS tongsize FROM tbl_kichthuoc WHERE id_sanpham = '$id_sanpham'";
    $query_sum = mysqli_query($mysqli, $sql_sum);
    $row_sum = mysqli_fetch_assoc($query_sum);
    $tongsize = (int)$row_sum['tongsize'];

    // Cập nhật lại tổng số lượng trong bảng tbl_sanpham
    $sql_update_sp = "UPDATE tbl_sanpham SET soluong = '$tongsize' WHERE id_sanpham = '$id_sanpham'";
    mysqli_query($mysqli, $sql_update_sp);

    // Quay về lại trang xem chi tiết size
    header("Location: xemchitietkichthuoc.php?id=$id_sanpham");
} else {
    echo "Thiếu dữ liệu!";
}
