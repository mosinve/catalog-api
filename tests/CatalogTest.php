<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 26.10.2017
     * Time: 23:36
     */

    namespace CatalogAPI;

    use PHPUnit\Framework\TestCase;

    class CatalogTest extends TestCase
    {
        private $db;
        private $catalog;
        public function __construct($name = null, array $data = [], $dataName = '')
        {
            parent::__construct($name, $data, $dataName);

            $config = require '../config.php';
            $this->db     = new DB($config['database']);
            $this->catalog = new QueryBuilder($this->db);
        }


        public function testCreateProduct()
        {

            $result = $this->catalog->createProduct([
                'name'   => 'Картон A',
                'typeid' => 1,
                'size'   => 20,
                'weight' => 40,
                'price'  => 100
            ]);

           $this->assertInstanceOf(Product::class, $result);
        }

        public function testGetProducts()
        {
            $products = $this->catalog->getProducts();

            $this->assertNotNull($products);
        }

        public function testSelectAndUpdateProduct()
        {
            $this->catalog->createProduct([
                'name'   => 'Картон A',
                'typeid' => 1,
                'size'   => 20,
                'weight' => 40,
                'price'  => 100
            ]);

            $product = $this->catalog->find($this->db->getConnection()->lastInsertId());

            $this->assertNotEmpty($product);

            $this->catalog->editProduct(['name'=>'Туалетная бумага'], $product->id);
            $editedProduct = $this->catalog->find($product->id);
            $this->assertNotEquals($product, $editedProduct);
        }

        public function testSelectAndDelete()
        {
            $products = $this->catalog->getProducts();

            foreach ($products as $product){
                $this->catalog->delete($product->id);
            }

            $products = $this->catalog->getProducts();

            $this->assertEmpty($products);
        }


    }
