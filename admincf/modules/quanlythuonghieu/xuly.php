<?php
include('../../config/config.php');
    $tenthsp = $_POST['tenthuonghieu'];
    $thutu = $_POST['thutu'];
    if(isset($_POST['Themthuonghieu'])){
        //thêm
        $sql_them = "INSERT INTO tbl_thuonghieu(tenthuonghieu,thutu) VALUE('".$tenthsp."','".$thutu."')";
        mysqli_query($mysqli,$sql_them);
        header('Location:/admincf/index.php?action=quanlythuonghieu&query=them');
    }elseif(isset($_POST['suathuonghieu'])){
        //sửa
        $sql_update = "UPDATE tbl_thuonghieu SET tenthuonghieu='".$tenthsp."' ,thutu='".$thutu."' WHERE id_thuonghieu='$_GET[idthuonghieu]' ";
        mysqli_query($mysqli,$sql_update);
        header('Location:/admincf/index.php?action=quanlythuonghieu&query=them');
            
    }else{
        $id=$_GET['id_thuonghieu'];
        $sql_xoa = "DELETE FROM tbl_thuonghieu WHERE id_thuonghieu='".$id."'   ";
        mysqli_query($mysqli,$sql_xoa);
        header('Location:/admincf/index.php?action=quanlythuonghieu&query=them');
    }
?>