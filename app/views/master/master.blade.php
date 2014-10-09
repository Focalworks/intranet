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
    {{ HTML::style('css/bootstrap-yeti.min.css') }}
    {{ HTML::style('packages/l4mod/sentryuser/sentryuser-style.css') }}
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

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
    <script type="text/javascript" src="{{ asset('js/libs/angular.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/libs/angular-route.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/prod/global.min.js') }}"></script>
    @show

    <script type="text/javascript">
        var base_url = "{{ URL::to('/') }}/";
    </script>
</head>
<body>
  @if (!isset($menuSkip))
  @if (Config::get('packages/l4mod/sentryuser/sentryuser.nav-tpl') == '')
  @include('sentryuser::nav')
  @else
  @include(Config::get('packages/l4mod/sentryuser/sentryuser.nav-tpl'))
  @endif
  @endif
  <div class="container-fluid main-content-wrapper">
    <div class="row">
      <div class="sidebar float-left menu-wrapper menu-wrapper-{{UserInterface::getUserMenuPref()}}" data-menu="{{UserInterface::getUserMenuPref()}}">
        @include('master.sidebar')
      </div>
      <div class="main-wrapper main-wrapper-{{UserInterface::getUserMenuPref()}}">
        @if (Session::get('message'))
          <div class="clearfix">
            <div class="col-md-12">
              <div class="alert alert-{{ Session::get('message-flag') }}">{{ Session::get('message') }}</div>
            </div>
          </div>
        @endif
        @if (!isset($menuSkip))
          <div class="col-md-12">@include('sentryuser::secondary-menu')</div>
        @endif
        <div class="clearfix">
          <div class="col-md-12">
            @yield('content')
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>