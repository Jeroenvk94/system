<?php
namespace System\View;

/**
 * View adapter for Smarty template engine
 * Smarty homepage: www.smarty.net
 *
 * @author Orest
 */
class Smarty implements \System\View
{
    protected $smarty;

    public function __construct(\Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    public function display($template, $vars = array())
    {
        echo $this->smarty->fetch($template, $vars);
    }

    public function fetch($template, $vars = array())
    {
        if (!is_array($vars)) {
            $vars = (Array) $vars;
        }

        $this->smarty->clearAllAssign();
        foreach ($vars as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        return $this->smarty->fetch($template, $vars);
    }
}
