<?php

namespace System;

class Translator
{

    protected $di;
    protected $translations = array();
    protected $allowedLocales = array('en-US');
    protected $bestLocale;
    protected $userLocales;

    /**
     * 
     * @param \System\DI $di
     * @param array $translations
     */
    public function __construct(DI $di, array $translations, array $allowedLoacales = array('en-US'))
    {
        $this->di = $di;
        $this->allowedLocales = $allowedLoacales;
        $this->translations = $translations;
    }

    public function _($key, $args = null, $context = null)
    {
        if (!isset($this->translations[$key])) {
            return $this->format($key, $args);
        }

        if (is_array($this->translations[$key])) {
            if (isset($context, $this->translations[$key][$context])) {
                return $this->format($this->translations[$key][$context], $args);
            }

            reset($this->translations[$key]);
            return $this->format(current($this->translations[$key]), $args);
        } else {
            return $this->format($this->translations[$key], $args);
        }
    }

    protected function format($string, $args)
    {
        if (is_array($args)) {
            return vsprintf($string, $args);
        }

        return $string;
    }

    public function getUserLocales()
    {
        if ($this->userLocales !== null) {
            return $this->userLocales;
        }

        $request = $this->di->get('request');
        if (!($request instanceof Request)) {
            throw new Translator\InvalidDIRequestValueException("Request offset of DI must be an instace of System\Request");
        }

        if (($acceptLanguage = $request->server('HTTP_ACCEPT_LANGUAGE')) !== null && strlen($acceptLanguage)) {
            $this->userLocales = $this->parseServerAcceptLanguage($acceptLanguage);
        } else {
            $this->userLocales = array();
        }

        return $this->userLocales;
    }

    protected function parseServerAcceptLanguage($string)
    {
        preg_match_all('#([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?#i', $string, $parsedValues);

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

    private function getAllowedLocale($locale)
    {
        foreach ($this->allowedLocales as $allowedLocale) {
            if (\Locale::getPrimaryLanguage($allowedLocale) == $locale ||
                    \Locale::getPrimaryLanguage($locale) == $allowedLocale) {
                return $allowedLocale;
            }
        }

        return false;
    }

    public function getBestLocale()
    {
        if ($this->bestLocale !== null) {
            return $this->bestLocale;
        }

        if (count($this->allowedLocales) == 0) {
            throw new Translator\AllowedLocalesEmptyException("AllowedLocales array can`t be empty!");
        }

        $userLocales = self::getUserLocales();

        foreach ($userLocales as $userLocale) {
            if (in_array($userLocale, $this->allowedLocales)) {
                $this->bestLocale = $userLocale;
                break;
            }

            if (($locale = self::getAllowedLocale($userLocale, $this->allowedLocales)) !== false) {
                $this->bestLocale = $locale;
                break;
            }
        }

        if (empty($this->bestLocale)) {
            $this->bestLocale = $this->allowedLocales[0];
        }

        return $this->bestLocale;
    }

}
