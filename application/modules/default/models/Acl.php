<?php

class Model_Acl extends Zend_Acl {

    public function __construct() {
        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('user'), 'guest');
        $this->addRole(new Zend_Acl_Role('admin'), 'user');
        $this->addRole(new Zend_Acl_Role('master'), 'admin');

        $this->add(new Zend_Acl_Resource('default'))
                ->add(new Zend_Acl_Resource('default:index'), 'default')
                ->add(new Zend_Acl_Resource('default:error'), 'default');

        $this->add(new Zend_Acl_Resource('backend'))
                ->add(new Zend_Acl_Resource('backend:index'), 'backend');
        
        $this->allow('guest', 'default:index', 'index');
        $this->allow('guest', 'default:error', 'error');
        $this->allow('guest', 'backend:index', 'index');
    }

}