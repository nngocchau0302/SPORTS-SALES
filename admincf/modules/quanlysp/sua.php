<?php
$sql_sua_sp = "SELECT * FROM tbl_sanpham WHERE id_sanpham='$_GET[idsanpham]' LIMIT 1";
$query_sua_sp = mysqli_query($mysqli, $sql_sua_sp);

// Lấy danh sách thương hiệu
$sql_thuonghieu = "SELECT * FROM tbl_thuonghieu ORDER BY thutu DESC";
$query_thuonghieu = mysqli_query($mysqli, $sql_thuonghieu);
?>


<p class="form-title">Sửa sản phẩm</p>

<div class="edit-product-container">
  <?php while ($row = mysqli_fetch_array($query_sua_sp)) { ?>
    <form method="POST" action="/admincf/modules/quanlysp/xuly.php?idsanpham=<?php echo $row['id_sanpham'] ?>" enctype="multipart/form-data" class="edit-form">

      <div class="form-group">
        <label>Tên sản phẩm</label>
        <input type="text" name="tensanpham" value="<?php echo $row['tensanpham'] ?>">
      </div>

      <div class="form-group">
        <label>Mã SP</label>
        <input type="text" name="masp" value="<?php echo $row['masp'] ?>">
      </div>

      <div class="form-group">
        <label>Giá sản phẩm</label>
        <input type="text" name="giasp" value="<?php echo $row['giasp'] ?>">
      </div>

      <div class="form-group">
        <label>Số lượng</label>
        <input type="text" name="soluong" value="<?php echo $row['soluong'] ?>">
      </div>

      <div class="form-group">
        <label>Hình ảnh</label>
        <input type="file" name="hinhanh">
        <div class="image-preview">
          <img src="./modules/quanlysp/uploads/<?php echo $row['hinhanh'] ?>" alt="Hình ảnh" width="120">
        </div>
      </div>

      <div class="form-group">
        <label>Danh mục sản phẩm</label>
        <select name="danhmuc">
          <?php
          $sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
          $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
          while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
            $selected = ($row_danhmuc['id_danhmuc'] == $row['id_danhmuc']) ? 'selected' : '';
            echo '<option value="' . $row_danhmuc['id_danhmuc'] . '" ' . $selected . '>' . $row_danhmuc['tendanhmuc'] . '</option>';
          }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label>Thương hiệu</label>
        <select name="thuonghieu">
          <?php
          while ($row_th = mysqli_fetch_array($query_thuonghieu)) {
            $selected = ($row_th['id_thuonghieu'] == $row['id_thuonghieu']) ? 'selected' : '';
            echo '<option value="' . $row_th['id_thuonghieu'] . '" ' . $selected . '>' . $row_th['tenthuonghieu'] . '</option>';
          }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label>Tình trạng</label>
        <select name="tinhtrang">
          <option value="1" <?php echo ($row['tinhtrang'] == 1) ? 'selected' : ''; ?>>Còn hàng</option>
          <option value="0" <?php echo ($row['tinhtrang'] == 0) ? 'selected' : ''; ?>>Hết hàng</option>
        </select>
      </div>
      <div class="form-group">
        <label>Bảo hành (tháng) </label>
        <input type="text" name="baohanh" value="<?php echo $row['baohanh'] ?>">
      </div>
      <div class="form-group">
        <label>Mô tả sản phẩm</label>
        <textarea name="mota" id="mota"><?php echo $row['mota'] ?></textarea>

      </div>

      <div class="form-actions">
        <input type="submit" class="btn-submit" name="suasanpham" value="Sửa sản phẩm">
      </div>
    </form>
  <?php } ?>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#mota'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'fontColor', 'fontBackgroundColor', '|',
                'alignment', '|',
                'link', 'insertImage', 'bulletedList', 'numberedList', '|',
                'undo', 'redo'
            ]
        })
        .catch(error => {
            console.error(error);
        });
</script>

<style>
  body {
    background: #f5f7fa;
    padding: 20px;
  }

  .form-title {
    text-align: center;
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 30px;
    color: #164863;
  }

  .edit-product-container {
    max-width: 700px;
    background-color: #fff;
    margin: 0 auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .edit-form {
    display: flex;
    flex-direction: column;
  }

  .form-group {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
  }

  .form-group label {
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
  }

  .form-group input[type="text"],
  .form-group select,
  .form-group input[type="file"] {
    padding: 10px 12px;
    font-size: 16px;
    border-radius: 6px;
    border: 1px solid #ccc;
    transition: border-color 0.3s ease;
  }

  .form-group input:focus,
  .form-group select:focus {
    border-color: #164863;
    outline: none;
  }

  .image-preview img {
    margin-top: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
  }

  .form-actions {
    text-align: center;
    margin-top: 20px;
  }

  .btn-submit {
    padding: 12px 24px;
    background-color: #164863;
    color: #fff;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .btn-submit:hover {
    background-color: #3e8e41;
  }
</style>