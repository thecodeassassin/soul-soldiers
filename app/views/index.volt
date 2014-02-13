<!doctype html>
<!--[if IE 6 ]><html lang="en-us" class="ie6"> <![endif]-->
<!--[if IE 7 ]><html lang="en-us" class="ie7"> <![endif]-->
<!--[if IE 8 ]><html lang="en-us" class="ie8"> <![endif]-->
<!--[if (gt IE 7)|!(IE)]><!-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="en-us">
    <!--<![endif]-->
    <head>

        <title>{{ title }}</title>

        {# Layout CSS #}
        {{ stylesheet_link("https://fonts.googleapis.com/css?family=Roboto:300,400,500,700", false) }}
        {{ stylesheet_link("bootstrap/css/bootstrap.css") }}
        {{ stylesheet_link("css/dark-dark.css") }}
        {{ stylesheet_link("font-icons/custom-icons/css/custom-icons.css") }}
        {{ stylesheet_link("font-icons/custom-icons/css/custom-icons-ie7.css") }}

        {{ stylesheet_link("css/layout.css") }}

        {# Custom CSS #}
        {{ stylesheet_link("css/custom.css") }}

        {{ javascript_include("js-plugin/respond/respond.min.js") }}
        {{ javascript_include("js-plugin/jquery/jquery-1.10.2.min.js") }}
        {{ javascript_include("js-plugin/jquery-ui/jquery-ui-1.8.23.custom.min.js") }}
        {{ javascript_include("bootstrap/js/bootstrap.js") }}
        {{ javascript_include("js-plugin/easing/jquery.easing.1.3.js") }}

        {{ javascript_include("js-plugin/neko-contact-ajax-plugin/js/jquery.form.js") }}
        {{ javascript_include("js-plugin/neko-contact-ajax-plugin/js/jquery.validate.min.js") }}
        {{ javascript_include("js-plugin/appear/jquery.appear.js") }}
        {{ javascript_include("js-plugin/ytplayer/jquery.mb.YTPlayer_modifed.js") }}
        {{ javascript_include("js-plugin/parallax/js/jquery.stellar.min.js") }}
        {{ javascript_include("js-plugin/toucheeffect/toucheffects.js") }}
        {{ javascript_include("js/jquery.loadmask.js") }}
        {{ javascript_include("js/custom.js") }}
        {{ javascript_include("js/bootstrap-remote-data.min.js") }}
        {{ javascript_include("js/modernizr-2.6.1.min.js") }}

        {# Custom javascript #}
    </head>
    <body class="activateAppearAnimation">
      {{ content() }}
        <div id="globalWrapper">
        <header class="navbar-fixed-top">
        <!-- pre header -->
        <div id="preHeader" class="hidden-xs">
            <div class="container">
                <div class="row">
                    <div class="col-xs-6">
                        <ul class="quickMenu">
                            <li><a href="template-site-map.html" class="linkLeft">Site map</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-6">
                        <div id="contactBloc" class="clearfix">
                            <!-- social icons -->
                            <ul class="socialNetwork">
                                <li><a href="#" class="tips" title="follow me on Facebook"> <i class="icon-facebook-1"></i></a></li>
                                <li> <a href="#" class="tips" title="follow me on Twitter"> <i class="icon-twitter-bird"></i> </a> </li>
                                <li>
                                    <a href="#" class="tips" title="follow me on Google+">
                                        <i class="icon-gplus-1"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="tips" title="follow me on Linkedin">
                                        <i class="icon-linkedin-1"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="tips" title="follow me on Dribble">
                                        <i class="icon-dribbble"></i>
                                    </a>
                                </li>
                                <!-- social icons -->
                            </ul>
                            <!-- phone -->
                            {#<span class="contactPhone"><i class="icon-mobile"></i></span>#}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- pre header -->
        <!-- header -->
        <div id="mainHeader" role="banner">
            <div class="container">
                <nav class="navbar navbar-default" role="navigation">
                    <div class="navbar-header">
                        <!-- responsive navigation -->
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <!-- Logo -->
                        <a class="navbar-brand" href="index.html"><img src="images/main-logo.png" alt="Soul-Soldiers"/></a>
                    </div>
                    <div class="collapse navbar-collapse" id="mainMenu">
                        <!-- Main navigation -->
                        <ul class="nav navbar-nav pull-right">

                            <li class="primary">
                                <a href="index.html" class="firstLevel active hasSubMenu" >Home</a>
                                <ul class="subMenu">
                                    <li><a href="index.html">Default</a></li>
                                </ul>
                            </li>

                            {#<li class="sep"></li>#}


                            <li class="sep"></li>
                            <li id="lastMenu" class="last"><a href="#" class="firstLevel last">Contact</a></li>
                        </ul>
                        <!-- End main navigation -->
                    </div>
                </nav>
            </div>
        </div>
        </header>
        <!-- header -->

        </div>
    </body>
</html>