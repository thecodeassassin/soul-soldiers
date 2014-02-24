<?php
/**
 * Register the flash service with custom CSS classes
 */
$di->set('flash', function(){
    $flash = new Phalcon\Flash\Session(array(
        'error' => 'alert alert-danger',
        'warning' => 'alert alert-warning',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
    ));
    return $flash;
});