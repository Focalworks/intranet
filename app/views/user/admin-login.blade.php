


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (isset($pageTitle))
    <title>{{ $pageTitle }}</title>
    @else
    <title>{{ Config::get('packages/l4mod/sentryuser/sentryuser.site-title') }}</title>
    @endif
    <!-- Bootstrap -->
    {{ HTML::style('packages/l4mod/sentryuser/bootstrap-ubuntu.min.css') }}
    {{ HTML::style('packages/l4mod/sentryuser/sentryuser-style.css') }}
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    @section('scripts')
    <script type="text/javascript" src="{{ asset('js/libs/jquery-1.11.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/libs/bootstrap.min.js') }}"></script>
    @show

    <script type="text/javascript">
        var base_url = "{{ URL::to('/') }}/";
    </script>
</head>
<body>
<div class="container">
    <div class="row">
    <div class="col-md-6 col-lg-push-2">
        {{ Form::open(array('url' => 'do-login', 'role' => 'form')) }}
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" name="email">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
        </div>
        <button type="submit" class="btn btn-success">Login</button>
        {{ Form::close() }}
    </div>
</div>
</div>
</body>
</html>