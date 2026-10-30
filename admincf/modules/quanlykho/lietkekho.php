<?php
// $dieukien = "";

// // Kiểm tra nếu bấm nút "Lọc"
// if (isset($_POST['loc_tonkho'])) {
//   $loai = $_POST['loai_tonkho'];

//   if ($loai == 'het') {
//     $dieukien = " WHERE tbl_sanpham.soluong = 1 ";
//   } elseif ($loai == 'saphethang') {
//     $dieukien = " WHERE tbl_sanpham.soluong < 5 AND tbl_sanpham.soluong > 2 ";
//   } else {
//     $dieukien = ""; // Tất cả
//   }
// } elseif (isset($_POST['timkiem'])) {
//   $tukhoa = mysqli_real_escape_string($mysqli, $_POST['tukhoa']);
//   $dieukien = " WHERE tbl_sanpham.tensanpham LIKE '%$tukhoa%' OR tbl_sanpham.masp LIKE '%$tukhoa%' ";
// }



$dieukien = "";

if (isset($_POST['loc_kho'])) {
    $where = [];
    
    // Từ khóa
    if (!empty($_POST['tukhoa'])) {
        $tukhoa = mysqli_real_escape_string($mysqli, $_POST['tukhoa']);
        $where[] = "(tbl_sanpham.tensanpham LIKE '%$tukhoa%' OR tbl_sanpham.masp LIKE '%$tukhoa%')";
    }

  
    // Trạng thái kho
    if (!empty($_POST['trangthai'])) {
        if ($_POST['trangthai'] == '1') {
            $where[] = "tbl_sanpham.soluong >= 5";
        } elseif ($_POST['trangthai'] == '2') {
            $where[] = "tbl_sanpham.soluong < 5 AND tbl_sanpham.soluong > 1";
        } elseif ($_POST['trangthai'] == '3') {
            $where[] = "tbl_sanpham.soluong <= 1";
        }
    }

    // Kết hợp điều kiện
    if (!empty($where)) {
        $dieukien = "WHERE " . implode(" AND ", $where);
    }
}

// Truy vấn
$sql_lietke_sp = "SELECT * FROM tbl_sanpham
JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
LEFT JOIN tbl_thuonghieu ON tbl_sanpham.id_thuonghieu = tbl_thuonghieu.id_thuonghieu
$dieukien
ORDER BY id_sanpham DESC";

$query_lietke_sp = mysqli_query($mysqli, $sql_lietke_sp);
?>


<p class="chitietsp">Quản lý kho - Tồn kho sản phẩm</p>
<!-- Bộ lọc tồn kho -->
<!-- <div style="margin: 20px 0; text-align:right;">
  <form method="POST" action="">

    <select name="loai_tonkho" id="loai_tonkho" style="padding: 6px 12px; margin-right: 10px;">
      <option value="tatca">Tất cả sản phẩm</option>
      <option value="het">Hết hàng</option>
      <option value="saphethang">Sắp hết hàng</option>
    </select>
    <button type="submit" name="loc_tonkho" style="padding: 6px 16px; background-color: #3498db; color: #fff; border: none; border-radius: 5px;">Lọc</button>
  </form>
</div> -->


<!-- Tìm kiếm -->
<!-- <div class="search-form-right">
  <form method="POST" action="">
    <input type="text" name="tukhoa" placeholder="Nhập mã hoặc tên sản phẩm">
    <button type="submit" name="timkiem">
      <i class="fas fa-search"></i> Tìm
    </button>
  </form>
</div> -->


<!-- Bộ lọc- tìm kiếm -->
<div class="search-container">
    <form method="POST" action="" class="search-form">
        <div class="form-row">
            <input type="text" name="tukhoa" placeholder="Nhập tên hoặc mã sản phẩm" class="form-input" />

            <!-- <select name="danhmuc" class="form-input">
                <option value="">-- Danh mục --</option>
                <!?php
                $sql_dm = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
                $query_dm = mysqli_query($mysqli, $sql_dm);
                while ($dm = mysqli_fetch_array($query_dm)) {
                    echo '<option value="' . $dm['id_danhmuc'] . '">' . $dm['tendanhmuc'] . '</option>';
                }
                ?>
            </select> -->

            <!-- <select name="thuonghieu" class="form-input">
                <option value="">-- Thương hiệu --</option>
                <!?php
                $sql_th = "SELECT * FROM tbl_thuonghieu ORDER BY id_thuonghieu DESC";
                $query_th = mysqli_query($mysqli, $sql_th);
                while ($th = mysqli_fetch_array($query_th)) {
                    echo '<option value="' . $th['id_thuonghieu'] . '">' . $th['tenthuonghieu'] . '</option>';
                }
                ?>
            </select> -->

            <select name="trangthai" class="form-input">
                <option value="">-- Trạng thái kho --</option>
                <option value="1">Còn hàng</option>
                <option value="2">Sắp hết hàng</option>
                <option value="3">Hết hàng</option>
            </select>

            <button type="submit" name="loc_kho" class="search-button">Lọc</button>
        </div>
    </form>
