<?php
/**
 * Created by PhpStorm.
 * User: mosinve
 * Date: 30.10.2017
 * Time: 8:24
 */

namespace CatalogAPI;


use GuzzleHttp\Psr7\Response;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Neomerx\Cors\Strategies\Settings;
use Psr\Http\Message\ServerRequestInterface;
use \Neomerx\Cors\Analyzer;
use \Neomerx\Cors\Contracts\AnalysisResultInterface;

class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $cors = Analyzer::instance($this->getCorsSettings())->analyze($request);

        switch ($cors->getRequestType()) {
            case AnalysisResultInterface::ERR_NO_HOST_HEADER:
            case AnalysisResultInterface::ERR_ORIGIN_NOT_ALLOWED:
            case AnalysisResultInterface::ERR_METHOD_NOT_SUPPORTED:
            case AnalysisResultInterface::ERR_HEADERS_NOT_SUPPORTED:
                // return 4XX HTTP error
                throw new NotFoundException();

            case AnalysisResultInterface::TYPE_PRE_FLIGHT_REQUEST:
                $corsHeaders = $cors->getResponseHeaders();

                // return 200 HTTP with $corsHeaders
                return new Response('200',$corsHeaders);

            case AnalysisResultInterface::TYPE_REQUEST_OUT_OF_CORS_SCOPE:
                // call next middleware handler
                return $handler->handle($request);

            default:
                // actual CORS request
                $response = $handler->handle($request);
                $corsHeaders = $cors->getResponseHeaders();

                // add CORS headers to Response $response

                foreach ($corsHeaders as $name => $value) {
                    $response = $response->withHeader($name, $value);
                }
                return $response;
        }
    }

    private function getCorsSettings()
    {
        return (new Settings())->setCheckHost(true)->setRequestAllowedOrigins(['*']);
    }
}
