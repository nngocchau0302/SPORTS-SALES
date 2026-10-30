<?php
if (isset($_POST['Thembaohanh'])) {
    $masanpham = $_POST['masanpham'];
    $thoihan = $_POST['thoihan'];
    $ghichu = $_POST['ghichu'];
    $dieukien = $_POST['dieukien'];

    $sql = "INSERT INTO tbl_baohanh (masanpham, thoihan, ghichu, dieukien) VALUES ('$masanpham', '$thoihan', '$ghichu',  '$dieukien')";
    mysqli_query($mysqli, $sql);
    header('Location: /admincf/index.php?action=quanlybaohanh&query=them');
}
elseif(isset($_POST['suabaohanh'])){
        //sửa
        $sql_update = "UPDATE tbl_baohanh SET masanpham='".$masanpham."' ,thoihan='".$thoihan."', ghichu='".$ghichu."',  dieukien='".$dieukien."' WHERE id_baohanh='$_GET[id_baohanh]' ";
        mysqli_query($mysqli,$sql_update);
        header('Location:/admincf/index.php?action=quanlydanhmucsanpham&query=them');
            
    }else{
        $id=$_GET['id_danhmuc'];
        $sql_xoa = "DELETE FROM tbl_baohanh WHERE id_baohanh='" . $id . "'";
        mysqli_query($mysqli,$sql_xoa);
        header('Location:/admincf/index.php?action=quanlybaohanh&query=them');
    }
