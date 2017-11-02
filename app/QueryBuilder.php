<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 25.10.2017
     * Time: 21:28
     */

    namespace CatalogAPI;

    use CatalogAPI\Models\Model;

    /**
     * Class Catalog
     * @package CatalogAPI
     */
    class QueryBuilder
    {
        /**
         * @var DB
         */
        private $connection;


        /**
         * @var Model
         */
        private $model;

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
         * @return array
         * @throws NotFoundException
         */
        public function find($id)
        {
            if ( !is_array($id)) {
                $id = [$this->model::getPrimaryKey() => $id];
            }
            $result = $this->connection->table($this->model::getTable())->where($id)->select();

            if ($result) {
                return $result[0];
            }
            throw new NotFoundException('Resource with given id not found', 404);
        }


        /**
         * @return array
         * @throws NotFoundException
         */
        public function all(): array
        {
            $result = $this->connection->table($this->model::getTable())->select();

            if (isset($result)){
                return $result;
            }
            throw new NotFoundException('Nothing found', 404);
        }


        /**
         * @param array $data
         *
         * @return array|bool|int|string
         * @throws NotFoundException
         */
        public function create(array $data)
        {
            try {
                return $this->connection->table($this->model::getTable())->insert($data);
            }catch (\PDOException $exception){
                throw new NotFoundException($exception->getMessage(), '400');
            }

        }

        /**
         * @param $data
         * @param $id
         *
         * @return array|bool|int|string
         * @throws NotFoundException
         */
        public function update($data, $id)
        {
            if ( ! is_array($id)) {
                $id = [$this->model::getPrimaryKey() => $id];
            }
            try {
                return $this->connection->table($this->model::getTable())->where($id)->update($data);
            }catch (\PDOException $exception){
                throw new NotFoundException($exception->getMessage(), '400');
            }

        }

        /**
         * @param $id
         *
         * @return array|bool|int|string
         */
        public function delete($id)
        {
            if ( ! is_array($id)) {
                $id = [$this->model::getPrimaryKey() => $id];
            }
           return $this->connection->table($this->model::getTable())->where($id)->delete();
        }

        /**
         * @param $model string
         *
         * @return self
         */
        public function setModel($model){
            $this->model = new $model();
            return $this;
        }
    }