</div>
<table style="width:100%" border="1" style="border-collapse:collapse;">
  <tr>
    <th>Id</th>
    <th>Tên sản phẩm</th>
    <th>Hình ảnh</th>
    <th>Giá</th>
    <th>Mã sản phẩm</th>
    <th>Số lượng kho</th>
    <th>Số lượng bán</th>
    <th>Danh mục</th>
    <th>Thương hiệu</th>
    <th>Trạng thái kho</th>
    <th>Chi tiết </th>
  </tr>
  <?php
  $i = 0;
  while ($row = mysqli_fetch_array($query_lietke_sp)) {
    $i++;

  ?>
    <tr class="<?php echo $tonkho_class; ?>">
      <td><?php echo $i ?></td>
      <td><?php echo $row['tensanpham'] ?></td>
      <td><img src="./modules/quanlysp/uploads/<?php echo $row['hinhanh'] ?>" width="100px"></td>
      <td><?php echo number_format($row['giasp'], 0, ',', '.') . ' vnđ' ?></td>
      <td><?php echo $row['masp'] ?></td>
      <td><?php echo $row['soluong'] ?></td>
      <td><?php echo $row['soluongban'] ?></td>
      <td><?php echo $row['tendanhmuc'] ?></td>
      <td><?php echo $row['tenthuonghieu'] ?></td>
      <td>
        <strong style="color:
          <?php
          if ($row['soluong'] == 1) echo 'red';
          elseif ($row['soluong'] < 5) echo 'orange';
          else echo 'green';
          ?>">
          <?php
          if ($row['soluong'] == 1) echo 'Hết hàng';
          elseif ($row['soluong'] < 5) echo 'Sắp hết hàng';
          else echo 'Còn hàng';
          ?>
        </strong>
      </td>
     <td>
  <?php
    $danhmuc = trim(mb_strtolower($row['tendanhmuc'], 'UTF-8'));

    if ($danhmuc === 'vợt cầu lông') {
      echo '<a class="btn-icon view" href="index.php?action=xemchitiet&query=serivot&id=' . $row['id_sanpham'] . '" title="Xem seri vợt"><i class="fa fa-eye"></i></a>';
    } elseif (in_array($danhmuc, ['giày cầu lông', 'áo cầu lông', 'quần cầu lông', 'váy cầu lông'])) {
      echo '<a class="btn-icon view" href="index.php?action=xemchitietkichthuoc&query=kichthuoc&id=' . $row['id_sanpham'] . '" title="Xem tồn size"><i class="fa fa-eye"></i></a>';
    } else {
      echo '<span style="color: gray;">Không áp dụng</span>';
    }
  ?>
</td>



    </tr>
  <?php } ?>
</table>

<style>
  .chitietsp {
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

  td {
    padding: 20px;
    border-bottom: 1px solid #eee;
    text-align: center;
    color: #333;
    font-size: 15px;
  }
thead th, tbody td {
  padding: 12px;
  border: 1px solid #eee;
  text-align: center;
}

  th {
    background: linear-gradient(to right, #3498db, #2980b9);
    color: white;
    padding: 14px 10px;
    text-transform: uppercase;
    font-size: 14px;
    border-bottom: 2px solid #ddd;
  }

  tr:hover {
    background-color: #f2f2f2;
  }

  .tonkho-het {
    background-color: #ffcccc !important;
    color: #900;
  }

  .tonkho-it {
    background-color: #fff3cd !important;
    color: #856404;
  }

  .tonkho-ok {
    background-color: #d4edda !important;
    color: #155724;
  }

  td img {
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  .btn-icon {
    padding: 6px 8px;
    border-radius: 4px;
    margin-right: 4px;
    text-decoration: none;
    font-size: 14px;
    display: inline-block;
  }

  .btn-icon.view {
    background-color: #17a2b8;
    color: white;
  }

  .btn-icon.view:hover {
    background-color: #138496;
  }
/* form loc - tiềm kiếm */
 .search-container {
    margin: 20px 0;
    display: flex;
    justify-content: flex-end;
}

.search-form {
    width: 100%;
    max-width: 1000px;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: flex-end;
    margin-bottom: 10px;
}

.form-action {
    display: flex;
    justify-content: flex-end;
}

.form-input {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    min-width: 180px;
    font-size: 14px;
}

.search-button {
    padding: 8px 16px;
    background-color: #2980b9;
    color: white;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.search-button:hover {
    background-color: #1f6391;
}
</style>