<?php
include('../../config/config.php');

if (isset($_POST['themkmai'])) {
    $id_sp = $_POST['id_sanpham'];
    $tenkm = $_POST['tenkm'];
    $phantram = $_POST['phantramgiam'];
    $batdau = $_POST['ngaybatdau'];
    $ketthuc = $_POST['ngayketthuc'];

    $sql_insert = "INSERT INTO tbl_khuyenmai (tenkm, phantramgiam, ngaybatdau, ngayketthuc, id_sanpham) 
                   VALUES ('$tenkm', '$phantram', '$batdau', '$ketthuc', '$id_sp')";
    mysqli_query($mysqli, $sql_insert);
    header('Location: ../../index.php?action=quanlykhuyenmai&query=them');
}
?>
