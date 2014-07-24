<?php

namespace System;

abstract class Controller
{

    protected $layoutTemplate = 'index.htm';
    protected $viewTemplate = 'index/index.htm';
    public $view;
    public $params = array();

    /**
     *
     * @var DI
     */
    public $di;

    public final function __construct($di)
    {
        $this->di = $di;
        $this->view = new \stdClass();
    }

    protected function init()
    {
        //init actions
    }

    protected function preAction()
    {
        //actions before call controller action
    }

    protected function postAction()
    {
        //actions after call controller action
    }

    public function response()
    {
        if ($this->viewTemplate !== false) {
            $view = $this->di->get('view');

            if (!($view instanceof \System\View)) {
                throw new \Exception('DI View must be an instance of System\View');
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
            $this->_init();
            $this->_preAction();
            $this->{$name . 'Action'}();
            $this->_postAction();
            $this->response();
            return true;
        }

        return false;
    }

    public function makeJSONResponse($data)
    {
        header('Content-Type: application/javascript');
        echo @json_encode($data);
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

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
                && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        die;
    }
}
