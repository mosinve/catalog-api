<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 27.10.2017
     * Time: 22:14
     */

    namespace CatalogAPI;

    /**
     * Class Product
     * @package CatalogAPI
     */
    class Product
    {
        /**
         * @var string
         */
        static public $table = 'products';
        /**
         * @var string
         */
        static public $primaryKey = 'id';
        /**
         * @var
         */
        public $name;

        private $fillable = ['id', 'name', 'type', 'size', 'price', 'weight'];
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
         * Product constructor.
         *
         * @param $data
         */
        public function __construct($data)
        {

            $data = $this->processData($data);
            foreach ($data as $key=>$value){
                $this->$key = $value;
            }

        }

        /**
         * @return string
         */
        public function __toString()
        {
            return json_encode($this, JSON_NUMERIC_CHECK);
        }

        private function processData(array $data):array
        {
            return array_filter($data, function ($key){
                return in_array($key, $this->fillable, true);
            }, ARRAY_FILTER_USE_KEY);
        }
    }