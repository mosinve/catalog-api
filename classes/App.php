<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 25.10.2017
     * Time: 20:25
     */

    namespace CatalogAPI;

    use Psr\Http\Message\ResponseInterface;

    class App
    {
        public $db;
        public $api;
        public $catalog;
        public $controller;

        public function __construct($config)
        {
            $this->db         = new DB($config['database']);
            $this->api        = new Router($config['router']);
            $this->catalog    = new Catalog($this->db);
            $this->controller = new Controller($this->catalog);
        }

        public function run()
        {
            // echo 1;
            $response = $this->api->handleRequest(Request::fromGlobals());
            $this->renderResponse($response);
        }

        private function renderResponse(ResponseInterface $response): void
        {

            if ( ! headers_sent()) {
                header(sprintf(
                    'HTTP/%s %s %s',
                    $response->getProtocolVersion(),
                    $response->getStatusCode(),
                    $response->getReasonPhrase()
                ));

                foreach ($response->getHeaders() as $name => $values) {
                    foreach ($values as $value) {
                        header(sprintf('%s: %s', $name, $value), false);
                    }
                }
            }

            $body          = $response->getBody();
            $contentLength = $response->getHeaderLine('Content-Length');
            if ( ! $contentLength) {
                $contentLength = $body->getSize();
            }



            if ($contentLength) {
                print $body;
            }

        }
    }