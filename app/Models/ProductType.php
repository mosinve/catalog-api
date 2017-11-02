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
    class ProductType extends Model
    {
        /**
         * @var string
         */
        static public $table = 'product_types';
        /**
         * @var string
         */
        static public $primaryKey = 'code';
        /**
         * @var
         */
        protected $fillable = ['id', 'code', 'name', 'type', 'size_mix', 'size_max', 'unit_price', 'unit_weight'];
    }