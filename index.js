// // SLIDE INDEX
// const imgPos= document.querySelectorAll(".slide img")
// const imgC= document.querySelector(".slide")
// const dotItems= document.querySelectorAll(".dot")
// let imgNum= imgPos.length;
// let index=0;
// if (imgPos!=null && imgC!=null && dotItems!=null){
// imgPos.forEach(function(image,index){
//     image.style.left= index*100 + "%";
//     dotItems[index].addEventListener("click",function(){
//         slider(index);
//     })
// })
// function imgSlide(){
//     index++;
//     console.log(index);
//     if(index>=imgNum)
//        {index=0;} 
//     slider(index);

// }
// function slider (index) {
//     imgC.style.left = "-"+ index*100+ "%";
//     const dotActive= document.querySelector(".active");
//     dotActive.classList.remove("active");
//     dotItems[index].classList.add("active");
// }
// setInterval(imgSlide,2500);
// }


// //Test
// let currentIndex = 0;
// const slides = document.querySelectorAll('.slide img');

// function showSlide(index) {
//     slides.forEach((slide, i) => {
//         slide.classList.remove('active');
//         if (i === index) {
//             slide.classList.add('active');
//         }
//     });
// }

// function nextSlide() {
//     currentIndex = (currentIndex + 1) % slides.length;
//     showSlide(currentIndex);
// }

// // Hiển thị slide đầu tiên
// showSlide(currentIndex);

// // Chuyển slide cứ mỗi 3 giây
// setInterval(nextSlide, 3000);
// // đăng nhập đk

// /*    const userIcon = document.querySelector('.user-icon');
//     const dropdownContent = document.querySelector('.dropdown-content');

//     userIcon.addEventListener('click', () => {
//         dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
//     });

//     // Đóng dropdown khi click ra ngoài
//     window.addEventListener('click', (e) => {
//         if (!userIcon.contains(e.target)) {
//             dropdownContent.style.display = 'none';
//         }
//     });


// // Đăng nhập//


// // validation form login
// const inputUsername = document.querySelector(".Email");
// const inputPassword = document.querySelector(".passWord");
// const btnLogin = document.querySelector(".signupLogin");



// btnLogin.addEventListener("click", (e) => {
//   // e.preventDefault();
//   if (inputUsername.value === "" || inputPassword.value === "") {
//     alert("vui lòng không để trống");
//   } else {
//     const user = JSON.parse(localStorage.getItem(inputUsername.value));
//     if (
//       user.username === inputUsername.value &&
//       user.password === inputPassword.value
//     ) {
//       alert("Đăng Nhập Thành Công");
//       window.location.href = "../html/trangchu.html";
//     } else {
//       alert("Đăng Nhập Thất Bại");
//     }
//   }
// });*/



document.addEventListener("DOMContentLoaded", () => {
  let currentIndex = 0;
  const slides = document.querySelectorAll('.slide img');

  function showSlide(index) {
      slides.forEach((slide, i) => {
          slide.classList.remove('active');
          if (i === index) {
              slide.classList.add('active');
          }
      });
  }

  function nextSlide() {
      currentIndex = (currentIndex + 1) % slides.length;
      showSlide(currentIndex);
  }

  // Hiển thị slide đầu tiên
  showSlide(currentIndex);

  // Chuyển slide cứ mỗi 3 giây
  setInterval(nextSlide, 3000);
});


