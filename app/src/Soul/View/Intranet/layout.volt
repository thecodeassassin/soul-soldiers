<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
{% set loginPage = router.getActionName() in ['login','forgotPassword'] %}
{% include 'header.volt' %}

    <body>
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


            <!-- Header Widgets -->
            <!-- You can create the widgets you want by replicating the following. Each one exists in a <li> element -->
            <ul id="widgets" class="navbar-nav-custom pull-right">

                <li class="divider-vertical"></li>

                {% if user %}
                <!-- User Menu -->
                <li class="dropdown pull-right dropdown-user">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-user-1"></span> {{ user.getNickName() }} <b class="caret"></b></a>
                    {{ usermenu }}
                </li>
                <!-- END User Menu -->
                {% endif %}
            </ul>
            <!-- END Header Widgets -->
        </header>
        <!-- END Header -->

        <!-- Inner Container -->
        <div id="inner-container">
            <!-- Sidebar -->
            <aside id="page-sidebar" class="collapse navbar-collapse navbar-main-collapse">

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
                <div class="inner">


                    <div class="page-header page-header-top clearfix">
                            {% if pageTitle is not defined %} {% set pageTitle = null %} {% endif %}
                            <div class="{% if pageTitle %}hasPageTitle{% endif %}">
                               <h4 class="pull-left">{{ pageTitle|default('') }}</h4>
                                <div class="pageTitleBlock">
                                    <h4 class="pull-left">{% block pageTitle %}{{ router.getActionName()|capitalize  }}{% endblock %}</h4>
                                </div>
                            </div>

                    </div>


                    <div class="row">
                        <div id="messages" class="col-md-12">
                            {{ flash.output() }}
                            {{ flashSession.output() }}
                        </div>
                    </div>

                  {% block content %}{% endblock %}

                </div>
            </div>
            <!-- END Page Content -->

            <!-- Footer -->
            <footer>
                &copy; Soul-Soldiers 2014
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Inner Container -->
    </div>
    <!-- END Page Container -->

    <!-- Scroll to top link, check main.js - scrollToTop() -->
    <a href="javascript:void(0)" id="to-top"><span class="icon-up"></span></a>

    {% block footer %}

        <script type="text/javascript">
            var __loading_img = '{{ url('img/ajax-loader.gif') }}';
            var CKEDITOR_BASEPATH = '/js/intranet/ckeditor/';

            var loadingImagePreload=new Image();
            loadingImagePreload.src = __loading_img;
        </script>

        {{ assets.outputJs('scripts') }}

    {% endblock %}
    {% block javascript %}{% endblock %}
    </body>
</html>