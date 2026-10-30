<?php
// Xử lý lọc và tìm kiếm
$filter_status = isset($_POST['filter_status']) ? $_POST['filter_status'] : null;
$tukhoa = isset($_POST['tukhoa']) ? mysqli_real_escape_string($mysqli, $_POST['tukhoa']) : '';

$sql_lietke_dh = "SELECT * FROM tbl_cart, tbl_dangky 
                  WHERE tbl_cart.id_khachhang = tbl_dangky.id_dangky";

// Thêm điều kiện tìm kiếm nếu có
if (!empty($tukhoa)) {
  $sql_lietke_dh .= " AND (tbl_cart.code_cart LIKE '%$tukhoa%' OR tbl_cart.cart_date LIKE '%$tukhoa%')";
}

// Thêm điều kiện lọc theo trạng thái nếu có
if ($filter_status !== null && in_array($filter_status, ['3', '1', '2'])) {
    $sql_lietke_dh .= " AND tbl_cart.cart_status = '$filter_status'";
}

$sql_lietke_dh .= " ORDER BY tbl_cart.id_cart DESC";

$query_lietke_dh = mysqli_query($mysqli, $sql_lietke_dh);
?>

<p class="chitietdh">Liệt kê đơn hàng</p>
<div class="search-form-right">
  <form method="POST" action="">
    <input type="text" name="tukhoa" placeholder="Nhập mã đơn hoặc ngày đặt">
     <select name="filter_status">
      <option value="">-- Tất cả trạng thái --</option>
      <option value="1" <?php if ($filter_status === '1') echo 'selected'; ?>>Chờ xác nhận</option>
      <option value="2" <?php if ($filter_status === '2') echo 'selected'; ?>>Đang vận chuyển</option>
      <option value="3" <?php if ($filter_status === '3') echo 'selected'; ?>>Đã giao hàng thành công</option>
    </select>
    <button type="submit" name="timkiem">
      <i class="fas fa-search"></i>
    </button>
  </form>
</div>

<table style="width:100%" border="1">
  <tr>
    <th>Id</th>
    <th>Mã đơn hàng</th>
    <th>Tên khách hàng</th>
    <th>Địa chỉ</th>
    <th>Email</th>
    <th>Số điện thoại</th>
    <th>Tình trạng</th>
    <th>Ngày đặt</th>
    <th>Quản lý</th>

  </tr>
  <?php
  $i = 0;
  while ($row = mysqli_fetch_array($query_lietke_dh)) {
    $i++;
  ?>
    <tr>
      <td><?php echo $i ?></td>
      <td><?php echo $row['code_cart'] ?></td>
      <td><?php echo  $row['tenkhachhang'] ?></td>
      <td><?php echo  $row['diachi'] ?></td>
      <td><?php echo  $row['email'] ?></td>
      <td><?php echo  $row['dienthoai'] ?></td>
      <td>
        <?php
        if ($row['cart_status'] == 1) {
          echo '<span style="color:blue;" class="text">Chờ xác nhận</span>';
        } elseif ($row['cart_status'] == 2) {
          echo '<span style="color:#EE7600;" class="text">Đang vận chuyển</span>';
        } elseif ($row['cart_status'] == 3){
          echo '<span style="color:green;" class="text">Đã giao hàng thành công</span>';
        }
        ?>
      </td>
      <td><?php echo $row['cart_date'] ?></td>
      <td>
        <a class="btn-icon view" href="index.php?action=donhang&query=xemdonhang&code=<?php echo $row['code_cart']; ?>" title="Xem đơn hàng">
          <i class="fa fa-eye"></i>
        </a>
        <a class="btn-icon print" href="index.php?action=donhang&query=indonhang&code=<?php echo $row['code_cart']; ?>" title="In đơn hàng">
          <i class="fa fa-print"></i>
        </a>
      </td>

    </tr>
  <?php
  }
  ?>
</table>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .chitietdh {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }

    .search-form-right {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .search-form-right form {
        display: flex;
        gap: 8px;
    }

    .search-form-right input[type="text"] {
        padding: 8px 12px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 250px;
    }

    .search-form-right button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
    }

    .search-form-right button:hover {
        background-color: #45a049;
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


   th {
  background: linear-gradient(to right, #3498db, #2980b9);
  color: white;
  padding: 14px 10px;
  text-transform: uppercase;
  font-size: 14px;
  border-bottom: 2px solid #ddd;
}

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
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

    .btn-icon.print {
        background-color: #28a745;
        color: white;
    }

    .btn-icon.view:hover {
        background-color: #138496;
    }

    .btn-icon.print:hover {
        background-color: #218838;
    }

    .text {
        font-weight: bold;
    }

     .search-form-right select {
    padding: 8px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
  }
</style>