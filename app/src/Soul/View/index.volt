<!--[if IE 6 ]><html lang="en-us" class="ie6"> <![endif]-->
<!--[if IE 7 ]><html lang="en-us" class="ie7"> <![endif]-->
<!--[if IE 8 ]><html lang="en-us" class="ie8"> <![endif]-->
<!--[if (gt IE 7)|!(IE)]><!-->
<!DOCTYPE html>
<html lang="en-us">
    <!--<![endif]-->
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ title }}</title>

        {# Output js/css content #}
        {{ assets.outputCss('main') }}
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
                        <a class="navbar-brand" href="{{ url('home') }}"><img src="/img/main-logo.png" alt="Soul-Soldiers"/></a>
                    </div>
                    <!-- Main navigation -->
                    {{ menu }}
                    <!-- End main navigation -->

                </nav>
            </div>
        </div>
        </header>
        <!-- header -->


        <section id="content" class="color2 pb30 pt30">

            <div class="container">
                <div class="row pt30">
                    <div id="messages" class="col-md-8 col-md-offset-2">
                        {{ flash.output() }}
                        {{ flashSession.output() }}
                    </div>
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
                                <img src="/img/main-logo.png" alt="Soul-Soldiers" id="footerLogo">

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="footerWidget">

                                <h3>Soul-Soldiers</h3>
                                <address>
                                    <p>
                                        <i class="icon-mail-alt"></i>&nbsp;<a href="{{ url('contact') }}">Contact</a> <br />
                                        <i class="icon-phone"></i>&nbsp; 084-8727663 (Ma-Vr 09:00 - 19:00)<br />
                                        <i class="icon-briefcase"></i>&nbsp; KVK te Den Haag: 50981633
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
                            <p>Copyright &copy; 2014 Soul-Soldiers / Alle rechten voorbehouden.</p>
                        </div>

                    </div>
                </div>
            </section>
        </footer>

        </div>
        <script type="text/javascript">
            var __loading_img = '{{ url('img/ajax-loader.gif') }}';
        </script>

        {{ assets.outputJs('scripts') }}

        <script type="text/javascript">
            window.onload = function () { "use strict"; gaSSDSLoad("{{ analyticsCode }}"); }; //load after page onload
        </script>
    </body>
</html>
