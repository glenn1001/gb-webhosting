<?php

class Plugin_LayoutCheck extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        $module = $request->getModuleName();
        $layout = Zend_Layout::getMvcInstance();

        // check if module layout exists else disable layout
        if (file_exists(APPLICATION_PATH . '/modules/' . $module . '/views/layouts/layout.phtml')) {
            $layout->setLayoutPath(APPLICATION_PATH . '/modules/' . $module . '/views/layouts/');
            $layout->setLayout('layout');
        } else {
            $layout->disableLayout();
        }
    }

}