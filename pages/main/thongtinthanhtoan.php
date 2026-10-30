<div class="container-next">
    <!--Responsive...--->
    <div class="arrow-steps clearfix">
        <div class="step done"> <span> <a href='index.php?quanly=giohang'>Giỏ hàng</a> </span></div>
        <div class="step done"> <span> <a href='index.php?quanly=vanchuyen'>Vận chuyển</a> </span></div>
        <div class="step current"> <span> <a href='index.php?quanly=thongtinthanhtoan'>Thanh toán</a> </span></div>
        <div class="step"> <span> <a href='index.php?quanly=donhangdadat'>Lịch sử đơn hàng</a> </span></div>

    </div>

    <form action="/pages/main/xulythanhtoan.php" method="POST">
        <p class="title_giohang">Thông tin thanh toán</p>
        <div class="row">
            <!-- <div class="wrapper_sanpham"> -->
            <?php
            $id_dangky = $_SESSION['id_khachhang'];
            $sql_get_vanchuyen = mysqli_query($mysqli, "SELECT *FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");
            $count = mysqli_num_rows($sql_get_vanchuyen);
            if ($count > 0) {
                $row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);
                $name = $row_get_vanchuyen['name'];
                $phone = $row_get_vanchuyen['phone'];
                $address = $row_get_vanchuyen['address'];
                $note = $row_get_vanchuyen['note'];
            } else {

                $name = '';
                $phone = '';
                $address = '';
                $note = '';
            }
            ?>
            <div class="container mx-auto p-4">
                <div class="bg-white shadow-md rounded-lg p-6 mb-4">
                    <h4 class="text-2xl font-semibold mb-4 border-b pb-2">
                        Thông tin vận chuyển
                    </h4>
                    <ul class="mb-4">
                        <li class="mb-2">
                            Họ và tên người nhận:
                            <b>
                                <?php echo $name ?>
                            </b>
                        </li>
                        <li class="mb-2">
                            Số điện thoại:
                            <b>
                                <?php echo $phone ?>
                            </b>
                        </li>
                        <li class="mb-2">
                            Địa chỉ giao hàng:
                            <b>
                                <?php echo $address ?>
                            </b>
                        </li>
                        <li class="mb-2">
                            Ghi chú:
                            <b>
                                <?php echo $note ?>
                            </b>
                        </li>
                    </ul>
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
                                    <!-- <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Quản lý</th> -->
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
                                        <!-- <td class="py-3 px-4 border border-gray-300"><a class="text-red-500" href="pages/main/themgiohang.php?xoa=<!?php echo $cart_item['id'] ?>">Xóa</a></td> -->
                                    </tr>

                                <?php
                                }

                                // Lấy phí vận chuyển từ DB
                                $sql_shipping = mysqli_query($mysqli, "SELECT shipping_fee FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");
                                $row_shipping = mysqli_fetch_assoc($sql_shipping);
                                $shipping_fee = $row_shipping ? $row_shipping['shipping_fee'] : 0;
                                $tongtien += $shipping_fee; // ✅ Tổng tiền bao gồm phí ship
                                ?>
                                <tr class="bg-gray-100">
                                    <td colspan="8" class="py-3 px-4 border border-gray-300">
                                        <div class="flex justify-between items-center">
                                            <p class="font-semibold">Phí vận chuyển: <?php echo number_format($shipping_fee, 0, ',', '.') . ' vnđ'; ?></p>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="font-semibold">Tổng tiền: <?php echo number_format($tongtien, 0, ',', '.') . 'vnđ'  ?></p>

                                        </div>
                                        <div style="clear:both;"></div>


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
                    <div class="bg-white shadow-md rounded-lg p-6 mb-4">
                        <h4 class="text-2xl font-semibold mb-4 border-b pb-2">
                            Phương thức thanh toán
                        </h4>
                        <form action="" method="POST">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input checked="" class="form-check-input" id="exampleRadios1" name="payment" type="radio" value="tienmat" />
                                    <img alt="Money logo" height="43" src="/images/-money-logo.png" width="43" />
                                    <label class="ml-2 form-check-label" for="exampleRadios1">
                                        Thanh toán khi nhận hàng
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input class="form-check-input" id="exampleRadios2" name="payment" type="radio" value="momo_atm" />
                                    <img alt="Bank transfer logo" height="40" src="/images/circle-logo.png" width="40" />
                                    <label class="ml-2 form-check-label" for="exampleRadios2">
                                        Momo ATM
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input class="form-check-input" id="exampleRadios3" name="payment" type="radio" value="momo_qr" />
                                    <img alt="Bank transfer logo" height="40" src="/images/Momo-QR-Code1.png" width="30" style="margin-left:5px;" />
                                    <label class="ml-3 form-check-label" for="exampleRadios3">
                                        Momo QR_Code
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input class="form-check-input" id="exampleRadios4" name="payment" type="radio" value="vnpay" />
                                    <img alt="VnPay logo" height="35" src="/images/Icon-VNPAY-QR.webp" width="35" style="margin-left:5px;" />
                                    <label class="ml-2 form-check-label" for="exampleRadios4">
                                        VnPay
                                    </label>
                                </div>
                                <div class="mb-6">
                                    <p class="text-xl font-semibold text-gray-800">Tổng tiền cần thanh toán: <span class="text-red-600"><?php echo number_format($tongtien, 0, ',', '.') . 'vnđ'  ?></span></p>
                                </div>
                        </form>
                        <!-- <form style="margin-top: 10px;" class="" method="POST" target="_blank" enctype="application/x-www-form-urlencoded"
                            action="pages/main/xulythanhtoanmomo.php ">
                            <div class="flex items-center">
                                <input type="hidden" value="<!?php echo $tongtien ?>" name="tongtien">
                                <input class="bg-pink-300 text-white py-3 px-6 rounded-lg shadow-md transition duration-30" name="momo" type="submit" value="Thanh toán MOMO QRCode" />


                            </div>
                        </form>
                        <form style="margin-top: 15px; margin-bottom: 5px;" class="" method="POST" target="_blank" enctype="application/x-www-form-urlencoded"
                            action="pages/main/xulythanhtoanmomo_atm.php ">

                            <div class="flex items-center">
                                <input type="hidden" value="<!?php echo $tongtien ?>" name="tongtien">
                                <input class="bg-pink-300 text-white py-3 px-6 rounded-lg shadow-md transition duration-30" name="momo" type="submit" value="Thanh toán MOMO ATM" />


                            </div>
                        </form> -->

                        <button class="bg-blue-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-blue-600 transition duration-30" name="redirect" type="submit">
                            Thanh toán ngay
                        </button>
                    </div>
                </div>

            </div>
        </div>





        <style>
            .thanhtoanngay {
                margin: 30px 10px;
                padding: 12px 24px;
                background-color: #DDF2FD;
                color: #164863;
                position: relative;
                border-radius: 6px;
                overflow: hidden;
                z-index: 1;
                cursor: pointer;
            }

            .cotvanchuyen {
                float: left;
                width: 65%;
                margin-left: 30px;

            }

            .cotthanhtoan {
                float: right;
                width: 25%;
                margin: 11px;

            }

            .wrapper_sanpham {

                margin-top: 50px;
                height: auto;
                width: 100%;
            }

            .custom-control custom-radio {
                margin: 15px;
            }

            /* .thongtinvanchuyen {
        text-align: center;
    } */
        </style>