<body>
    <div class="container">
        <h1>Thêm sản phẩm</h1>
        <form method="POST" action="/admincf/modules/quanlysp/xuly.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>Tên sản phẩm</label>
                <input type="text" name="tensanpham" />
            </div>
            <div class="form-group">
                <label>Mã sản phẩm</label>
                <input type="text" name="masp" />
            </div>
            <div class="form-group">
                <label>Giá sản phẩm</label>
                <input type="text" name="giasp" />
            </div>
            <div class="form-group">
                <label>Số lượng</label>
                <input type="text" name="soluong" />
            </div>
            <div class="form-group">
                <label>Hình ảnh</label>
                <div class="file-input">
                    <input type="file" name="hinhanh" />
                    <span>Không có tệp nào được chọn</span>
                </div>
            </div>
            <div class="form-group">
                <label>Danh mục sản phẩm</label>

                <select name="danhmuc">
                    <?php
                    $sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
                    $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
                    while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
                    ?>
                        <option value="<?php echo $row_danhmuc['id_danhmuc'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></option>
                    <?php } ?>

                </select>
            </div>
            <!-- Thương  hiệu-->
            <div class="form-group">
                <label>Thương hiệu</label>

                <select name="thuonghieu">
                    <?php
                    $sql_thuonghieu = "SELECT * FROM tbl_thuonghieu ORDER BY id_thuonghieu DESC";
                    $query_thuonghieu = mysqli_query($mysqli, $sql_thuonghieu);
                    while ($row_thuonghieu = mysqli_fetch_array($query_thuonghieu)) {
                    ?>
                        <option value="<?php echo $row_thuonghieu['id_thuonghieu'] ?>"><?php echo $row_thuonghieu['tenthuonghieu'] ?></option>
                    <?php } ?>

                </select>
            </div>
            
            <div class="form-group">
                <label>Tình trạng</label>
                <select name="tinhtrang">
                    <option value="1">Còn hàng</option>
                    <option value="0">Hết hàng</option>
                </select>
            </div>

            <div class="form-group">
                <label>Thời hạn bảo hành (tháng)</label>
                <input type="number" name="baohanh" min="0" value="0">
            </div>

            <div class="form-group">
                <label>Kích thước</label>
                <div class="size-group">
                    <div class="size-item">
                        <label>S:</label>
                        <input type="number" name="size[S]" min="0" value="0">
                    </div>
                    <div class="size-item">
                        <label>M:</label>
                        <input type="number" name="size[M]" min="0" value="0">
                    </div>
                    <div class="size-item">
                        <label>L:</label>
                        <input type="number" name="size[L]" min="0" value="0">
                    </div>
                    <div class="size-item">
                        <label>XL:</label>
                        <input type="number" name="size[XL]" min="0" value="0">
                    </div>
                    <div class="size-item">
                        <label>38:</label>
                        <input type="number" name="size[38]" min="0" value="0">
                    </div>
                    <div class="size-item">
                        <label>39:</label>
                        <input type="number" name="size[39]" min="0" value="0">
                    </div>
                    <div class="size-item">
                        <label>40:</label>
                        <input type="number" name="size[40]" min="0" value="0">
                    </div>
                    <div class="size-item">
                        <label>41:</label>
                        <input type="number" name="size[41]" min="0" value="0">
                    </div>
                    <div class="size-item">
                        <label>42:</label>
                        <input type="number" name="size[42]" min="0" value="0">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Mô tả sản phẩm</label>
                <textarea name="mota" id="mota"></textarea>

            </div>
            <div class="submit-btn">
                <input class="Them" type="submit" name="Themsanpham" value="Thêm sản phẩm">
            </div>
        </form>
    </div>
</body>
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
        background-color: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
    }

    .container {
        background-color: #ffffff;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 768px;
        margin: 0 auto;
        /* Center the container */
    }

    h1 {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 24px;
    }

    .form-group {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-group label {
        background-color: #e5e7eb;
        padding: 8px;
        border: 1px solid #d1d5db;
    }

    .form-group input,
    .form-group select {
        padding: 8px;
        border: 1px solid #d1d5db;
    }

    .file-input {
        display: flex;
        align-items: center;
    }

    .file-input input {
        padding: 8px;
        border: 1px solid #d1d5db;
    }

    .file-input span {
        margin-left: 8px;
    }

    .size-group {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .size-item {
        display: flex;
        align-items: center;
    }

    .size-item label {
        width: 32px;
    }

    .size-item input {
        flex: 1;
        padding: 8px;
        border: 1px solid #d1d5db;
    }

    .submit-btn {
        display: flex;
        justify-content: center;
    }

    .submit-btn button {
        background-color: #e5e7eb;
        padding: 8px 16px;
        border: 1px solid #d1d5db;
        cursor: pointer;
        border-radius: 4px;
    }

    .submit-btn button:hover {
        background-color: #d1d5db;
    }

    .Them {
        display: inline-block;
        width: 50%;
        padding: 10px;
        background-color: #164863;
        color: #EFECEC;
        border: none;
        cursor: pointer;
        border-radius: 8px;
        text-align: center;
        font-size: 1em;
        margin-top: 20px;
    }

    .Them:hover {
        background-color: #123a52;
    }
</style>
<!-- <p class="chitietsp">Thêm sản phẩm</p>
<table border="1" width="50%" >
 <form method="POST" action="/admincf/modules/quanlysp/xuly.php" enctype="multipart/form-data">
   <tr>
     <th>Tên sản phẩm</th>
     <td><input type="text" name="tensanpham"></td>

    </tr>
    <tr>
     <th>Mã sp</th>
     <td><input type="text" name="masp"></td>

    </tr>
    <tr>
     <th>Giá sp</th>
     <td><input type="text" name="giasp"></td>

    </tr>
    <tr>
     <th>Số lượng</th>
     <td><input type="text" name="soluong"></td>

    </tr>
    <tr>
      <th>Hình ảnh</th>
      <td><input type="file" name="hinhanh"></td>

    </tr>
    <tr>
     <th>Danh mục sản phẩm</th>
     <td>
      <select name="danhmuc">
        <!?php
          $sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
          $query_danhmuc = mysqli_query($mysqli,$sql_danhmuc);
          while($row_danhmuc = mysqli_fetch_array($query_danhmuc)){
        ?>
         <option value="<!?php echo $row_danhmuc['id_danhmuc'] ?>"><!?php echo $row_danhmuc['tendanhmuc'] ?></option>  
         <!?php } ?>
         </select>
     </td>

    </tr>
    <tr>
     <th>Tình trạng</th>
     <td>
      <select name="tinhtrang">
            <option value="1">Kích hoạt</option>
            <option value="0">Ẩn</option> 
         </select>
     </td>

    </tr>
    <tr>

      <td colspan="2" class="center-submit"><input class="Them" type="submit" name="Themsanpham" value="Thêm sản phẩm"></td>
    </tr>
  </form>
  <style>
        /* body {
            background-color: #f3f4f6;
            font-family: Arial, sans-serif;
        } */

        /* .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        } */

        .chitietsp {
            display: block;
            font-size: 1.5em;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        input[type="text"], input[type="file"], select {
            width: 80%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }

        .center-submit {
            text-align: center;
        }

        .Them {
            display: inline-block;
            width: 50%;
            padding: 10px;
            background-color: #164863;
            color: #EFECEC;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            text-align: center;
            font-size: 1em;
            margin-top: 20px;
        }

        .Them:hover {
            background-color: #123a52;
        }
    </style>
</table> -->