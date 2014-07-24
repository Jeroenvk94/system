<?php
namespace System\View;

/**
 * View adapter for Smarty template engine
 * 
 * Smarty homepage: www.smarty.net
 *
 * @author Orest
 */
class Smarty implements \System\View {

    protected $_smarty;

    public function __construct(\Smarty $smarty) {
        $this->_smarty = $smarty;
    }

    public function display($template, $vars = array()) {
        if (!is_array($vars)) {
            $vars = (Array) $vars;
        }
        
        $this->_smarty->clear_all_assign();
        foreach ($vars as $key => $value) {
            $this->_smarty->assign($key, $value);
        }

        $this->_smarty->display($template, $vars);
    }

    public function fetch($template, $vars = array()) {
        if (!is_array($vars)) {
            $vars = (Array) $vars;
        }
        
        $this->_smarty->clear_all_assign();
        foreach ($vars as $key => $value) {
            $this->_smarty->assign($key, $value);
        }

        return $this->_smarty->fetch($template, $vars);
    }
}
