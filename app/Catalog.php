<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 25.10.2017
     * Time: 21:28
     */

    namespace CatalogAPI;

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
         * @return Product
         * @throws NotFoundException
         */
        public function getProduct($id): Product
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
         */
        public function getProducts(): array
        {
            $result = $this->connection->table(Product::$table)->select();
            foreach ($result as $product) {
                $products[] = new Product($product);
            }

            return $products ?? [];
        }

        /**
         * @param $data
         *
         * @return Product
         */
        public function createProduct($data): Product
        {
            $product     = new Product($data);
            $product->id = $this->connection->table(Product::$table)->insert($data);

            return $product;
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

            return $this->connection->table(Product::$table)->where($id)->update($data);
        }

        /**
         * @param $id
         */
        public function deleteProduct($id)
        {
            if ( ! is_array($id)) {
                $id = [Product::$primaryKey => $id];
            }
            $this->connection->table(Product::$table)->where($id)->delete();
        }
    }