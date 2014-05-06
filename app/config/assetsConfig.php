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
                  'https://fonts.googleapis.com/css?family=Droid+Sans:400,700',
                  'https://fonts.googleapis.com/css?family=Roboto:400,300,500,700',
                  'font-icons/custom-icons/css/custom-icons-ie7.css',
                  'font-icons/custom-icons/css/custom-icons.css',
                  'https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css',
                  'css/soul-color.css',
                  'js-plugin/magnific-popup/magnific-popup.css',
                  'css/layout.css',
                  'css/ajax-loader.css',
                  'css/custom.css',
                  'css/buttons.css',
                  'css/common.css'
                ]
            ],

            'scripts' => [
                'js' => [

                    "js-plugin/respond/respond.min.js",
                    "js-plugin/jquery/jquery-1.10.2.min.js",
                    "js-plugin/jquery-ui/jquery-ui-1.8.23.custom.min.js",
                    "https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js",
                    "js-plugin/easing/jquery.easing.1.3.js",

                    "js-plugin/neko-contact-ajax-plugin/js/jquery.form.js",
                    "js-plugin/neko-contact-ajax-plugin/js/jquery.validate.min.js",
                    "js-plugin/appear/jquery.appear.js",
                    "js/jquery.loadmask.js",
                    "js/jquery.validate.nl.js",
                    "js/magnificent.popup.min.js",
                    "js/bootbox.min.js",
                    "js/neko.js",
                    "js/bootstrap-remote-data.min.js",
                    "js/modernizr-2.6.1.min.js",
                    "js/jquery.stellar.min.js",

                    "js/blockui.js",

                    "js/soul.js"
              ]
          ]
        ],

        /**
         * Intranet assets
         */

        'intranet' => [

            'main' => [
                'css' => [
                    'https://fonts.googleapis.com/css?family=Roboto:400,300,500,700',
                    'https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css',
                    'font-icons/custom-icons/css/custom-icons-ie7.css',
                    'font-icons/custom-icons/css/custom-icons.css',
                    'css/intranet/plugins.css',
                    'css/intranet/themes/deepblue.css',
                    'css/intranet/main.css',
                    'css/common.css'
                ]
            ],

            'scripts' => [
                'js' => [
                    'js/modernizr-2.6.1.min.js',
                    "js/jquery.loadmask.js",
                    "js/jquery.validate.nl.js",
                    "js/bootbox.min.js",
                    "js-plugin/jquery/jquery-1.10.2.min.js",
                    "js-plugin/jquery-ui/jquery-ui-1.8.23.custom.min.js",
                    "js/intranet/plugins.js",
                    "https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"
                ]
            ]
        ]
    ]
);
