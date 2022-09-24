<!DOCTYPE html>
<html ng-app="login">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" ng-app="login">

<head>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

	<meta name="author" content="Rinku">
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<link rel="shortcut icon" href="{{ asset('images/icon.ico') }}">
	<title>Rinku :: Login</title>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('bower_components/themify-icons/css/themify-icons.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('bower_components/toastr/toastr.min.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body ng-controller="loginController" class="view login">

	<section>
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12">
					<div class="content text-center">
						<div class="companies">
							<img src="{{ asset('images/rinku-logo.png') }}" alt="RINKU- logo">
						</div>
						<form id="demo-form" class="form-validate">
							<div class="row">
								<div class="col-xs-12">
									<div class="form-group">
										<input type="email" ng-model="usuario.vc_email" class="form-control required" placeholder="Correo Electrónico">
									</div>
									<div class="form-group">
										<input type="password" ng-model="usuario.vc_password" class="form-control required" placeholder="Contraseña">
									</div>
									<div class="form-group">
										<div class="g-recaptcha" data-callback="setRecaptcha" data-sitekey="6LePn70aAAAAAMFdAcf5t5h5EKRJVdeUG1OPRJe8"></div>
									</div>
									<div class="form-group">
										<button class="btn" ng-click="login()">Iniciar sesión</button>
									</div class="form-group">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<footer>
		<div class="container">
			<div class="row">
				<div class="col-sm-6 foot text-left">
					® 2022 Rinku
				</div>
				<div class="col-sm-6 foot text-right">
					<a href="https://www.linkedin.com/in/carolina-gonz%C3%A1lez-1b2449194/" target="_blank">Developed by CGC</a>
				</div>
			</div>
		</div>
	</footer>


	<script type="text/javascript" src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bower_components/angular/angular.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bower_components/toastr/toastr.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('bladmir/utils/factories/util.factories.js') }}"></script>

	<script type="text/javascript" src="{{ asset('bladmir/utils/services/util.services.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bladmir/utils/services/validate.services.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bladmir/utils/services/authentication.services.js') }}"></script>

	<script type="text/javascript" src="{{ asset('bladmir/login.app.js') }}"></script>
	<script src="{{ asset('js/website.js') }}"></script>
</body>

</html>