<div class="container-next">
  <!--Responsive...--->
  <div class="arrow-steps clearfix">
    <div class="step done"> <span> <a href='index.php?quanly=giohang'>Giỏ hàng</a> </span></div>
    <div class="step current"> <span> <a href='index.php?quanly=vanchuyen'>Vận chuyển</a> </span></div>
    <div class="step "> <span> <a href='index.php?quanly=thongtinthanhtoan'>Thanh toán</a> </span></div>
    <div class="step "> <span> <a href='index.php?quanly=donhangdadat'>Lịch sử đơn hàng</a> </span></div>

  </div>
  <div class="row">
    <div class="containerContent">
      <?php
      function tinh_phi_vanchuyen($province)
      {
        $province = mb_strtolower(trim($province), 'UTF-8');
        return ($province === 'cần thơ') ? 10000 : 30000;
      }

      if (isset($_POST['themvanchuyen']) || isset($_POST['capnhatvanchuyen'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $note = $_POST['note'];
        $province = $_POST['province'];
        $shipping_fee = tinh_phi_vanchuyen($province);
        $id_dangky = $_SESSION['id_khachhang'];

        if (isset($_POST['themvanchuyen'])) {
          $sql = "INSERT INTO tbl_shipping(name,phone,address,note,province,shipping_fee,id_dangky) 
                  VALUES('$name','$phone','$address','$note','$province','$shipping_fee','$id_dangky')";
          $msg = "Thêm vận chuyển thành công";
        } else {
          $sql = "UPDATE tbl_shipping SET name='$name',phone='$phone',address='$address',note='$note',
                  province='$province',shipping_fee='$shipping_fee' WHERE id_dangky='$id_dangky'";
          $msg = "Cập nhật vận chuyển thành công";
        }

        if (mysqli_query($mysqli, $sql)) {
          echo "<script>alert('$msg')</script>";
        }
      }

      $id_dangky = $_SESSION['id_khachhang'];
      $sql_get = mysqli_query($mysqli, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");
      $row = mysqli_fetch_array($sql_get);
      $name = $row['name'] ?? '';
      $phone = $row['phone'] ?? '';
      $address = $row['address'] ?? '';
      $note = $row['note'] ?? '';
      $province_selected = $row['province'] ?? '';
      ?>


      <div class="container mx-auto p-4">
        <div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
          <h2 class="text-center text-2xl font-semibold mb-6">Thông tin vận chuyển</h2>
          <form action="" autocomplete="off" method="POST">
            <div class="mb-4">
              <label class="block text-gray-700">Họ và tên</label>
              <input class="w-full px-4 py-2 border rounded-md bg-gray-100" type="text" name="name" value="<?php echo $name ?>">
            </div>
            <div class="mb-4">
              <label class="block text-gray-700">Phone</label>
              <input type="text" name="phone" value="<?php echo $phone ?>" class="w-full px-4 py-2 border rounded-md bg-gray-100" />
            </div>
            <div class="mb-4">
              <label class="block text-gray-700">Địa chỉ</label>
              <input type="text" name="address" maxlength="100" value="<?php echo $address ?>" class="w-full px-4 py-2 border rounded-md bg-gray-100" />
            </div>
            <div class="mb-4">
              <label class="block text-gray-700">Tỉnh/Thành phố</label>
              <input type="text" name="province" value="<?php echo $province_selected ?>" class="w-full px-4 py-2 border rounded-md bg-gray-100" />
            </div>
            <div class="mb-4">
              <label class="block text-gray-700">Ghi chú</label>
              <input type="text" name="note" value="<?php echo $note ?>" class="w-full px-4 py-2 border rounded-md bg-gray-100" />
            </div>
            <div class="text-center">
              <?php if ($name == '' && $phone == '') { ?>
                <button type="submit" name="themvanchuyen" class="px-6 py-2 bg-blue-500 text-white rounded-md">Thêm vận chuyển</button>
              <?php } else { ?>
                <button type="submit" name="capnhatvanchuyen" class="px-6 py-2 bg-blue-500 text-white rounded-md">Cập nhật vận chuyển</button>
              <?php } ?>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
  <p class="title_giohang">Giỏ hàng của bạn <?php
                                            if (isset($_SESSION['dangky'])) {
                                              echo $_SESSION['dangky'];
                                            }
                                            ?> </p>
  <div class="row">
    <?php

    if (isset($_SESSION['cart'])) {
    }
    ?>
    <table style="width:100%; text-align:center; border-collapse:collapse; margin-bottom: 20px;" border="1">
      <thead class="bg-gray-800 text-white">
        <tr>
          <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Id</th>
          <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Mã sp</th>
          <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Tên sản phẩm</th>
          <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Hình ảnh</th>
          <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Kích thước</th>
          <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Số lượng</th>
          <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Giá sản phẩm</th>
          <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Thành Tiền</th>

        </tr>
      </thead>
      <?php
      if (isset($_SESSION['cart'])) {
        $i = 0;
        $tongtien = 0;
        foreach ($_SESSION['cart'] as $cart_item) {
          $thanhtien =  $cart_item['soluong'] * $cart_item['giasp'];
          $tongtien += $thanhtien;
          $i++;
      ?>


          <tr class="border-b border-gray-300">
            <td class="py-3 px-4 border border-gray-300"><?php echo $i; ?></td>
            <td class="py-3 px-4 border border-gray-300"><?php echo $cart_item['masp'] ?></td>
            <td class="py-3 px-4 border border-gray-300"><?php echo $cart_item['tensanpham'] ?></td>
            <td class="py-3 px-4 border border-gray-300"><img src="admincf/modules/quanlysp/uploads/<?php echo $cart_item['hinhanh'] ?>" width="150px" alt="Image of <?php echo $cart_item['tensanpham'] ?>"></td>
            <!-- Cột Kích thước -->
            <td class="py-3 px-4 border border-gray-300">
              <?php
              $id_danhmuc = $cart_item['id_danhmuc'];
              if (in_array($id_danhmuc, [2, 7, 8, 9])) {
                echo $cart_item['size'];
              } else {
                echo "Không có";
              }
              ?>
            </td>
            <td>

              <?php echo $cart_item['soluong'] ?>

            </td>
            <td class="py-3 px-4 border border-gray-300"><?php echo number_format($cart_item['giasp'], 0, ',', '.') . 'vnđ' ?></td>
            <td class="py-3 px-4 border border-gray-300"><?php echo number_format($thanhtien, 0, ',', '.') . 'vnđ'  ?></td>

          </tr>

        <?php
        }

        // Lấy phí vận chuyển từ DB
        $sql_shipping = mysqli_query($mysqli, "SELECT shipping_fee FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");
        $row_shipping = mysqli_fetch_assoc($sql_shipping);
        $shipping_fee = $row_shipping ? $row_shipping['shipping_fee'] : 0;
        $tongtien += $shipping_fee;

        // Hiển thị phí vận chuyển
        ?>
        <tr class="bg-gray-100">
          <td colspan="8" class="py-3 px-4 border border-gray-300">
            <div class="flex justify-between items-center">
              <p class="font-semibold">Phí vận chuyển: <?php echo number_format($shipping_fee, 0, ',', '.') . ' vnđ'; ?></p>
            </div>
            
            <div class="flex justify-between items-center">
              <p class="font-semibold">Tổng tiền: <?php echo number_format($tongtien, 0, ',', '.') . ' vnđ'; ?></p>
            </div>
            <div style="clear:both;"></div>
            <?php if (isset($_SESSION['dangky'])) { ?>
              <p><a class="dathang" href="index.php?quanly=thongtinthanhtoan" style=" color: #1A3636; background: rgb(185, 203, 222); border-radius: 20px; padding: 7px 47px;">Thanh toán</a></p>
            <?php } else { ?>
              <p><a class="dathang" href="index.php?quanly=dangky">Đăng ký để đặt hàng</a></p>
            <?php } ?>
          </td>
        </tr>
      <?php

      } else {
      ?>
        <tr>
          <td colspan="8">
            <p>Hiện tại giỏ hàng trống</p>
          </td>

        </tr>




      <?php
      }
      ?>
    </table>
  </div>


</div>
</div>
<!-- <style>
  @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");

  :root {
    --primary-color: rgb(118, 149, 184);
    --primary-color-light: rgb(151, 207, 242);
    --secondary-color: rgb(243, 240, 240);
    --text-dark: #1f1f20;
    --text-light: #030303;
    --white: #ffffff;
    --max-width: 1200px;
  }

  .dathang {
    color: #1A3636;
    background: rgb(185, 203, 222);
    border-radius: 20px;
    padding: 7px 47px;
  }

  .maincontent {
    /* background: url(/images/AD1.png); */
    border: 1px solid #ffffff;
    height: auto;
    width: 100%;
    float: right;
    border-radius: 10px;
    margin: auto;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
  }

  .containerContent {
    margin-top: 50px;
    width: 100%;
    padding-block: 2rem;
    max-width: 500px;
    margin-inline: auto;
  }

  .containerContent h1 {
    margin-left: 7%;
    margin-bottom: 1rem;
    font-size: 1.8rem;
    font-weight: 300;
    color: var(--text-dark);
  }

  .containerContent form {
    display: grid;
    gap: 10px;
  }

  .containerContent label {
    font-size: 0.9rem;
    color: var(--text-dark);
  }

  /* .inputRow {
    margin-bottom: 1rem;
    width: 100%;
    padding: 0.5rem 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    background-color: var(--secondary-color);
    border-radius: 5px;
  } */

  .containerContent input {
    width: 100%;
    outline: none;
    border: none;
    font-size: 1rem;
    color: var(--text-dark);
    background-color: transparent;
  }

  .containerContent input::placeholder {
    color: var(--text-dark);
  }

  .containerContent button {
    max-width: 50%;
    margin-left: 20%;
    margin-block: 1rem 2rem;
    padding: 0.75rem 2rem;
    outline: none;
    border: none;
    font-size: 1rem;
    color: var(--white);
    background-color: var(--primary-color);
    border-radius: 5rem;
  }

  .containerContent button:hover {
    background-color: var(--primary-color-light);
  }

  .containerContent h6 {
    margin-bottom: 2rem;
    font-size: 1rem;
    font-weight: 400;
    color: var(--text-dark);
    text-align: center;
  }

  .logins {
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
  }

  .logins a {
    padding: 0.5rem 2.5rem;
    border: 2px solid var(--text-dark);
    border-radius: 5rem;
  }

  .logins a:hover {
    background: var(--secondary-color);
    border: 2px solid var(--primary-color);
  }

  .logins img {
    display: flex;
    max-width: 20px;
  }

  .containerContent p {
    color: var(--text-light);
    text-align: center;
  }

  .containerContent p a {
    text-decoration: none;
    font-weight: 500;
    color: #164863;
  }
</style> -->