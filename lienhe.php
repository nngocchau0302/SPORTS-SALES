<?php
include("admincf/config/config.php");
$sql_lh = "SELECT * FROM tbl_lienhe WHERE id=1";
$query_lh = mysqli_query($mysqli, $sql_lh);
?>



<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <link rel="stylesheet" type="text/css" href="css/style2.css">
  
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script type="text/javascript" src="/js/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="/js/product_cart.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
 </head><link rel="icon" type="image/x-icon" href="/images/logoft1.png"><title>DC SPORT </title>
 <body class="bg-white text-gray-800">
 <?php

include("pages/header.php");
?>
  <div class="container mx-auto p-4" style="padding-top: 10rem; margin-top: 55px;">
   <h1 class="text-center text-3xl font-bold mb-8 text-blue-400">
    LIÊN HỆ
   </h1>
   <div class="flex flex-col lg:flex-row">
    <div class="lg:w-1/2 p-4">
    <iframe allowfullscreen="" height="750" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3928.7635853451384!2d105.75490397479416!3d10.036357590070915!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0886adf2c7027%3A0x4faa67dc33545458!2zMTYyIMSQLiBOZ3V54buFbiBWxINuIEPhu6ssIFBoxrDhu51uZyBBbiBLaMOhbmgsIE5pbmggS2nhu4F1LCBD4bqnbiBUaMahLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1709612165352!5m2!1svi!2s" style="border:0;" width="100%">
        </iframe>
    </div>
    <div class="lg:w-1/2 p-4">
     <div class="mb-4">
      <p>
       Địa chỉ: 162 Đ. Nguyễn Văn Cừ, Phường An Khánh, Ninh Kiều, Cần Thơ, Việt Nam
      </p>
      <p>
       Email: dcsport323@gmail.com
      </p>
      <p>
       Số điện thoại: 0909.123.456
      </p>
      <p>
       TK Techcombank: 268999 - VO DOAN NGOC CHAU
      </p>
      <p>
       TK Vietcombank: 99.228.999999 - VO DOAN NGOC CHAU
      </p>
     </div>
     <form class="space-y-4" style="padding-top: 1rem;" method="POST" action="pages/main/form_lienhe.php">
      <div>
       <!-- <label class="block text-sm font-medium" for="wsport">
        DCSport
       </label> -->
       <!-- <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="wsport" type="text"/> -->
      </div>
      <div>
       <label class="block text-sm font-medium" for="name">
        Họ và tên
       </label>
       <input class="mt-1 block w-full border border-gray-300 rounded-md p-2"  require id="name" type="text" name="username"/>
      </div>
      <div>
       <label class="block text-sm font-medium" for="phone">
        Số điện thoại
       </label>
       <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" require id="phone" type="text" name="phone"/>
      </div>
      <div>
       <label class="block text-sm font-medium" for="address">
        Địa chỉ
       </label>
       <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" require id="address" type="text" name="ct_address"/>
      </div>
      <div>
       <label class="block text-sm font-medium" for="email">
        Email
       </label>
       <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" require id="email" type="email" name="email"/>
      </div>
      <div>
       <label class="block text-sm font-medium" for="message">
        Nội dung
       </label>
       <textarea class="mt-1 block w-full border border-gray-300 rounded-md p-2" require id="message" rows="4" name="ct_status"></textarea>
      </div>
      <div>
       <button class="bg-orange-500 text-white px-4 py-2 rounded-md" type="submit" name="guilienhe">
        Gửi
       </button>
      </div>
     </form>
    </div>
   </div>
  </div>
  <?php

include("pages/footer.php");
?>
 <?php include 'pages/chat-widget.php'; ?>
 </body>
</html>
