<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 27.10.2017
     * Time: 22:14
     */

    namespace CatalogAPI\models;

    /**
     * Class Product
     * @package CatalogAPI
     */
    class Product extends DBModel
    {

        /**
         * @var string
         */
        public static $table = 'products';
        /**
         * @var string
         */
        public static $primaryKey = 'id';

        protected $fillable = ['id', 'name', 'type', 'size', 'price', 'weight'];
        /**
         * @var
         */
        public $name;

        /**
         * @var
         */
        public $id;
        /**
         * @var
         */
        public $weight;
        /**
         * @var
         */
        public $size;
        /**
         * @var
         */
        public $price;
        /**
         * @var
         */
        public $type;


        /**
         * @return string
         */
        public function __toString()
        {
            return json_encode($this, JSON_NUMERIC_CHECK);
        }

    }
