<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      {{ link_to('/', Config::get('packages/l4mod/sentryuser/sentryuser.site-title'), array('class' => 'navbar-brand')) }}
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse nav-right-block pull-right" id="bs-example-navbar-collapse-1">
      <!-- <div class="search-wrapper pull-left">
        <input type="text" placeholder="Search" />
      </div> -->
      <ul class="nav navbar-nav navbar-right">
        <span class="user-image"><img src="{{asset(UserHelper::getUserPicture())}}" alt="" class="pull-left" width="35" height="35" /></span>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{UserHelper::getUserDisplayName()}} <span class="fw-icon-arrow"></span></a>
          <ul class="dropdown-menu">
            <li>{{ link_to('edit-profile', 'Edit Profile') }}</li>
            <li class="divider"></li>
            <li>{{ link_to('user/logout', 'Logout') }}</li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>