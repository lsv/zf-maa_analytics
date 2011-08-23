<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
    protected function getViewBootstrap()
    {
        $this->bootstrap('view');
        return $this->getResource('view');
    }

    protected function _initDoctype()
    {
        $view = $this->getViewBootstrap();
        $view->doctype('HTML5');
    }
    
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
        return $config;
    }
    
}

