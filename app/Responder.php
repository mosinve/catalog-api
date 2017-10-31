<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 31.10.2017
     * Time: 20:17
     */

    namespace CatalogAPI;


    class Responder
    {
        public static function error(\Exception $exception)
        {
            $code   = $exception->getCode();
            $result = $exception->getMessage();

            $errMsg = new ErrorResponse($code, []);

            $error = [
                    'status' => $code,
                    'title'  => $errMsg->getReasonPhrase(),
                    'detail' => $result
            ];

            $errMsg->getBody()->write(json_encode($error));

            return $errMsg;
        }
    }