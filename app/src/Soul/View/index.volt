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
        {{ stylesheet_link("http://fonts.googleapis.com/css?family=Droid+Sans:400,700", false) }}
        {{ stylesheet_link("//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css", false) }}
        {{ stylesheet_link("css/soul-color.css") }}
        {{ stylesheet_link("font-icons/custom-icons/css/custom-icons.css") }}
        {{ stylesheet_link("font-icons/custom-icons/css/custom-icons-ie7.css") }}

        {{ stylesheet_link("css/layout.css") }}

        {# Custom CSS #}
        {{ stylesheet_link("css/custom.css") }}
        {{ stylesheet_link("css/bgstretcher.css") }}
        {{ stylesheet_link("css/buttons.css") }}

        {{ javascript_include("js-plugin/respond/respond.min.js") }}
        {{ javascript_include("js-plugin/jquery/jquery-1.10.2.min.js") }}
        {{ javascript_include("js-plugin/jquery-ui/jquery-ui-1.8.23.custom.min.js") }}
        {{ javascript_include("//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js", false) }}
        {{ javascript_include("js-plugin/easing/jquery.easing.1.3.js") }}

        {{ javascript_include("js-plugin/neko-contact-ajax-plugin/js/jquery.form.js") }}
        {{ javascript_include("js-plugin/neko-contact-ajax-plugin/js/jquery.validate.min.js") }}
        {{ javascript_include("js-plugin/appear/jquery.appear.js") }}
        {#{{ javascript_include("js-plugin/ytplayer/jquery.mb.YTPlayer_modifed.js") }}#}
        {#{{ javascript_include("js-plugin/parallax/js/jquery.stellar.min.js") }}#}
        {#{{ javascript_include("js-plugin/toucheeffect/toucheffects.js") }}#}
        {{ javascript_include("js/jquery.loadmask.js") }}
        {{ javascript_include("js/neko.js") }}
        {{ javascript_include("js/bootstrap-remote-data.min.js") }}
        {{ javascript_include("js/modernizr-2.6.1.min.js") }}

        {{ javascript_include("js/soul.js") }}

        {# Custom javascript #}
    </head>
    <body>
        <div id="globalWrapper">
        <header class="navbar-fixed-top">
        <!-- pre header -->
        <div id="preHeader" class="hidden-xs">
            <div class="container">
                <div class="row">
                    <div class="col-xs-6">
                        <ul class="quickMenu">
                            {#<li><a href="template-site-map.html" class="linkLeft">Site map</a></li>#}
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
                        <a class="navbar-brand" href="index.html"><img src="/img/main-logo.png" alt="Soul-Soldiers"/></a>
                    </div>
                    <!-- Main navigation -->
                    {{ menu }}
                    <!-- End main navigation -->

                </nav>
            </div>
        </div>
        </header>
        <!-- header -->


        <section id="content" class="color2 pt30 pb30">
            <div class="container">
                <div class="row">
                    <div id="messages" class="col-md-8 col-md-offset-2">{{ flash.output() }}</div>
                </div>
            </div>
            <div id="mainContent">
                {{ content() }}
            </div>
        </section>


        <footer id="footerWrapper">
            <section id="mainFooter">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="footerWidget">
                                {#<img src="/img/main-logo.png" alt="Soul-Soldiers" id="footerLogo">#}
                                <p>
                                    &copy; Soul-Soldiers 2014
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="footerWidget">

                                <h3>Soul-Soldiers</h3>
                                <address>
                                    <p>
                                        <i class="icon-mail-alt"></i>&nbsp;<a href="{{ url('contact') }}">Contactformulier</a>
                                    </p>
                                </address>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="footerWidget">
                                <h3>Social Media</h3>
                                <ul class="socialNetwork">
                                    <li><a href="http://facebook.com/soulsoldiers" target="_blank" class="tips" title="" data-original-title="Volg ons op facebook"><i class="icon-facebook-1 iconRounded"></i></a></li>
                                    <li><a href="http://twitter.com/soulsoldiers2" target="_blank" class="tips" title="" data-original-title="Volg ons op Twitter"><i class="icon-twitter-bird iconRounded"></i></a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section id="footerRights">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <p>Copyright &copy; 2014 Soul-Soldiers / All rights reserved.</p>
                        </div>

                    </div>
                </div>
            </section>
        </footer>

        </div>
    </body>
</html>