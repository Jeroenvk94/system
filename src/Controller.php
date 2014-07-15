<?php

namespace System;

abstract class Controller {

    protected $_layout = 'index.htm';
    protected $_view = 'index/index.htm';
    public $view;
    public $params = array();

    /**
     *
     * @var DI
     */
    public $di;

    public final function __construct($di) {
        $this->di = $di;
        $this->view = new \stdClass();
    }

    protected function _init() {
        
    }

    protected function _preAction() {
        
    }

    protected function _postAction() {
        
    }

    public function response() {
        if ($this->_view !== false) {
            $view = $this->di->get('view');
            
            if (!($view instanceof \System\View)) {
                throw new \Exception('DI View must be an instance of System\View');
            }
            
            if ($this->_layout == false) {
                echo $view->fetch($this->_view, $this->view);
            } else {
                $this->view->content = $view->fetch($this->_view);

                echo $view->fetch($this->_view, $this->view);
            }
        }
    }

    public function callAction($name) {
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

    public function makeJSONResponse($data) {
        header('Content-Type: application/javascript');
        echo @json_encode($data);
        die;
    }

    public function setLayout($template) {
        $this->_layout = $template;
    }

    public function setView($template) {
        $this->_view = $template;
    }

    public function disableView() {
        $this->_view = false;
    }

    public function disableLayout() {
        $this->_layout = false;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function redirect($url, $statusCode = 302) {
        header('Location: ' . $url, true, $statusCode);
        die;
    }

}