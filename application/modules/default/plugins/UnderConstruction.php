<?php

class Plugin_UnderConstruction extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        if ($_SERVER['REMOTE_ADDR'] != '80.57.73.199') {
            $request->setModuleName('default')
                    ->setControllerName('construction')
                    ->setActionName('index');
        }
    }

}