<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 30.10.2017
     * Time: 0:11
     */

    namespace CatalogAPI;

    /**
     * Class NotFoundException
     * @package CatalogAPI
     */
    class ValidationException extends \Exception
    {
        /**
         * @var string
         */
        protected $code = '400';
    }