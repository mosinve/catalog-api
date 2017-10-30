<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 27.10.2017
     * Time: 22:14
     */

    namespace CatalogAPI;


    class Product
    {
        static public $table = 'products';
        static public $primaryKey = 'id';
        public $name;
        public $id;
        public $weight;
        public $size;
        public $price;
        public $typeid;

        public function __construct( $data)
        {
            $this->name = $data['name'];
            $this->weight = $data['weight'];
            $this->size = $data['size'];
            $this->price = $data['price'];
            $this->type = $data['type'];
            if (isset($data['id'])){
                $this->id = $data['id'];
            }

        }

        public function __toString()
        {
            return json_encode($this);
        }
    }