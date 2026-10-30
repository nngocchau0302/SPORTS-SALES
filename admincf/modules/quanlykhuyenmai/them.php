<p class="chitietdm">Thêm Khuyến Mãi Cho Sản Phẩm</p>
<table class="center-table">
  <form method="POST" action="/admincf/modules/quanlykhuyenmai/xuly.php">
    <tr>
      <td>Sản Phẩm</td>
      <td>
        <select name="id_sanpham">
          <?php
          $sql_sp = "SELECT * FROM tbl_sanpham";
          $query_sp = mysqli_query($mysqli, $sql_sp);
          while ($row = mysqli_fetch_array($query_sp)) {
              echo '<option value="'.$row['id_sanpham'].'">'.$row['tensanpham'].'</option>';
          }
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>Tên Khuyến Mãi</td>
      <td><input type="text" name="tenkm" placeholder="Tên khuyến mãi" required></td>
    </tr>
    <tr>
      <td>Phần Trăm Giảm</td>
      <td><input type="number" name="phantramgiam" placeholder="Phần trăm giảm" min="0" max="100" required></td>
    </tr>
    <tr>
      <td>Ngày Bắt Đầu</td>
      <td><input type="date" name="ngaybatdau" required></td>
    </tr>
    <tr>
      <td>Ngày Kết Thúc</td>
      <td><input type="date" name="ngayketthuc" required></td>
    </tr>
    <tr>
      <td colspan="2" class="center-submit">
        <input class="danhmuc" type="submit" name="themkmai" value="Thêm Khuyến Mãi">
      </td>
    </tr>
  </form>
</table>

<style>
  .chitietdm {
    text-align: center;
    font-size: 28px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 30px;
  }

  .center-table {
    margin: 0 auto;
    width: 60%;
    border-collapse: collapse;
    background-color: #ffffff;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
  }

  .center-table td {
    padding: 15px;
    border: 1px solid #e0e0e0;
  }

  .center-table input[type="text"],
  .center-table input[type="number"],
  .center-table input[type="date"],
  .center-table select {
    width: 100%;
    padding: 5px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .center-submit {
    text-align: center;
    padding: 20px;
  }

  th {
    background-color: #b7e1f8;
    color: #333;
    text-transform: uppercase;
    font-weight: bold;
    padding: 10px;
  }

  tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  .danhmuc {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 12px 25px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .danhmuc:hover {
    background-color: #0056b3;
  }
</style>