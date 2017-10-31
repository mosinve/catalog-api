<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 27.10.2017
     * Time: 20:06
     */

    namespace CatalogAPI;

    use GuzzleHttp\Psr7\LazyOpenStream;
    use GuzzleHttp\Psr7\ServerRequest;

    /**
     * Class Request
     * @package CatalogAPI
     */
    class Request extends ServerRequest
    {
        /**
         * @param $name
         *
         * @return mixed
         */
        public function get($name)
        {
            return $this->getAttribute('params')[$name];
        }

        /**
         * @return mixed
         */
        public static function fromGlobals()
        {
            $method   = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
            $headers  = function_exists('getallheaders') ? getallheaders() : [];
            $uri      = self::getUriFromGlobals();
            $body     = new LazyOpenStream('php://input', 'r+');
            $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '',
                $_SERVER['SERVER_PROTOCOL']) : '1.1';

            $serverRequest = new static($method, $uri, $headers, $body, $protocol, $_SERVER);

            return $serverRequest
                ->withCookieParams($_COOKIE)
                ->withQueryParams($_GET)
                ->withParsedBody($_POST)
                ->withUploadedFiles(self::normalizeFiles($_FILES));
        }
    }