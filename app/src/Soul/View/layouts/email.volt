<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0">    <!-- So that mobile webkit will display zoomed in -->
    <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->

    <title>{{ subject }}</title>

    <style type="text/css">

        /* Resets: see reset.css for details */
        .ReadMsgBody { width: 100%; background-color: #ebebeb;}
        .ExternalClass {width: 100%; background-color: #ebebeb;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;}
        body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}
        body {margin:0; padding:0;}
        table {border-spacing:0;}
        table td {border-collapse:collapse;}
        .yshortcuts a {border-bottom: none !important;}

        a, a:visited, a:hover, a:focus {
            color: #ffffff;
        }

        /* Constrain email width for small screens */
        @media screen and (max-width: 600px) {
            table[class="container"] {
                width: 95% !important;
            }
        }

        /* Give content more room on mobile */
        @media screen and (max-width: 480px) {
            td[class="container-padding"] {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
        }


        /* Styles for forcing columns to rows */
        @media only screen and (max-width : 600px) {

            /* force container columns to (horizontal) blocks */
            td[class="force-col"] {
                display: block;
                padding-right: 0 !important;
            }
            table[class="col-2"] {
                /* unset table align="left/right" */
                float: none !important;
                width: 100% !important;

                /* change left/right padding and margins to top/bottom ones */
                margin-bottom: 12px;
                padding-bottom: 12px;
                border-bottom: 1px solid #eee;
            }

            /* remove bottom border for last column/row */
            table[id="last-col-2"] {
                border-bottom: none !important;
                margin-bottom: 0;
            }

            /* align images right and shrink them a bit */
            img[class="col-2-img"] {
                float: right;
                margin-left: 6px;
                max-width: 130px;
            }
        }


    </style>

</head>
<body style="margin:0; padding:10px 0;" bgcolor="#426d84" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<br>

<!-- 100% wrapper (grey background) -->
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#426d84" style="background-color: #426d84">
    <tr>
        <td align="center" valign="top" bgcolor="#426d84" style="background-color: #426d84;">

            <!-- 700px container (white background) -->
            <table border="0" width="700" cellpadding="0" cellspacing="0" class="container" bgcolor="#3c3b3b">
                <tr>
                    <td class="container-padding" bgcolor="#3c3b3b" style="background-color: #3c3b3b; padding-left: 30px; padding-right: 30px; font-size: 13px; line-height: 20px; font-family: Helvetica, sans-serif; color: #ffffff;" align="left">
                        {{ image('img/main-logo.png') }}
                        <br><br>

                        {{ content() }}

                        ---<br>
                        Met vriendelijke groet,<br>
                        Soul-Soldiers

                        <br><br>
                        <center>
                            <p style="text-align:center;"><a href="{{ url('terms') }}">Algemene voorwaarden</a> {% if user.id != 0 %} | <a href="{{ url('unsubscribe/'+user.id) }}">Uitschrijven</a>{% endif %}</p>
                        </center>

                    </td>
                </tr>
            </table>
            <!--/600px container -->

        </td>
    </tr>
</table>
<!--/100% wrapper-->
<br>
<br>
</body>
</html>
