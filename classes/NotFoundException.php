<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 30.10.2017
     * Time: 0:11
     */

    namespace CatalogAPI;


    class NotFoundException extends \Exception
    {
        protected $code = '404';
    }