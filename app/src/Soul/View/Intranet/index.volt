{% extends 'layout.volt' %}

{% block body %}

    <!-- Page Container -->
    <div id="page-container" class="header-fixed-top">
        <!-- Header -->
        <!-- Add the class .navbar-fixed-top or .navbar-fixed-bottom for a fixed header on top or bottom respectively -->
        <header class="navbar navbar-inverse navbar-fixed-top">
        <!-- <header class="navbar navbar-inverse navbar-fixed-bottom"> -->
            <!-- Mobile Navigation, Shows up  on smaller screens -->
            <ul class="navbar-nav-custom pull-right hidden-md hidden-lg">
                <li class="divider-vertical"></li>
                <li>
                    <!-- It is set to open and close the main navigation on smaller screens. The class .navbar-main-collapse was added to aside#page-sidebar -->
                    <a href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-main-collapse">
                        <i class="icon-th-list"></i>
                    </a>
                </li>
            </ul>
            <!-- END Mobile Navigation -->

            <!-- Logo -->
            <a href="{{ url('home') }}" class="navbar-brand">Soul-Soldiers Intranet</a>

            <!-- Loading Indicator, Used for demostrating how loading of widgets could happen, check main.js - uiDemo() -->
            <div id="loading" class="pull-left"><img src="img/ajax-loader.gif" alt="loading" /></div>

            <!-- Header Widgets -->
            <!-- You can create the widgets you want by replicating the following. Each one exists in a <li> element -->
            <ul id="widgets" class="navbar-nav-custom pull-right">

                {#<li class="divider-vertical"></li>#}

                <!-- Messages Widget -->
                <!-- Add .dropdown-left-responsive class to align the dropdown menu left (so its visible on mobile) -->
                {#<li id="messages-widget" class="dropdown dropdown-left-responsive">#}
                    {#<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">#}
                        {#<i class="fa fa-envelope"></i>#}
                        {#<!-- If the <span> element with .badge class has no content it will disappear (not in IE8 and below because of using :empty in CSS) -->#}
                        {#<span class="badge badge-success">1</span>#}
                    {#</a>#}
                    {#<ul class="dropdown-menu dropdown-menu-right widget">#}
                        {#<li class="widget-heading"><i class="fa fa-comment pull-right"></i>Latest Messages</li>#}
                        {#<li class="new-on">#}
                            {#<div class="media">#}
                                {#<a class="pull-left" href="javascript:void(0)">#}
                                    {#<img src="img/placeholders/image_light_64x64.png" alt="fakeimg">#}
                                {#</a>#}
                                {#<div class="media-body">#}
                                    {#<h6 class="media-heading"><a href="javascript:void(0)">George</a><span class="label label-success">2 min ago</span></h6>#}
                                    {#<div class="media">Thanks for your help! The tutorial really helped me a lot!</div>#}
                                {#</div>#}
                            {#</div>#}
                        {#</li>#}
                        {#<li class="divider"></li>#}
                        {#<li class="text-center"><a href="page_inbox.html">View All Messages</a></li>#}
                    {#</ul>#}
                {#</li>#}
                <!-- END Messages Widget -->

                <li class="divider-vertical"></li>

                <!-- Notifications Widget -->
                <!-- Add .dropdown-center-responsive class to align the dropdown menu center (so its visible on mobile) -->

                {#<li id="notifications-widget" class="dropdown dropdown-center-responsive">#}
                    {#<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">#}
                        {#<i class="fa fa-flag"></i>#}
                        {#<span class="badge badge-danger">1</span>#}
                        {#<span class="badge badge-warning">2</span>#}
                    {#</a>#}
                    {#<ul class="dropdown-menu dropdown-menu-right widget">#}
                        {#<li class="widget-heading"><a href="javascript:void(0)" class="pull-right widget-link"><i class="fa fa-cog"></i></a><a href="javascript:void(0)" class="widget-link">System</a></li>#}
                        {#<li>#}
                            {#<ul>#}
                                {#<li class="label label-danger">20 min ago</li>#}
                                {#<li class="text-danger">Support system is down for maintenance!</li>#}
                            {#</ul>#}
                        {#</li>#}
                        {#<li class="divider"></li>#}
                        {#<li class="text-center"><a href="javascript:void(0)">View All Notifications</a></li>#}
                    {#</ul>#}
                {#</li>#}
                <!-- END Notifications Widget -->

                <!-- User Menu -->
                <li class="dropdown pull-right dropdown-user">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-user-1"></span> {{ user.getNickName() }} <b class="caret"></b></a>
                    {{ usermenu }}
                </li>
                <!-- END User Menu -->
            </ul>
            <!-- END Header Widgets -->
        </header>
        <!-- END Header -->

        <!-- Inner Container -->
        <div id="inner-container">
            <!-- Sidebar -->
            <aside id="page-sidebar" class="collapse navbar-collapse navbar-main-collapse">
                {#<!-- Sidebar search -->#}
                {#<form id="sidebar-search" action="page_search_results.html" method="post">#}
                    {#<div class="input-group">#}
                        {#<input type="text" id="sidebar-search-term" name="sidebar-search-term" placeholder="Search..">#}
                        {#<button><i class="icon-search"></i></button>#}
                    {#</div>#}
                {#</form>#}
                {#<!-- END Sidebar search -->#}

                <!-- Primary Navigation -->
                <nav id="primary-nav">
                    <!-- Main navigation -->
                    {{ menu }}
                    <!-- End main navigation -->
                </nav>
                <!-- END Primary Navigation -->

            </aside>
            <!-- END Sidebar -->

            <!-- Page Content -->
            <div id="page-content">
                {#<ul id="nav-info" class="clearfix">#}
                    {#<li><a href="index.php"><i class="fa fa-home"></i></a></li>#}
                    {#<li class="active"><a href="">Dashboard</a></li>#}
                {#</ul>#}
                <div class="row">
                    <div id="messages" class="col-md-12">
                        {{ flash.output() }}
                        {{ flashSession.output() }}
                    </div>
                </div>


                {{ content() }}
            </div>
            <!-- END Page Content -->

            <!-- Footer -->
            <footer>
                <span id="year-copy"></span> &copy; Soul-Soldiers 2014
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Inner Container -->
    </div>
    <!-- END Page Container -->

    <!-- Scroll to top link, check main.js - scrollToTop() -->
    <a href="javascript:void(0)" id="to-top"><span class="glyphicon glyphicon-chevron-up"></span></a>

{% endblock %}
