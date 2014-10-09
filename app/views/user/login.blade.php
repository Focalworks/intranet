<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Focalworks Intranet Login</title>
	<!-- Bootstrap -->
    {{ HTML::style('css/bootstrap-lumen.min.css') }}
    {{ HTML::style('packages/l4mod/sentryuser/sentryuser-style.css') }}
		<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
		@section('scripts')
		  <script type="text/javascript" src="{{ asset('js/libs/jquery-1.11.1.min.js') }}"></script>
		  <script type="text/javascript" src="{{ asset('js/prod/global.min.js') }}"></script>
	  @show
</head>
<body class="login">
	<div class="user-login-wrapper">
		<div class="logo-wrapper">
			<div class="logo-wrapper-inner img-circle"><img src="{{ asset('images/logo_0.png') }}" /></div>
		</div>
		<div class="login-lonk">
			<div class="login-lonk-inner">
				<a href="{{url('user/oauth')}}" class="btn btn-info col-md-push1"><span class="fw-icons glyphicon glyphicon-lock"></span>Login with Focalworks Email</a>
			</div>
		</div>
	</div>
	<div class="fw-overly"></div>
</body>
</html>