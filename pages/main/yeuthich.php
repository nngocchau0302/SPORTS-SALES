<?php
include('../../admincf/config/config.php');

    $product_id = $_POST['product_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    $sql_check = mysqli_query($mysqli,"SELECT * FROM tbl_yeuthich WHERE product_id='$product_id ' AND ip_address='$ip_address' LIMIT 1");
    $result = mysqli_fetch_array($sql_check);
    $result_count = mysqli_num_rows($sql_check);
    
    if($result_count <1 ){
       //insert vào dữ liệu
    $sql_chitiet = "INSERT INTO tbl_yeuthich(product_id,ip_address) VALUES('".$product_id."', '".$ip_address."') ";
    $query_chitiet = mysqli_query($mysqli, $sql_chitiet);
    echo 'Done';
    
    }else{
        echo 'Fail';
    }
    
?>
