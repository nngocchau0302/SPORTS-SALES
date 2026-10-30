<!--?php
 $sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC" ;
    $query_danhmuc = mysqli_query($mysqli,$sql_danhmuc);
?>


<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
          <div class="text">Danh mục sản phẩm</div>
          <!--<ul class="list_sidebar">
              <li><a href="index2.php?quanly=danhmucsanpham&id=1">Cà Phê</a></li>
              <li><a href="index2.php?quanly=danhmucsanpham&id=2">Trà</a></li>
              <li><a href="index2.php?quanly=danhmucsanpham&id=3">Freeze</a></li>
          </ul>-->
          <!--ul class="list_sidebar">
          <!?php
              while($row_danhmuc = mysqli_fetch_array($query_danhmuc)){
          ?>
          <li><a href="index.php?quanly=danhmucsanpham&id=<!?php echo $row_danhmuc['id_danhmuc'] ?>"><!?php  echo $row_danhmuc['tendanhmuc']?></a></li>
          <!?php
              }
              ?>
              </!--ul>
</div-->
       
<style>
    .list-group-item.list-hover:hover {
  background-color: #f0f8ff;
  transition: background-color 0.3s ease;
}

.card-header {
  font-size: 1.2rem;
  letter-spacing: 0.5px;
}
.list-group-item.list-hover:hover {
  background-color: #f0f8ff;
  transition: background-color 0.3s ease;
}

.card-header {
  font-size: 1.2rem;
  letter-spacing: 0.5px;
}

.card-body h6 {
  border-bottom: 1px solid #dee2e6;
  padding-bottom: 6px;
}
.small-sidebar .form-label {
  font-size: 0.85rem;
}
.small-sidebar .form-check-label {
  font-size: 0.85rem;
}
.small-sidebar .card-body {
  font-size: 0.85rem;
}
.small-sidebar .list-group-item {
  font-size: 0.9rem;
}
.custom-header {
  background-color: #DDF2FD !important;
  color:#164863 !important;
}

    </style>
<?php
// Lấy ra danh mục
  $sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
  $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);

// Lấy ra thương hiệu
  $sql_thuonghieu = "SELECT * FROM tbl_thuonghieu ORDER BY id_thuonghieu DESC";
  $query_thuonghieu = mysqli_query($mysqli, $sql_thuonghieu);
?>

<div class="col-lg-2 col-md-3 col-sm-12 mb-4">
  <!-- DANH MỤC -->
  <div class="card shadow-sm small-sidebar mb-3">
    <div class="card-header custom-header text-white text-center  py-2">
      Danh mục
    </div>
    <ul class="list-group list-group-flush">
      <?php while($row_danhmuc = mysqli_fetch_array($query_danhmuc)) { ?>
        <li class="list-group-item py-2 px-3 list-hover">
          <a href="index.php?quanly=danhmucsanpham&id=<?php echo $row_danhmuc['id_danhmuc']; ?>" class="text-decoration-none text-dark d-block">
            <i class="bi bi-chevron-right me-1"></i><?php echo $row_danhmuc['tendanhmuc']; ?>
          </a>
        </li>
      <?php } ?>
    </ul>
  </div>


  <!-- THƯƠNG HIỆU -->
     <div class="card shadow-sm small-sidebar mb-3">
    <div class="card-header custom-header text-white text-center  py-2">
      Thương hiệu
    </div>
    <ul class="list-group list-group-flush">
      <?php while($row_thuonghieu = mysqli_fetch_array($query_thuonghieu)) { ?>
        <li class="list-group-item py-2 px-3 list-hover">
          <a href="index.php?quanly=thuonghieu&id=<?php echo $row_thuonghieu['id_thuonghieu']; ?>" class="text-decoration-none text-dark d-block">
            <i class="bi bi-chevron-right me-1"></i><?php echo $row_thuonghieu['tenthuonghieu']; ?>
          </a>
        </li>
      <?php } ?>
    </ul>
  </div>

</div>


