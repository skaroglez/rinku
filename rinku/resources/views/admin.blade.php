<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" ng-app="admin">

<head>

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

  <meta name="author" content="Rinku">
  <meta name="description" content="Rinku" />
  <meta name="keywords" content="" />

  <link rel="shortcut icon" href="{{ asset('images/icon.ico') }}">
  <title>Rinku</title>

  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/themify-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/toastr/toastr.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/sweetalert/dist/sweetalert.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/angular-bootstrap-calendar/dist/css/angular-bootstrap-calendar.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/admin.css') }}">

</head>

<body ng-controller="adminController" class="centro" ng-class="bodyClass">

  <div class="lock" ng-show="lock.value">
    <div class="sk-spinner sk-spinner-wave">
      <div class="sk-rect1"></div>
      <div class="sk-rect2"></div>
      <div class="sk-rect3"></div>
      <div class="sk-rect4"></div>
      <div class="sk-rect5"></div>
      <h4>Cargando</h4>
    </div>
  </div>

  <aside>
    <div class="header">
      <a href="#">
        <div class="icono">
          <img src="{{ asset('images/rinku-logo.png') }}" alt="RINKU - Logo">
        </div>
      </a>
    </div>
    <div class="section">
      <ul>
        <li ng-repeat="menu in menu.aside" ng-class="menu.title == 1 ? 'title' : ''">
          <a href="[[menu.url]]" ng-if="!menu.title">
            <i class="[[menu.icon]]"></i> <span>[[ menu.name ]]</span>
          </a>
          <span ng-if="menu.title">[[ menu.name ]]</span>
        </li>
      </ul>
    </div>
  </aside>

  <header>
    <div class="buttons">
      <div class="row">
        <div class="col-12">
          <ul>           
            <li><a ng-click="logout()"><i class="ti-close"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  <section>
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="area" ui-view></div>
        </div>
      </div>
    </div>
  </section>


  <!-- JS -->
  <script src="https://kit.fontawesome.com/2508f54b33.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/clipboard/dist/clipboard.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/toastr/toastr.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/angular/angular.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/ngMask/dist/ngMask.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/angular-i18n/angular-locale_es-mx.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/angular-ui-router/release/angular-ui-router.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/ocLazyLoad/dist/ocLazyLoad.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/moment/moment.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/angular-bootstrap-calendar/dist/js/angular-bootstrap-calendar-tpls.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/ng-sortable/dist/ng-sortable.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/angular-file-upload/dist/angular-file-upload.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/tinymce/tinymce.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/angular-ui-tinymce/src/tinymce.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bower_components/angular-cookies/angular-cookies.min.js') }}"></script>

  <script type="text/javascript" src="{{ asset('bladmir/utils/factories/menu.factories.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bladmir/utils/factories/util.factories.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bladmir/utils/factories/interceptor.factories.js') }}"></script>

  <script type="text/javascript" src="{{ asset('bladmir/utils/services/util.services.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bladmir/utils/services/validate.services.js') }}"></script>
  <script type="text/javascript" src="{{ asset('bladmir/utils/services/authentication.services.js') }}"></script>

  <script type="text/javascript" src="{{ asset('bladmir/admin.app.js') }}"></script>
</body>

</html>