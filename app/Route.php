<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 29.10.2017
     * Time: 14:11
     */

    namespace CatalogAPI;

    /**
     * Class Route
     * @package CatalogAPI
     */
    class Route
    {
        /**
         * @var callable
         */
        private $callback;


        /**
         * @var string
         */
        private $uriPattern;

        /**
         * @var
         */
        private $params;

        private const PARAMS_REGEX = '/\\\?:\s*([\w-]*)\s*/';

        /**
         * Route constructor.
         *
         * @param $callback
         * @param string $uriPattern
         */
        public function __construct($callback, string $uriPattern)
        {
            $this->callback   = $callback;
            $this->uriPattern = $uriPattern;
            $this->fetchParams($this->uriPattern);
        }

        /**
         * @return mixed
         */
        public function getCallback()
        {
            return $this->callback;
        }

        /**
         * @param mixed $callback
         *
         * @return Route
         */
        public function setCallback($callback)
        {
            $this->callback = $callback;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getUriPattern()
        {
            return $this->uriPattern;
        }

        /**
         * @param mixed $uriPattern
         *
         * @return Route
         */
        public function setUriPattern($uriPattern)
        {
            $this->uriPattern = $uriPattern;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getParams()
        {
            return $this->params;
        }

        /**
         * @param $uri
         */
        public function fetchParams($uri)
        {
            preg_match_all(static::PARAMS_REGEX, $uri, $matches, PREG_SET_ORDER);
            foreach ($matches as [$placeholder, $param]) {
                $this->params[$placeholder] = $param;
            }
        }

        /**
         * @return string
         */
        public function __toString()
        {
            return (string)preg_replace(self::PARAMS_REGEX, '(.*)', preg_quote($this->uriPattern, '/'));
        }

        /**
         * @return bool
         */
        public function hasParams(): bool
        {
            return ! ! $this->params;
        }
    }