<?php
use \Soul\AclBuilder as AclBuilder;

/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @namespace Soul
 */
return new \Phalcon\Config(
    [
      'main' => [
          'css' => [
              'https://fonts.googleapis.com/css?family=Droid+Sans:400,700',
              'https://fonts.googleapis.com/css?family=Roboto:400,300,500,700',
              'https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css',
              'css/soul-color.css',
              'font-icons/custom-icons/css/custom-icons.css',
              'font-icons/custom-icons/css/custom-icons-ie7.css',
              'js-plugin/magnific-popup/magnific-popup.css',
              'css/layout.css',
              'css/ajax-loader.css',
              'css/custom.css',
              'css/buttons.css'
          ],
          'js' => [
              'js/soul.js'
          ]
      ]

    ]
);
