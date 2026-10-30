<?php
if (isset($_GET['code'])) {
    $code_cart = $_GET['code'];

    // Lấy thông tin đơn hàng + thông tin vận chuyển
    $sql = "SELECT c.*, s.name AS shipping_name, s.phone AS shipping_phone, 
               s.address AS shipping_address, s.note AS shipping_note, 
               s.province, s.shipping_fee
        FROM tbl_cart AS c
        LEFT JOIN tbl_shipping AS s ON c.cart_shipping = s.id_shipping
        WHERE c.code_cart = '$code_cart' LIMIT 1";


    $query = mysqli_query($mysqli, $sql);
    if (!$query) {
        die("Lỗi SQL: " . mysqli_error($mysqli));
    }
    $row = mysqli_fetch_array($query);

    if ($row) {
        // Kiểm tra có sản phẩm vợt không
        $sql_check_racket = "SELECT sp.id_sanpham FROM tbl_cart_details AS cd 
                             JOIN tbl_sanpham AS sp ON cd.id_sanpham = sp.id_sanpham 
                             WHERE cd.code_cart = '$code_cart' AND sp.id_danhmuc = 1";
        $query_check = mysqli_query($mysqli, $sql_check_racket);
        $has_racket = mysqli_num_rows($query_check) > 0;
?>





        <!DOCTYPE html>
        <html lang="vi">

        <head>
            <meta charset="UTF-8">
            <title>In đơn hàng - Mã: <?php echo $row['code_cart']; ?></title>
            <style>
                h1,
                h2 {
                    text-align: center;
                    color: #2c3e50;
                    margin-bottom: 10px;
                    font-weight: 700;
                }

                p {
                    font-size: 16px;
                    margin: 5px 0;
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

                tbody tr {
                    border-bottom: 1px solid #e0e0e0;
                    transition: background-color 0.3s ease;
                }

                tbody tr:hover {
                    background-color: #f1f9f7;
                }

                td {
                    font-size: 15px;
                }

                strong {
                    color: rgb(33, 95, 176);
                }

                .print-btn {
                    text-align: center;
                    margin: 30px 0;
                }

                .print-btn button {
                    padding: 12px 30px;
                    font-size: 17px;
                    background-color: #164863;
                    color: white;
                    border: none;
                    border-radius: 30px;
                    cursor: pointer;
                    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.5);
                    transition: background-color 0.3s ease, box-shadow 0.3s ease;
                }

                .print-btn button:hover {
                    background-color: #219150;
                    box-shadow: 0 6px 16px rgba(33, 145, 80, 0.7);
                }

                @media print {
                    body {
                        background: none !important;
                        color: #000 !important;
                        max-width: 100% !important;
                        margin: 0 !important;
                    }

                    header,
                    footer,
                    .bannerad,
                    .admincf_list,
                    .header,
                    .print-btn {
                        display: none !important;
                    }

                    table {
                        box-shadow: none !important;
                        border-radius: 0 !important;
                    }
                }

                .header-invoice {
                    text-align: center;
                    margin-bottom: 20px;
                }

                .header-invoice .logo {
                    max-width: 120px;
                    height: auto;
                    margin-bottom: 10px;
                }

                .header-invoice h1 {
                    margin: 0;
                    font-size: 28px;
                    color: #27ae60;
                    font-weight: 700;
                }

                .header-invoice p {
                    margin: 2px 0;
                    font-size: 14px;
                    color: #555;
                }

                .signature {
                    margin-top: 50px;
                    width: 100%;
                    text-align: right;
                    font-size: 14px;
                    color: #333;
                }

                .signature p {
                    margin: 4px 0;
                }

                /* bổ sung */
                table th,
                table td {
                    text-align: center;
                }

                th.seri-col,
                td.seri-col {
                    display: <?php echo $has_racket ? 'table-cell' : 'none'; ?>;
                }
            </style>
        </head>

        <body>
            <div class="print-area">
                <!-- Logo và tên cửa hàng -->
                <div class="header-invoice">
                    <img src="/images/logoDC2..png" alt="Logo cửa hàng" class="logo">
                    <p>Địa chỉ: 126 Đ.Nguyễn Văn Cừ, Phường An Khánh, Quận Ninh Kiều , TP. Cần Thơ</p>
                    <p>Điện thoại: 0909 123 456</p>
                </div>


                <h1>Chi tiết đơn hàng</h1>
                <h2>Mã đơn hàng: <?php echo $row['code_cart']; ?></h2>
                <p><strong>Tên người nhận:</strong> <?php echo $row['shipping_name']; ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo $row['shipping_phone']; ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo $row['shipping_address']; ?></p>
                <p><strong>Tỉnh/Thành:</strong> <?php echo $row['province']; ?></p>
                <p><strong>Ghi chú:</strong> <?php echo $row['shipping_note']; ?></p>

                <p><strong>Phí vận chuyển:</strong>
                    <?php echo isset($row['shipping_fee']) ? number_format($row['shipping_fee'], 0, ',', '.') . ' đ' : 'Miễn phí'; ?>
                </p>
                <p><strong>Ngày đặt:</strong> <?php echo $row['cart_date']; ?></p>


                <table>
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                            <th class="seri-col">Số Seri</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_items = "SELECT cd.*, sp.tensanpham, sp.giasp, sp.id_danhmuc 
                      FROM tbl_cart_details AS cd 
                      JOIN tbl_sanpham AS sp ON cd.id_sanpham = sp.id_sanpham 
                      WHERE cd.code_cart = '$code_cart'";
                        $query_items = mysqli_query($mysqli, $sql_items);
                        $i = 0;
                        $total = 0;
                        while ($item = mysqli_fetch_array($query_items)) {
                            $i++;
                            $subtotal = $item['giasp'] * $item['soluongmua'];
                            $total += $subtotal;
                            echo "<tr>";
                            echo "<td>$i</td>";
                            echo "<td>{$item['tensanpham']}</td>";
                            echo "<td>{$item['soluongmua']}</td>";
                            echo "<td>" . number_format($item['giasp'], 0, ',', '.') . " đ</td>";
                            echo "<td>" . number_format($subtotal, 0, ',', '.') . " đ</td>";

                            if ($has_racket) {
                                if ($item['id_danhmuc'] == 1) {
                                    $id_sp = $item['id_sanpham'];
                                    $seri_sql = "SELECT sr.seri FROM tbl_serial_racket sr 
                                 JOIN tbl_seri s ON sr.seri = s.seri 
                                 WHERE sr.code_cart = '$code_cart' AND s.id_sanpham = '$id_sp'";
                                    $seri_query = mysqli_query($mysqli, $seri_sql);
                                    $seri_list = [];
                                    while ($seri_row = mysqli_fetch_array($seri_query)) {
                                        $seri_list[] = $seri_row['seri'];
                                    }
                                    $seri_text = implode("<br>", $seri_list);
                                    echo "<td class='seri-col'>$seri_text</td>";
                                } else {
                                    echo "<td class='seri-col'>-</td>";
                                }
                            }

                            echo "</tr>";
                        }
                        // Cộng phí vận chuyển nếu có
                        $shipping_fee = isset($row['shipping_fee']) ? (int)$row['shipping_fee'] : 0;
                        $total += $shipping_fee;

                        ?>
                        <tr>
                            <td colspan="<?php echo $has_racket ? 5 : 4; ?>" style="text-align:right; font-weight: bold;">
                                <strong>Tổng cộng:</strong>
                            </td>
                            <td style="text-align:right; font-weight: bold;">
                                <strong><?php echo number_format($total, 0, ',', '.') . ' đ'; ?></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Chữ ký -->
                <div class="signature">
                    <p>Ngày ... tháng ... năm ...</p>
                    <p><strong>Người lập hóa đơn</strong></p>
                    <br><br><br>
                    <p>(Ký và ghi rõ họ tên)</p>
                </div>
            </div>
            </div>
            <div class="print-btn">
                <button onclick="window.print();">In Hóa Đơn</button>
            </div>
        </body>

        </html>
<?php
    } else {
        echo "Không tìm thấy đơn hàng.";
    }
} else {
    echo "Mã đơn hàng không hợp lệ.";
}
?>