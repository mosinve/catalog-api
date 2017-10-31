<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 29.10.2017
     * Time: 4:29
     */

    namespace CatalogAPI;

    /**
     * Class JSONResponse
     * @package CatalogAPI
     */
    class JSONResponse extends \GuzzleHttp\Psr7\Response
    {
        /**
         * JSONResponse constructor.
         *
         * @param int $status
         * @param array $headers
         * @param null $body
         * @param string $version
         * @param null $reason
         */
        public function __construct($status = 200, array $headers = [], $body = null, $version = '1.1', $reason = null)
        {
            $jsonHeaders = [
                'Content-Type'                => 'application/json',
                'Access-Control-Allow-Origin' => '*'
            ];
            parent::__construct($status, array_merge($headers, $jsonHeaders), $body, $version, $reason);
        }
    }