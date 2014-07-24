<?php

namespace System;

class Translator
{

    protected $_translations = array();

    public function __construct(array $translations)
    {
        $this->_translations = $translations;
    }

    public function _($key, $args = null, $context = null)
    {
        if (isset($this->_translations[$key])) {
            if (is_array($this->_translations[$key])) {
                if (isset($context, $this->_translations[$key][$context])) {
                    return $this->_format($this->_translations[$key][$context], $args);
                }

                reset($this->_translations[$key]);
                return $this->_format(current($this->_translations[$key]), $args);
            } else {
                return $this->_format($this->_translations[$key], $args);
            }
        }

        return $this->_format($key, $args);
    }

    protected function _format($string, $args)
    {
        if (is_array($args)) {
            return vsprintf($string, $args);
        }

        return $string;
    }

    static function getUserLocales()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strlen($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            preg_match_all('#([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?#i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $parsedValues);

            if (count($parsedValues[1])) {
                $result = array_combine($parsedValues[1], $parsedValues[4]);

                foreach ($result as $key => $value) {
                    if (!strlen($value)) {
                        $result[$key] = 1;
                    } else {
                        $result[$key] = (float) $value;
                    }
                }

                arsort($result, SORT_NUMERIC);
                return array_keys($result);
            }
        }

        return false;
    }

    static function getBestLocale(array $allowedLocales)
    {
        $userLocales = self::getUserLocales();
        if ($userLocales) {
            foreach ($userLocales as $userLocale) {
                if (in_array($userLocale, $allowedLocales)) {
                    return $userLocale;
                }

                foreach ($allowedLocales as $locale) {
                    if (\Locale::getPrimaryLanguage($locale) == $userLocale) {
                        return $locale;
                    }
                }
            }
        }

        return array_shift($allowedLocales);
    }

}
