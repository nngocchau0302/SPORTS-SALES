<?php

// Lấy ra sản phẩm
$sql_lietke_sp = "SELECT * FROM tbl_sanpham
JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
LEFT JOIN tbl_thuonghieu ON tbl_sanpham.id_thuonghieu = tbl_thuonghieu.id_thuonghieu
ORDER BY id_sanpham DESC";

$query_lietke_sp = mysqli_query($mysqli, $sql_lietke_sp);
?>

<p class="chitietsp">Liệt kê sản phẩm</p>

<!-- Tìm kiếm -->
<div class="search-form-right">
  <form method="POST" action="">
    <input type="text" name="tukhoa" placeholder="Nhập mã hoặc tên sản phẩm">
    <button type="submit" name="timkiem">
      <i class="fas fa-search"></i>
    </button>
  </form>
</div>

<?php
if (isset($_POST['timkiem'])) {
  $tukhoa = mysqli_real_escape_string($mysqli, $_POST['tukhoa']);
  $sql_lietke_sp = "SELECT * FROM tbl_sanpham
    JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
    LEFT JOIN tbl_thuonghieu ON tbl_sanpham.id_thuonghieu = tbl_thuonghieu.id_thuonghieu
    WHERE tbl_sanpham.tensanpham LIKE '%$tukhoa%' OR tbl_sanpham.masp LIKE '%$tukhoa%'
    ORDER BY id_sanpham DESC";
} else {
  $sql_lietke_sp = "SELECT * FROM tbl_sanpham
    JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
    LEFT JOIN tbl_thuonghieu ON tbl_sanpham.id_thuonghieu = tbl_thuonghieu.id_thuonghieu
    ORDER BY id_sanpham DESC";
}

$query_lietke_sp = mysqli_query($mysqli, $sql_lietke_sp);
?>


<!-- Liệt kê sản phẩm -->
<table style="width:100%" border="1" style="border-collapse:collapse;">
  <tr>
    <th>Id</th>
    <th>Tên sản phẩm</th>
    <th>Hình ảnh</th>
    <th>Giá sản phẩm</th>
    <th>Mã sản phẩm</th>
    <th>Bảo hành (tháng)</th>
    <!-- <th>Số lượng kho</th> -->
    <!-- <th>Số lượng bán</th> -->
    <th>Danh mục</th>
    <th>Thương hiệu</th>
    <th>Mô tả</th>
    <!-- <th>Tình trạng</th> -->
    <th>Quản lý</th>

  </tr>
  <?php
  $i = 0;
  while ($row = mysqli_fetch_array($query_lietke_sp)) {
    $i++;
  ?>
    <tr>
      <td><?php echo $i ?></td>
      <td><?php echo $row['tensanpham'] ?></td>
      <td><img src="./modules/quanlysp/uploads/<?php echo $row['hinhanh'] ?>" width="150px"></td>
      <td><?php echo $row['giasp'] ?></td> 
      <td><?php echo $row['masp'] ?></td>
       <td><?php echo $row['baohanh'] ?></td>
      <!-- <td><!?php echo $row['soluong'] ?></td> -->
      <!--td><!?php echo $row['soluongban'] ?></td-->
      <td><?php echo $row['tendanhmuc'] ?></td>
      <td><?php echo $row['tenthuonghieu'] ?></td>
     <td>
  <div style="max-width: 250px; overflow-wrap: break-word; word-break: break-word;">
    <?php echo strip_tags(mb_strimwidth($row['mota'], 0, 150, "...")) ?>
  </div>
</td>


      <!--td><!?php if ($row['tinhtrang'] == 1) {
            echo 'Còn hàng';
          } else {
            echo 'Hết hàng';
          }
          ?>

      </!--td-->

      <td>
        <a class="Xoa" href="/admincf/modules/quanlysp/xuly.php?idsanpham=<?php echo $row['id_sanpham'] ?>" title="Xóa">
          <i class="fas fa-trash-alt"></i>
        </a>
        <a class="Sua" href="?action=quanlysp&query=sua&idsanpham=<?php echo $row['id_sanpham'] ?>" title="Sửa">
          <i class="fas fa-edit"></i>
        </a>
      </td>

    </tr>
  <?php
  }
  ?>
</table>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<style>
 .chitietdm, .chitietsp {
  text-align: center;
  font-size: 26px;
  font-weight: 600;
  margin: 30px 0 20px;
  color: #2c3e50;
}

table {
  width: 100%;
  border-collapse: collapse;
  background-color: #fff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  overflow: hidden;
}

th {
  background: linear-gradient(to right, #3498db, #2980b9);
  color: white;
  padding: 14px 10px;
  text-transform: uppercase;
  font-size: 14px;
  border-bottom: 2px solid #ddd;
}

td {
  padding: 12px;
  border-bottom: 1px solid #eee;
  text-align: center;
  color: #333;
  font-size: 14px;
}
tr:hover {
        background-color: #e6f7ff;
    }
td img {
  max-width: 90px;
  height: auto;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

a.Xoa, a.Sua {
  display: inline-block;
  margin: 2px;
  padding: 6px 12px;
  border-radius: 6px;
  text-decoration: none;
  color: #fff;
  font-weight: 500;
  font-size: 13px;
  transition: 0.3s ease-in-out;
}

a.Xoa {
  background-color: #e74c3c;
}

a.Xoa:hover {
  background-color: #c0392b;
}

a.Sua {
  background-color: #27ae60;
}

a.Sua:hover {
  background-color: #1e8449;
}

.fas {
  margin-right: 5px;
}

.search-form-right {
  text-align: right;
  margin: 0 0 20px;
}

.search-form-right input[type="text"] {
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  width: 250px;
  font-size: 14px;
}

.search-form-right button {
  padding: 8px 12px;
  background-color: #3498db;
  border: none;
  color: white;
  border-radius: 6px;
  cursor: pointer;
  margin-left: 5px;
}

.search-form-right button:hover {
  background-color: #2980b9;
}

</style>