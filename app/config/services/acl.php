<?php

$aclConfig = include __DIR__ . '/../aclConfig.php';

$cache = \Phalcon\DI::getDefault()->get('cache');

/**
 * Should there be any errors, log the error and terminate the application
 */

/**
 * if the aclconfig has been changed or the cache no longer holds the acl, rebuild it
 */
$aclConfigKey = crc32(serialize($aclConfig[ACTIVE_MODULE]));

// disable translations cache in development
$disableCache = (APPLICATION_ENV == \Phalcon\Error\Application::ENV_DEVELOPMENT ? true : false);

if (!$cache->exists($aclConfigKey) || $disableCache) {

    $acl = new \Phalcon\Acl\Adapter\Memory();

    // Default action is deny access
    $acl->setDefaultAction(Phalcon\Acl::DENY);

    if (!array_key_exists(ACTIVE_MODULE, $aclConfig)) {
        throw new \Exception(sprintf('No ACL rules found for %s', ACTIVE_MODULE));
    }

    // build the ACL
    \Soul\AclBuilder::build($acl, $aclConfig[ACTIVE_MODULE]);

    if (!$disableCache) {
        $cache->save($aclConfigKey, $acl);
    }

} else {
    $acl = $cache->get($aclConfigKey);
}

// add the ACL to the DI
$di->setShared('acl',  $acl);
