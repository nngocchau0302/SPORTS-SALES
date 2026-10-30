<?php
ob_start();
session_start();

if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
  unset($_SESSION['dangky']);
  unset($_SESSION['id_khachhang']);
  header("Location: index.php");
  exit();
}
?>


<header>

  <div class="inner-header container">
    <a id="logo">
      <!-- <div class="logo-text">LEGGO COFFEE</div>
      <div class="logo-slogan">Không chỉ là cà phê</div> -->
      <img style="object-fit:unset" width="50" height="50" src="/images/logoDC2.png" data-src="/images/logoDC2.png" alt="VNBSports" class="">
    </a>
    <nav id="menu">
      <a href="index.php">Trang Chủ </a>
      <a href="../about.php">Giới Thiệu</a>
      <a href="../lienhe.php"> Liên hệ</a>
      <a href="../tintuc.php">Tin Tức</a>

    </nav>
    <form action="index.php?quanly=timkiem" method="POST">
      <div class="search-container">
        <input type="text" placeholder="Tìm sản phẩm ..." name="tukhoa">
        <button type="submit" name="timkiem"><i name="timkiem" class="fas fa-search"></i></button>
      </div>
    </form>




    <div class="user-icon">
      <i class="fas fa-user"></i>
      <div class="dropdown-content">
        <?php
        if (isset($_SESSION['dangky'])) {

        ?>
          <a href="index.php?dangxuat=1">Đăng xuất</a>
          <a href="index.php?quanly=thaydoimatkhau">Đổi mật khẩu</a>
          <!-- <a href="index.php?quanly=lichsudonhang">Lịch sử đơn hàng</a> -->
        <?php
        } else {
          // User is not logged in
        ?>
          <a href="index.php?quanly=dangky">Đăng ký</a>
          <a href="index.php?quanly=dangnhap">Đăng nhập</a>
        <?php
        }
        ?>

      </div>
    </div>
   <a href="index.php?quanly=yeuthichsp"><i class="fa-solid fa-heart fa-lg" style="color: #e30d0d; margin:left;"></i></a>
    <?php
        if (isset($_SESSION['dangky'])) {

        ?>
   <?php
        } else {
          // User is not logged in
        ?>
         <?php
        }
        ?>

    <div id="header_item">
   
      <div class="cart"><a href="index.php?quanly=giohang"><i class="fa-solid fa-cart-shopping fa-lg" style="color:rgb(50, 69, 94);"></i></a>
        
      </div>
    </div>
  </div>
</header>