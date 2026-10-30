<?php
include('../../config/config.php');

// 1. Xử lý ẨN
if (isset($_GET['an_id'])) {
    $id_binhluan = intval($_GET['an_id']);
    $sql_an = "UPDATE tbl_binhluan SET trangthai = 'an' WHERE id_binhluan = '$id_binhluan'";
    if (mysqli_query($mysqli, $sql_an)) {
        header("Location:/admincf/index.php?action=quanlybinhluan&query=lietke");
        exit();
    } else {
        echo "<script>alert('Lỗi khi ẩn bình luận'); window.history.back();</script>";
    }
}

// 2. Xử lý HIỆN
if (isset($_GET['hien_id'])) {
    $id_binhluan = intval($_GET['hien_id']);
    $sql_hien = "UPDATE tbl_binhluan SET trangthai = 'hien' WHERE id_binhluan = '$id_binhluan'";
    if (mysqli_query($mysqli, $sql_hien)) {
        header("Location:/admincf/index.php?action=quanlybinhluan&query=lietke");
        exit();
    } else {
        echo "<script>alert('Lỗi khi hiện bình luận'); window.history.back();</script>";
    }
}
