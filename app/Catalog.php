<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 25.10.2017
     * Time: 21:28
     */

    namespace CatalogAPI;

    use CatalogAPI\models\Product;

    /**
     * Class Catalog
     * @package CatalogAPI
     */
    class Catalog
    {
        /**
         * @var DB
         */
        private $connection;

        /**
         * Catalog constructor.
         *
         * @param DB $DB
         */
        public function __construct(DB $DB)
        {
            $this->connection = $DB;
        }

        /**
         * @param $id
         *
         * @return Product|boolean
         * @throws NotFoundException
         */
        public function getProduct($id)
        {
            if ( ! is_array($id)) {
                $id = [Product::$primaryKey => $id];
            }
            $result = $this->connection->table(Product::$table)->where($id)->select();

            if ($result) {
                return new Product($result[0]);
            }
            throw new NotFoundException('Product with given id not found', 404);
        }


        /**
         * @return array
         * @throws NotFoundException
         */
        public function getProducts(): array
        {
            $result = $this->connection->table(Product::$table)->select();
            foreach ($result as $product) {
                $products[] = new Product($product);
            }

            if (isset($products)){
                return $products;
            }
            throw new NotFoundException('Nothing found', 404);
        }


        /**
         * @param array $data
         *
         * @return Product
         * @throws NotFoundException
         */
        public function createProduct(array $data): Product
        {
            try {
                $product     = new Product($data);
                $product->id = $this->connection->table(Product::$table)->insert($data);

                return $product;
            }catch (\PDOException $exception){
                throw new NotFoundException($exception->getMessage(), '400');
            }

        }

        /**
         * @param $data
         * @param $id
         *
         * @return array|bool|int|string
         */
        public function editProduct($data, $id)
        {
            if ( ! is_array($id)) {
                $id = [Product::$primaryKey => $id];
            }
            try {
                return $this->connection->table(Product::$table)->where($id)->update($data);
            }catch (\PDOException $exception){
                throw new NotFoundException($exception->getMessage(), '400');
            }

        }

        /**
         * @param $id
         */
        public function deleteProduct($id)
        {
            if ( ! is_array($id)) {
                $id = [Product::$primaryKey => $id];
            }
           return $this->connection->table(Product::$table)->where($id)->delete();
        }
    }
