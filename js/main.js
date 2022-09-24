// -------------------------- scroll animate -------------------------- //
$(document).ready(function() {
  $(window).scroll(function() {
      if ($(this).scrollTop() > 100) {
          $('.scroll-top').fadeIn();
      } else {
          $('.scroll-top').fadeOut();
      }
  });

  $('.scroll-top').click(function() {
      $("html, body").animate({
          scrollTop: 0
      }, 100);
      return false;
  });

});

// -------------------------- products filter -------------------------- //

$('.category ul').hide();
$(".category a").click(function() {
  $(this).parent(".category.dropdown").children("ul").slideToggle("100");
  $(this).find(".right").toggleClass("fa-angle-up");
});

// ---------------------------- categories ---------------------------- //

const imgs = document.querySelectorAll('.img-select a');
const imgBtns = [...imgs];
let imgId = 1;

imgBtns.forEach((imgItem) => {
  imgItem.addEventListener('click', (event) => {
      event.preventDefault();
      imgId = imgItem.dataset.id;
      slideImage();
  });
});

function slideImage() {
  const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;

  document.querySelector('.img-showcase').style.transform = `translateX(${- (imgId - 1) * displayWidth}px)`;
}

window.addEventListener('resize', slideImage);