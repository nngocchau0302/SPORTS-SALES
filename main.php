<div class="clear"></div>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div class="main">
    <?php
        if(isset($_GET['action']) && isset($_GET['query'])){
            $tam = $_GET['action'];
            $query = $_GET['query'];
          }else{
              $tam = '';
              $query = '';
          }   
          if($tam=='quanlydanhmucsanpham' && $query=='them'){
            include("quanlydanhmucsp/them.php");
            include("quanlydanhmucsp/lietke.php");
            
          }elseif($tam=='quanlydanhmucsanpham' && $query=='sua'){
            include("quanlydanhmucsp/sua.php");
          }
          
          elseif($tam=='quanlysp' && $query=='them'){
            include("quanlysp/them.php");
            include("quanlysp/lietke.php");
          }elseif($tam=='quanlysp' && $query=='sua'){
            include("quanlysp/sua.php");
          
          }elseif($tam=='quanlydathang' && $query=='lietke'){
            include("quanlydathang/lietke.php");

          }elseif($tam=='donhang' && $query=='xemdonhang'){
            include("quanlydathang/xemdonhang.php");

          }elseif($tam=='donhang' && $query=='indonhang'){
            include("quanlydathang/indonhang.php");
          }
          elseif($tam=='quanlythongke' && $query=='thongke'){
            include("quanlythongke/thongke.php");
          }elseif($tam=='quanlythongke' && $query=='thongkesanpham'){
            include("quanlythongke/thongke_sanpham.php");
          }
          elseif($tam=='quanlythuonghieu' && $query=='sua'){
            include("quanlythuonghieu/sua.php");
          }
          elseif($tam=='quanlythuonghieu' && $query=='them'){
            include("quanlythuonghieu/them.php");
            include("quanlythuonghieu/lietke.php");
          }
          elseif($tam=='quanlykhachhang' && $query=='lietke'){
            include("quanlykhachhang/lietke_khachhang.php");
          }
          elseif($tam=='quanlybaohanh' && $query=='them'){
            include("quanlybaohanh/lietke.php");
          }
           elseif($tam=='quanlykhuyenmai' && $query=='them'){
            include("quanlykhuyenmai/them.php");
            include("quanlykhuyenmai/lietke.php");
          }
          elseif($tam=='quanlykho' && $query=='lietke'){
            include("quanlykho/lietkekho.php");
          }elseif($tam=='xemchitiet' && $query=='serivot'){
            include("quanlykho/xemchitiet.php");

          }elseif($tam=='xemchitietkichthuoc' && $query=='kichthuoc'){
            include("quanlykho/xemchitietkichthuoc.php");

          }
          elseif($tam=='quanlybinhluan' && $query=='lietke'){
            include("quanlybinhluan/lietkebinhluan.php");
          }
          
          
          
          else{
            include("dashboard.php");
          }
    ?>
</div>