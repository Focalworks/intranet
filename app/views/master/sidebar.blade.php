<div class="menu-inner">
	<div class="fw-btn-menu clearfix" id="menuButton"><div class="fw-btn-menu-inner clearfix"><span class="enlarge">Menu</span><span class="fw-icon-menu">&nbsp;</span></div></div>
	<ul class="menu">
		<li class="leaf first clearfix" data-title="Home"><div class="item-wrapper clearfix"><a href="#" title="Home"><span class="fw-icons fw-icon-home">&nbsp;</span><span class="enlarge">Home</span></a></div></li>
		<li class="leaf clearfix sub-menu last">
			<div class="item-wrapper clearfix"><span class="fw-icon-expand">&nbsp;</span><span class="fw-icons fw-icon-grievance">&nbsp;</span><span class="enlarge">Grievance</span></div>
			<ul class="sub-menu-wrapper sub-menu-normal clearfix">
				<li class="leaf first clearfix">{{ link_to('grievance/list', 'Grievance List') }}</li>
				<li class="leaf last clearfix">{{ link_to('grievance/add', 'Grievance Add') }}</li>
			</ul>
		</li>
		<!-- <li class="leaf clearfix last"><div class="item-wrapper clearfix"><a href="#"title="Mail"><span class="fw-icons fw-icon-mail">&nbsp;</span><span class="enlarge">Mail</span></a></div></li> -->
	</ul>
</div>