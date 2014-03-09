<?php
$di->set('crypt', function() use($config) {
    $crypt = new \Phalcon\Crypt();

//    $crypt->setCipher('TWOFISH256');
    $crypt->setKey($config->crypt->key);

    return $crypt;
});