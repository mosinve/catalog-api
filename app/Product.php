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
            $this->name   = $data['name'];
            $this->weight = $data['weight'];
            $this->size   = $data['size'];
            $this->price  = $data['price'];
            $this->type   = $data['type'];
            if (isset($data['id'])) {
                $this->id = $data['id'];
            }

        }

        /**
         * @return string
         */
        public function __toString()
        {
            return json_encode($this);
        }
    }