<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 26.10.2017
     * Time: 23:56
     */

    namespace CatalogAPI;


    use function GuzzleHttp\Psr7\uri_for;
    use GuzzleHttp\Psr7\UriResolver;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ServerRequestInterface;

    /**
     * Class Router
     * @package CatalogAPI
     */
    class Router
    {
        /**
         * @var Route[][]
         */
        private $routes = [];

        /**
         * @var array
         */
        private static $methods = ['POST', 'GET', 'PUT', 'DELETE', 'OPTIONS'];

        /**
         * @var string
         */
        private $basePath;

        /**
         * API constructor.
         *
         * @param $config array
         */
        public function __construct(array $config)
        {
            $this->basePath = $config['basepath'];
        }

        /**
         * @param $methods string|array
         * @param $uri string
         * @param $callback callable
         *
         * @return Route
         */
        public function addRoute(string $uri, callable $callback, $methods = null): Route
        {
            $route = new Route($callback, $uri);
            foreach ($methods ? (array)$methods : self::$methods as $method) {
                $this->routes[$method][] = $route;
            }

            return $route;
        }

        /**
         * @param $uri
         * @param callable $callback
         */
        public function get($uri, callable $callback): void
        {
            $this->addRoute($uri, $callback, 'GET');
        }

        /**
         * @param $uri
         * @param callable $callback
         */
        public function post($uri, callable $callback): void
        {
            $this->addRoute($uri, $callback, 'POST');
        }

        /**
         * @param $uri
         * @param callable $callback
         */
        public function put($uri, callable $callback): void
        {
            $this->addRoute($uri, $callback, 'PUT');
        }

        /**
         * @param $uri
         * @param callable $callback
         */
        public function delete($uri, callable $callback): void
        {
            $this->addRoute($uri, $callback, 'DELETE');
        }

        /**
         * @param $request ServerRequestInterface
         *
         * @return mixed
         */
        public function handleRequest(ServerRequestInterface $request)
        {
            try {
                return $this->process($this->getMatchedRoute($request));
            } catch (\Exception $e) {
                $code   = $e->getCode();
                $result = $e->getMessage();

                $errMsg = new ErrorResponse($code, []);

                $error = [
                    'errors' => [
                        'status' => $code,
                        'title'  => $errMsg->getReasonPhrase(),
                        'detail' => $result
                    ]
                ];

                $errMsg->getBody()->write(json_encode($error));

                return $errMsg;
            }
        }


        /**
         * @param ServerRequestInterface $request
         *
         * @return ServerRequestInterface
         * @throws NotFoundException
         */
        private function getMatchedRoute(ServerRequestInterface $request): ServerRequestInterface
        {
            $methodRoutes = $this->routes[$request->getMethod()];
            foreach ($methodRoutes as $route) {
                if (preg_match('/' . preg_quote($this->basePath, '/') . $route . '/',
                    UriResolver::resolve(uri_for($this->basePath), $request->getUri()), $matches)) {
                    array_shift($matches);
                    $request = $request->withAttribute('callback', $route->getCallback());
                    if ($route->hasParams()) {
                        $params  = array_combine(array_values($route->getParams()), $matches);
                        $request = $request->withAttribute('params', $params);
                    }

                    return $request;
                }
            }
            throw new NotFoundException('Nothing found');
        }

        /**
         * @param $request ServerRequestInterface
         *
         * @return mixed
         */
        private function process(ServerRequestInterface $request): ResponseInterface
        {
            $callback = $request->getAttribute('callback');

            /**
             * @var ResponseInterface $response
             */
            return $callback($request);
        }
    }