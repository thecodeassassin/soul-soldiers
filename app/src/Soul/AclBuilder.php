<?php
/**
 * @author Stephen Hoogendijk
 * @namespace Soul
*/
namespace Soul;
use Phalcon\Acl;
use Phalcon\Config;
use Phalcon\DI;
use Phalcon\Exception;
use Phalcon\Mvc\User\Plugin;


/**
 * The ACLBuilder builds the given acl configuration and applies it to the given ACL object.
 *
 * @todo improve documentation
 *
*/
class AclBuilder extends Plugin
{

    const PUBLIC_RESOURCE = 'public';

    const ROLE_ADMIN = 3;
    const ROLE_MODERATOR = 2;
    const ROLE_USER = 1;
    const ROLE_GUEST = 0;

    /**
     * @var Acl\Adapter\Memory
     */
    protected $acl;

    /**
     * @var \Phalcon\Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $roles = array();


    /**
     * Method for adding the acl to the di container
     */
    public function addAclToDi()
    {
        $this->di->setShared('acl', $this->acl);
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param Acl\Adapter\Memory $acl
     * @param \Phalcon\Config $config
     * @return \Soul\AclBuilder
     */
    protected  function __construct(Acl\Adapter\Memory $acl, Config $config)
    {

        $this->config = $config;
        $this->acl = $acl;

    }

    /**
     *
     */
    protected function addRoles()
    {
        foreach($this->config->roles as $role) {
            $this->acl->addRole($this->roles[strtolower($role)] = new Acl\Role($role));
        }
    }

    /**
     * Sets access rights for public controllers
     * @return bool
     * @throws \Phalcon\Acl\Exception
     */
    protected function processPublicControllers()
    {
        $publicControllers = $this->config->publiccontrollers;

        // if the public resources config does not exist, or is not a valid array, skip processing it.
        if (!$publicControllers) {
            return false;
        }

        foreach ($publicControllers as $controller) {

            if (!is_string($controller)) {
                throw new Acl\Exception('Invalid controller specified as public');
            }

            foreach ($this->roles as $role) {
                $this->processActions($role, $controller, '*');
            }
        }

        return true;
    }

    /**
     *
     */
    protected function processResources()
    {
        if (count($this->roles) == 0) {
            throw new Exception('Add the roles before processing the resources');
        }

        // We iterate over the role list backwards because the highest should inherit the lowest rights
        $resourcesList = $this->config->resources->toArray();
        $inheritedResources = array();
        $reversedRoleList = array_reverse($this->roles);

        // set all the rights for the users according to the ACL
        foreach ($reversedRoleList as $role) {
            $roleName = $role->getName();


            if (array_key_exists($roleName, $resourcesList)) {

                // the resource has to be a valid resource
                if (!is_array($resourcesList[$roleName])) {
                    throw new Acl\Exception(sprintf('Invalid resource defined: %s', $resourcesList[$roleName]));
                }
                $inheritedResources[] = $resourcesList[$roleName];

                foreach ($inheritedResources as $resources) {

                    if (!is_array($resources)) {
                        throw new Acl\Exception('Invalid resource type specified');
                    }

                    foreach ($resources as $controller => $actions) {
                        if (!is_string($controller)) {
                            throw new Acl\Exception(sprintf('Invalid controller defined: %s (array expected)',
                                $controller));
                        }

                        // allow actions for this specific role
                        $this->processActions($role, $controller, $actions, 'allow');

                    }

                }
            }
        }

    }

    /**
     * @param Acl\Role $role
     * @param string   $controller
     * @param array    $actions
     * @param string   $method
     *
     * @throws \Phalcon\Acl\Exception
     */
    protected function processActions(Acl\Role $role, $controller, $actions, $method = 'allow')
    {
        if(!in_array($method, array('allow', 'deny'))){
            throw new Acl\Exception(sprintf('Invalid %s method: %s. Allowed methods: allow, deny', __FUNCTION__, $method));
        }

        $this->acl->addResource(new Acl\Resource($controller), $actions);

        if (is_string($actions)) {
            $this->acl->$method($role->getName(), $controller, $actions);
        } elseif (is_array($actions)) {
            foreach ($actions as $action) {
                $this->acl->$method($role->getName(), $controller, $action);
            }
        }

    }

    /**
     * @param \Phalcon\Acl\Adapter\Memory $acl
     * @param Config                      $config
     */
    public static function build(Acl\Adapter\Memory $acl, Config $config)
    {
        $obj = new self($acl, $config);

        // add the roles to the acl
        $obj->addRoles();

        // process the public controllers
        $obj->processPublicControllers();

        // process the resources
        $obj->processResources();

    }

}