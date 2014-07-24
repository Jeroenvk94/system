<?php
namespace System\View;

/**
 * View class for templates written on native PHP code
 *
 * @author Orest
 */
class Native implements \System\View
{

    public function display($template, $vars = array())
    {
        echo $this->fetch($template, $vars);
    }

    public function fetch($template, $vars = array())
    {
        if (!is_array($vars)) {
            $vars = (Array) $vars;
        }

        ob_start();
        extract($vars);
        include $template;
        return ob_get_clean();
    }

}
