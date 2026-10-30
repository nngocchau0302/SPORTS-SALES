<?php
ob_start();
include("./admincf/config/config.php");
if (isset($_POST['dangky'])) {
  $tenkhachhang = $_POST['hovaten'];
  $email = $_POST['email'];
  $dienthoai = $_POST['dienthoai'];
  $matkhau = md5($_POST['matkhau']);
  $diachi = $_POST['diachi'];
  $sql_dangky = mysqli_query($mysqli, "INSERT INTO tbl_dangky(tenkhachhang,email,diachi,matkhau,dienthoai) VALUES ('" . $tenkhachhang . "','" . $email . "','" . $diachi . "' ,'" . $matkhau . "', '" . $dienthoai . "')");
  if ($sql_dangky) {
    $_SESSION['dangky'] = $tenkhachhang;
    $_SESSION['email'] = $email;
    $_SESSION['id_khachhang'] = mysqli_insert_id($mysqli);
    header('Location:index.php?quanly=giohang');
    exit();
  }else {
    echo '<p style="color:green">Bạn đã đăng ký thành công</p>';
  }
}
?>

<link rel="stylesheet" href="../../css/dangky.css" />





    <div class="containerContent">
      <h1>Đăng ký thành viên</h1>
      <form action="" method="POST">

        <label for="uname">Họ và tên</label>
        <div class="inputRow">
          <input type="username" placeholder="Nhập tên" name="hovaten">
        </div>



        <label for="mail">Email</label>
        <div class="inputRow">
          <input type="username" placeholder="Nhập email" name="email">
        </div>



        <label for="sdt">Số điện thoại</label>
        <div class="inputRow">
          <input type="text" placeholder="Nhập số điện thoại" name="dienthoai" maxlength="10">
        </div>



        <label for="mail">Địa chỉ</label>
        <div class="inputRow">
          <input type="text" placeholder="Nhập địa chỉ" name="diachi" required>
        </div>



        <label for="pwd">Password</label>
        <div class="inputRow">

          <input type="password" name="matkhau" placeholder="Nhập password" required />

        </div>



        <button type="submit" name="dangky">Đăng ký</button>
        <p>Tôi đã có tài khoản <a href="index.php?quanly=dangnhap"> Log in</a></p>
    </div>


    </form>

