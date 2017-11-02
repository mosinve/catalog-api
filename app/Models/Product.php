<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 27.10.2017
     * Time: 22:14
     */
    namespace CatalogAPI\Models;
    /**
     * Class Product
     * @package CatalogAPI
     */
    class Product extends Model
    {
        /**
         * @var string
         */
        protected static $table= 'products';

        protected $fillable = ['id', 'size', 'type_id'];

        protected $visible = ['id', 'size', 'weight', 'name', 'price'];

        /**
         * @return string
         */

        public function calcSize(){
            switch ($this->relation()->type){
                case 'square':
                    return ($this->size/100)**2;
                case '6/9':
                    return (($this->size/100)**2)*9/6;
            }
        }

        public function getPriceField()
        {
            return round((float)$this->getWeightField() * ((float)$this->relation()->unit_price),2);
        }

        public function getWeightField()
        {
            return round((float)($this->calcSize()) * ((float)$this->relation()->unit_weight/1000),2);
        }

        public function getTypeField()
        {
           return $this->relation()->type;
        }

        public function getNameField()
        {
            return $this->relation()->name;
        }

        public function relation(){
            return ProductType::find($this->type_id);
        }

        public function validateSize($data)
        {
            $min = ProductType::find($data['type_id'])->size_min;
            $max = ProductType::find($data['type_id'])->size_max;
            return ($data['size'] >= $min && $data['size'] <= $max);
        }
    }