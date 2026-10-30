<?php
session_start();
include("../../admincf/config/config.php");

if (isset($_POST['guibinhluan'])) {
    $id_dangky = $_SESSION['id_dangky'] ?? null;
    $id_sanpham = $_POST['id_sanpham'];
    $noidung = mysqli_real_escape_string($mysqli, $_POST['noidung']);

    if ($id_dangky && !empty($noidung)) {
        $sql_khach = "SELECT tenkhachhang FROM tbl_dangky WHERE id_dangky = '$id_dangky' LIMIT 1";
        $res_khach = mysqli_query($mysqli, $sql_khach);
        $row_khach = mysqli_fetch_array($res_khach);
        $ten = $row_khach['hoten'] ?? 'Ẩn danh';

        $sql_insert = "INSERT INTO tbl_binhluan(id_sanpham, ten, noidung, id_dangky, ngaybinhluan)
                       VALUES ('$id_sanpham', '$ten', '$noidung', '$id_dangky', NOW())";

        if (mysqli_query($mysqli, $sql_insert)) {
            header("Location: ../../index.php?quanly=sanpham&id=$id_sanpham");
            exit;
        } else {
            echo "Lỗi khi thêm bình luận: " . mysqli_error($mysqli);
        }
    } else {
        echo "Dữ liệu không hợp lệ!";
    }
}
?>
