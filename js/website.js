
var url = '';
if (window.location.host == 'localhost' || window.location.host == 'localhost:8080') {
  if (window.location.host == 'localhost:8080') {
    var url = 'http://localhost:8080/rinku';
  } else {
    var url = 'http://localhost/rinku';
  }
} else {
  var url = 'https://' + window.location.host;
}


function paginacion(currentUrl, indice, aux) {
  if (aux) {
    var currentIndice = parseInt(localStorage.getItem('paginacion'));
    if ((currentIndice > 1 && aux != -1) || (currentIndice < indice)) {
      currentIndice += parseInt(aux);
      localStorage.setItem('paginacion', currentIndice);
      if (window.location.search != '') {
        var current = currentUrl.slice(0, -1);
        window.location.assign(current + currentIndice + window.location.search);
      } else {
        var current = currentUrl.slice(0, -1);
        window.location.assign(current + currentIndice);
      }
    }
  } else {
    localStorage.setItem('paginacion', indice);
    if (window.location.search != '') {
      var current = currentUrl.slice(0, -1);
      window.location.assign(current + indice + window.location.search);
    } else {
      var current = currentUrl.slice(0, -1);
      window.location.assign(current + indice);
    }
  }


}

$(function () {
  $('.modal-permisos').hide();
  if (window.location.pathname.substr(window.location.pathname.length - 11)) {
    localStorage.setItem('categoria', 0);
    localStorage.setItem('paginacion', 1);
  }
  var id = localStorage.getItem('categoria');
  $('.currentless').removeClass('current', function () {
    $('#categoria' + id).addClass('current');
  });

  var page = localStorage.getItem('paginacion');
  if (page) {
    $('.current').removeClass('active', function () {
      $('.current-' + page).addClass('active');
    });
  }

  cargarCheckout();
  setImagenes();

  $('.ps-widget--filter .ui-slider-handle:first').first().css('display', 'none');

  let owl = $('.owl-carousel1').owlCarousel({
    center: true,
    loop: false,
    margin: 20,
    autoWidth:true,
    // URLhasListener: true,
    // startPosition: 0,
    mouseDrag: false,
    touchDrag: false
  });


  // if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
  //   try {
  //     DeviceOrientationEvent.requestPermission().then(function (response) {
  //       if (response === 'granted') {
  //         getAceleracion();
  //       }
  //     }).catch((error) => {
  //       setTimeout(() => {
  //         $('.modal-permisos').fadeIn(500);
  //       }, 2600);
  //     });
  //   } catch (error) {

  //   }
  // }

});
