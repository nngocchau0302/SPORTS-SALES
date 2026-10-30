<div id="main">
  <div class="row">
   
      <?php
      include("sidebar/sidebar.php");
      ?>
    
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
      <div class="maincontent">
        <?php
        if (isset($_GET['quanly'])) {
          $tam = $_GET['quanly'];
        } else {
          $tam = '';
        }
        if ($tam == 'danhmucsanpham') {
          include("main/danhmuc.php");
        } elseif ($tam == 'giohang') {
          include("main/giohang.php");
        } elseif ($tam == 'sanpham') {
          include("main/sanpham.php");
        } elseif ($tam == 'thanhtoan') {
          include("main/thanhtoan.php");
        } elseif ($tam == 'dangky') {
          include("main/dangky.php");
        } elseif ($tam == 'dangnhap') {
          include("main/dangnhap.php");
        } elseif ($tam == 'timkiem') {
          include("main/timkiem.php");
        } elseif ($tam == 'camon') {
          include("main/camon.php");
        } elseif ($tam == 'thaydoimatkhau') {
          include("main/thaydoimatkhau.php");
        } elseif ($tam == 'muangay') {
          include("main/muangay.php");
        } elseif ($tam == 'vanchuyen') {
          include("main/vanchuyen.php");
        } elseif ($tam == 'thongtinthanhtoan') {
          include("main/thongtinthanhtoan.php");
        } elseif ($tam == 'donhangdadat') {
          include("main/donhangdadat.php");
        } elseif ($tam == 'xulythanhtoan') {
          include("main/xulythanhtoan.php");
        } elseif ($tam == 'lichsudonhang') {
          include("main/lichsudonhang.php");
        }elseif ($tam == 'xemdonhang') {
          include("main/xemdonhang.php");
        }elseif ($tam == 'yeuthichsp') {
          include("main/yeuthichsp.php");
        } elseif ($tam == 'camonkh') {
          include("main/camonkh.php");
        }elseif ($tam == 'thuonghieu') {
          include("main/thuonghieu.php");
        }
        
        
        
        
        
        else {
          include("main/index.php");
        }
        ?>
      </div>
    </div>
    </div>
    

  </div>


