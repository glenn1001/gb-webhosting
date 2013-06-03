<?php

class Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract {

    private $_acl = null;

    public function __construct(Zend_Acl $acl) {
        $this->_acl = $acl;
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        if (!$this->_acl->isAllowed(Zend_Registry::get('role'), $module . ':' . $controller, $action)) {
            $request->setControllerName('auth')
                    ->setActionName('login');
        }
    }

}