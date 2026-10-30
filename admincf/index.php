<?php
session_start();
if (!isset($_SESSION['dangnhap'])) {
    header("Location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" type="text/css" href="css/styleadmincf.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
     
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/> -->
    <link rel="icon" type="image/x-icon" href="/images/logoft1.png">
    
    <title>AdminDC</title>
</head>

<body>
    <div class="wrapper">
        <?php

        include("config/config.php");
        include("modules/bannerad.php");
        // include("modules/header.php");
        include("modules/menu.php");
        include("modules/main.php");
        // include("modules/footer.php");
        ?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    <!-- <script type="text/javascript">
        $(document).ready(function() {
            var char = new Morris.Area({
                element: 'myfirstchart',
                xkey: 'date',
                ykeys: ['date','order', 'sales', 'quantity'],
                labels: ['Date','Đơn hàng', 'Doanh thu', 'Số lượng bán ra']
            });

            // Hàm lấy dữ liệu thống kê
            function thongke() {
                var text = '365 ngày qua';
                $('#text-date').text(text);
                $.ajax({
                    url: "modules/thongke.php",
                    method: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        console.log(data); 
                        char.setData(data);
                    }
                });
            }
            thongke(); // Gọi hàm để lấy dữ liệu ban đầu

            // Xử lý thay đổi thời gian
            $('.select-date').change(function() {
                var thoigian = $(this).val();
                var text = '';

                switch (thoigian) {
                    case '7ngay':
                        text = '7 ngày qua';
                        break;
                    case '28ngay':
                        text = '28 ngày qua';
                        break;
                    case '90ngay':
                        text = '90 ngày qua';
                        break;
                    default:
                        text = '365 ngày qua';
                }

                $('#text-date').text(text);

                $.ajax({
                    url: "modules/thongke.php",
                    method: "POST",
                    dataType: "JSON",
                    data: { thoigian: thoigian },
                    success: function(data) {
                        console.log(data); 
                        char.setData(data);
                    }
                });
            });
        });
    </script> -->
</body>
</html>