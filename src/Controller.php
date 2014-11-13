<?php

namespace System;

abstract class Controller
{

    protected $layoutTemplate = 'index.htm';
    protected $viewTemplate = 'index/index.htm';
    protected $view;
    protected $params = array();

    /**
     *
     * @var DI
     */
    public $di;

    final public function __construct($di)
    {
        $this->di = $di;
        $this->view = new \stdClass();
    }

    /**
     * init actions
     */
    protected function init()
    {
        
    }

    /**
     * actions before call controller action
     */
    protected function preAction()
    {
        
    }

    /**
     * actions after call controller action
     */
    protected function postAction()
    {
        
    }

    public function response()
    {
        if ($this->viewTemplate !== false) {
            $view = $this->di->get('view');

            if (!($view instanceof ViewInterface)) {
                throw new DI\InvalidOffsetException('DI View must be an instance of System\View');
            }

            $this->view->di = $this->di;

            if ($this->layoutTemplate == false) {
                echo $view->fetch($this->viewTemplate, $this->view);
            } else {
                $this->view->content = $view->fetch($this->viewTemplate, $this->view);

                echo $view->fetch($this->layoutTemplate, $this->view);
            }
        }
    }

    public function callAction($name)
    {
        if (method_exists($this, $name . 'Action')) {
            $this->init();
            $this->preAction();
            $this->{$name . 'Action'}();
            $this->postAction();
            $this->response();
            return true;
        }

        return false;
    }

    public function makeJSONResponse($data)
    {
        header('Content-Type: application/javascript');
        echo json_encode($data);
        die;
    }

    public function setLayout($template)
    {
        $this->layoutTemplate = $template;
    }

    public function setView($template)
    {
        $this->viewTemplate = $template;
    }

    public function disableView()
    {
        $this->viewTemplate = false;
    }

    public function disableLayout()
    {
        $this->layoutTemplate = false;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Get Request object
     * 
     * @return Request
     */
    public function getRequest()
    {
        return $this->di->get('request');
    }

    /**
     * 
     * @return DI
     */
    public function getDI()
    {
        return $this->di;
    }

    public function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        die;
    }

}
