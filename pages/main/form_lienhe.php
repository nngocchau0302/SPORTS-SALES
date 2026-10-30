<?php
include("../../admincf/config/config.php");

if (isset($_POST['guilienhe'])) {
    $email = $_POST['email'];
    $username =  $_POST['username']; // Mã hóa mật khẩu cũ
    $phone =  $_POST['phone'];; // Mã hóa mật khẩu mới
    $ct_address =  $_POST['ct_address'];
    $ct_status =  $_POST['ct_status'];
    // Kiểm tra mật khẩu cũ
    $sql = "INSERT INTO tbl_lienhe(email,username,phone,ct_address,ct_status) VALUES('".$email."', '".$username."', '".$phone."', '".$ct_address."', '".$ct_status."')";
    $row = mysqli_query($mysqli,$sql);

  

   
        
      
        echo "<script>alert('Thông tin gửi thành công!'); window.location.href='http://localhost:3000/lienhe.php';</script>";
    
}
?>