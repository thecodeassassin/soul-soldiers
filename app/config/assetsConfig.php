<?php
use \Soul\AclBuilder as AclBuilder;

/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @namespace Soul
 */
return new \Phalcon\Config(
    [
        /**
         * Website assets
         */

        'website' => [

            'main' => [
                'css' => [
                  'css/droidsans.css',
                  'css/roboto.css',
                  'font-icons/custom-icons/css/custom-icons-ie7.css',
                  'font-icons/custom-icons/css/custom-icons.css',
                  'css/bootstrap.min.css',
                  'css/soul-color.css',
                  'js-plugin/magnific-popup/magnific-popup.css',
                  'css/layout.css',
                  'css/ajax-loader.css',
                  'css/custom.css',
                  'css/buttons.css',
                  'css/common.css',
                  'css/slick.css',
                  'css/slick-theme.css'
                ]
            ],

            'scripts' => [
                'js' => [

                    "js-plugin/respond/respond.min.js",
                    "js-plugin/jquery/jquery-1.10.2.min.js",
                    "js/blockui.js",
                    "js/common.js",
                    "js/jquery-ui.min.js",
                    "js/bootstrap.min.js",
                    "js-plugin/easing/jquery.easing.1.3.js",

                    "js-plugin/neko-contact-ajax-plugin/js/jquery.form.js",
                    "js-plugin/neko-contact-ajax-plugin/js/jquery.validate.min.js",
                    "js-plugin/appear/jquery.appear.js",
                    "js/jquery.loadmask.js",
                    "js/jquery.validate.nl.js",
                    "js/magnificent.popup.min.js",
                    "js/bootbox.min.js",
                    "js/remote-modal.js",
                    "js/modernizr-2.6.1.min.js",
                    "js/neko.js",
                    "js/bootstrap-remote-data.min.js",
                    "js/jquery.stellar.min.js",
                    "js/jquery.stellar.min.js",


                    "js/soul.js",
                    "js/forum.js",
                    "js/slick.min.js"
              ]
          ]
        ],

        /**
         * Intranet assets
         */

        'intranet' => [

            'main' => [
                'css' => [
                    'css/roboto.css',
                    'css/bootstrap.min.css',
//                    'css/intranet/bootstrap.css',
                    'font-icons/custom-icons/css/custom-icons-ie7.css',
                    'font-icons/custom-icons/css/custom-icons.css',
                    'js-plugin/magnific-popup/magnific-popup.css',
                    'css/intranet/plugins.css',
                    'css/intranet/chat.css',
                    'css/intranet/themes/deepblue.css',
                    'css/intranet/main.css',
                    'css/ajax-loader.css',
                    'css/datepicker.min.css',
                    'css/common.css'
                ]
            ],

            'scripts' => [
                'js' => [
                    "js-plugin/jquery/jquery-1.10.2.min.js",
                    "js/jquery.json.min.js",
                    "js/blockui.js",
                    "js/common.js",
                    "js/intranet/plugins.js",
                    "js/maskedinput.js",
                    'js/modernizr-2.6.1.min.js',
                    "js/magnificent.popup.min.js",
//                    "js/jquery.loadmask.js",
                    "js/bootbox.min.js",
                    "js/remote-modal.js",
//                    "js/jquery-ui.min.js",
                    "js/intranet/main.js",
                    "js/intranet/handlebars.min.js",
                    "js/notify.js",
                    "js/noty.js",
                    // "js/nanoscroller.js",
                    "js/bootstrap.min.js"
                ]
            ]
        ]
    ]
);
