//chỉnh sửa nav
$(document).ready(function () {
    $(window).scroll(function () {
      if ($(this).scrollTop()) {
        $("header").addClass("sticky");
      } else {
        $("header").removeClass("sticky");
      }
    });
  });

  //Nút backtop
/*$(document).ready(function () {
    $(window).scroll(function (){
        if($(this).scrollTop()){    // scrollTop != 0
            $('.backtop').fadeIn(); // fadeIn(): hiển thị
        }else{
            $('.backtop').fadeOut(); // fadeOut(): mờ dần
        }
    }); // ở đầu trang web sẽ không hiển thị nút backtop, cuộn xuống sẽ hiển thị
    $(".backtop").click(function (){
        $('html, body').animate({
            scrollTop: 0
        }, 1000);
        });
    });
  
    //hiển thị giỏ hàng
    let iconCart = document.querySelector('.cart');
    let closeCart = document.querySelector('.close');
    let body = document.querySelector('body');
    let listProductHTML = document.querySelector('.list_product');
    let listCartHTML = document.querySelector('.list_Cart');
    let iconCartSpan = document.querySelector('.cart span');
    
    let listProducts = [];
    let carts=[];


    iconCart.addEventListener('click', () => {
      body.classList.toggle('showCart')
    })
    closeCart.addEventListener('click', () => {
      body.classList.toggle('showCart')
    })

const addDataToHTML = () =>{
  listProductHTML.innerHTML ='';
  if(listProducts.length > 0){
      listProducts.forEach(product =>{
          let newProduct = document.createElement('div');
          newProduct.classList.add('item');
          newProduct.dataset.id = product.id;
          newProduct.innerHTML=`
                <div class="product_top">
                  <div class="add_like">
                          <!-- <a class="add_product"><i class='bx bx-sm bx-cart-add'></i></a> -->
                    <a class="like_product"><i class='bx bx-sm bx-heart-circle'></i></a>
                  </div>
                  <div class="img_item">
                    <img class="photo1" src="${product.image}" />
                    <img src="${product.image2}" class="photo2" />
                  </div>
                  <a class="buynow"><i class='bx bx-cart-add'></i> Thêm vào giỏ hàng</a>
                </div>
                <div class="infor">
                  <div class="name_product">
                    <p>${product.name}</p>
                  </div>
                  <p class="price">${product.price} ₫</p>
                </div>
          `;
          listProductHTML.appendChild(newProduct);
      })
  }
}

listProductHTML.addEventListener('click', (e) =>{
  let positionClick = e.target;
  if(positionClick.classList.contains('buynow')){
    let product_id = positionClick.parentElement.parentElement.dataset.id;
    addToCart(product_id);
  }
})

const addToCart = (product_id) => {
  let positionThisProductInCart = carts.findIndex((value) => value.product_id == product_id);
  if(carts.length <= 0){
    carts = [{
      product_id: product_id,
      quantity: 1
    }]
  }
  else if(positionThisProductInCart < 0){
    carts.push ({
      product_id: product_id,
      quantity: 1
    });
  }
  else{
    carts[positionThisProductInCart].quantity = carts[positionThisProductInCart].quantity + 1;
  }
  addCartToHTML();
  addCartToMemory();
}

const addCartToHTML = () => {
  listCartHTML.innerHTML = '';
  let totalQuantity = 0;
  if(carts.length > 0){
    carts.forEach(cart => {
      totalQuantity = totalQuantity + cart.quantity;
      let newCart = document.createElement('div');
      newCart.classList.add('item');
      newCart.dataset.id = cart.product_id;
      let positionProduct = listProducts.findIndex((value) => value.id == cart.product_id);
      let info = listProducts[positionProduct];
      newCart.innerHTML = `
      <div class="img_cart">
          <img src="${info.image}">
        </div>
        <div class="name_cart">
          ${info.name}
        </div>
        <div class="price_cart">
          ${info.price * cart.quantity} ₫ 
        </div>
        <div class="quantity">
          <span class="minus">-</span>
          <span>${cart.quantity}</span>
          <span class="plus">+</span>
        </div>
        <Button class="delete_item_cart">Xóa</Button>
      `;
        listCartHTML.appendChild(newCart);
    })
  }
  else{
    listCartHTML.innerHTML = `<div class="empty">Giỏ hàng của bạn đang trống!!!</div>`
  }
  iconCartSpan.innerText = totalQuantity;
}

const addCartToMemory = () =>{
    localStorage.setItem('cart',JSON.stringify(carts));
}

const innitApp = () => {
  //lấy dữ liệu từ json
  fetch('../json/product.json')
  .then(response => response.json())
  .then(data => {
    listProducts = data;
    addDataToHTML();

    //lấy dữ liệu từ bộ nhớ (khi nhấn thêm vào giỏ hàng nó sẽ lấy dữ liệu từ json lưu vào bộ nhớ đang nói đến)
    if(localStorage.getItem('cart')){
      carts = JSON.parse(localStorage.getItem('cart'));
      addCartToHTML();
    }
  });
};
innitApp();


listCartHTML.addEventListener('click', (event) =>{
  let positionClick = event.target;
  if(positionClick.classList.contains('minus') || positionClick.classList.contains('plus')){
      let product_id = positionClick.parentElement.parentElement.dataset.id;
      let type = 'minus';
      if(positionClick.classList.contains('plus')){
          type = 'plus';
      }
      changeQuantity(product_id,type);
  }
})

const changeQuantity = (product_id,type) =>{
  let positionItemInCart = carts.findIndex((value) => value.product_id == product_id);
      if(positionItemInCart >= 0){
          switch(type){
              case 'plus':
                  carts[positionItemInCart].quantity =  carts[positionItemInCart].quantity +1;
                  break;
              default:
                  let valueChange = carts[positionItemInCart].quantity-1;
                  if(valueChange > 0){
                      carts[positionItemInCart].quantity=valueChange;
                  }else{
                      carts.splice(positionItemInCart,1);
                  }
                  break;
          } 
      }
  addCartToMemory();
  addCartToHTML();
}

const checkoutBtn = document.querySelector('.checkOut');

checkoutBtn.addEventListener('click', () => {
    checkout();
});

const calculateTotalInfo = () => {
    let totalPrice = 0;
    let totalQuantity = 0;

    carts.forEach(cart => {
        let positionProduct = listProducts.findIndex((value) => value.id == cart.product_id);
        let info = listProducts[positionProduct];
        totalPrice += info.price * cart.quantity;
        totalQuantity += cart.quantity;
    });

    return { totalPrice, totalQuantity };
}

const displayPaymentSuccess = (totalPrice, totalQuantity) => {
    // Hiển thị 
    // confirm(`Thanh toán thành công!\nTổng số sản phẩm: ${totalQuantity}\nTổng tiền đã thanh toán: ${totalPrice} ₫`);
        const confirmed = confirm(`Tổng hóa đơn của bạn:\nTổng số sản phẩm: ${totalQuantity}\nTổng tiền đã thanh toán: ${totalPrice} ₫\n Bạn có chắc chắn muốn đặt hàng?`);
        if (confirmed) {
            // Chuyển hướng sang trang cảm ơn
            alert(`Chúng tôi xin chân thành cảm ơn bạn đã đặt hàng tại cửa hàng DOLCE VIVA. Đơn hàng của bạn đã được nhận và đang được xử lý.`); // Thay đổi đường dẫn tùy theo cấu trúc của trang web của bạn
            clearCart();
          }
        else{
          console.log("Giữ nguyên giỏ hàng");
        } 
      
}

const checkout = () => {
    if(carts.length > 0){
        // tổng tiền, số sản phẩm trong giỏ
        const { totalPrice, totalQuantity} = calculateTotalInfo();
        // Hiển thị thông báo 
        displayPaymentSuccess(totalPrice, totalQuantity);
        // Xóa dữ liệu trong giỏ hàng sau khi thanh toán

    }else{
        alert("Vui lòng thêm sản phẩm trước khi thanh toán!!!");
    }  
}

const clearCart = () => {
    // Xóa dữ liệu trong giỏ hàng
    carts = [];
    // Cập nhật giỏ hàng trên HTML và trong bộ nhớ
    addCartToHTML();
    addCartToMemory();
}

//xóa sản phẩm khỏi giỏ
listCartHTML.addEventListener('click', (event) =>{
  let positionClick = event.target;
  if(positionClick.classList.contains('delete_item_cart')){
    const confirmed = confirm("Bạn có chắc chắn muốn xóa mặt hàng này khỏi giỏ?");
    if(confirmed){
      let product_id = positionClick.parentElement.dataset.id;
      removeCartItem(product_id);
  }} 
})

const removeCartItem = (product_id) =>{
  let positionItemInCart = carts.findIndex((value) => value.product_id == product_id);
  if(positionItemInCart >= 0){
      carts.splice(positionItemInCart,1);
  }
  addCartToMemory();
  addCartToHTML();
}*/