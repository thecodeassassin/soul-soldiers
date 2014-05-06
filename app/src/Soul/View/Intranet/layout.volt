{% block header %}
<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title>{{ title }}</title>

    <meta name="description" content="Soul-Soldiers is een Nederlandse Lan-Party organisatie gefocust op kleinschalige lan parties">
    <meta name="author" content="Soul-Soldiers">
    <meta name="robots" content="noindex, nofollow">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="apple-touch-icon" href="img/icon57.png" sizes="57x57">
    <link rel="apple-touch-icon" href="img/icon72.png" sizes="72x72">
    <link rel="apple-touch-icon" href="img/icon76.png" sizes="76x76">
    <link rel="apple-touch-icon" href="img/icon114.png" sizes="114x114">
    <link rel="apple-touch-icon" href="img/icon120.png" sizes="120x120">
    <link rel="apple-touch-icon" href="img/icon144.png" sizes="144x144">
    <link rel="apple-touch-icon" href="img/icon152.png" sizes="152x152">
    <!-- END Icons -->

    <!-- Stylesheets -->
    {# Output js/css content #}
    {{ assets.outputCss('main') }}
    {# Custom javascript #}
</head>
{% endblock %}
    <body class="{{ router.getActionName() }}">
    {% block body %}

    {% endblock %}

    {% block footer %}
    <script type="text/javascript">
        var __loading_img = '{{ url('img/ajax-loader.gif') }}';
    </script>

    {{ assets.outputJs('scripts') }}

    {% endblock %}
    </body>
</html>