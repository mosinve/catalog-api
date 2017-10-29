<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 25.10.2017
     * Time: 19:20
     */

    namespace CatalogAPI;

    use PDO, PDOException;

    class DB
    {

        /**
         * @var \PDO
         */
        private $connection;
        private $fields = ['*'];
        private $table;
        private $wheres = [];
        private $query;
        private $cmd;

        const SELECT_CMD = 'SELECT';
        const INSERT_CMD = 'INSERT';
        const UPDATE_CMD = 'UPDATE';
        const DELETE_CMD = 'DELETE';

        public function __construct($config)
        {

            try {
                $this->connection = new PDO("{$config['driver']}:host={$config['host']}:{$config['port']};dbname={$config['database']}",
                    $config['user'], $config['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Application error while connecting to database');
            }

        }

        public function table($table)
        {
            $this->table = $table;

            return $this;
        }

        public function fields(...$fields)
        {
            $this->fields = $fields;

            return $this;
        }

        public function where($field, $value = null, $operator = '=', $boolean = 'AND'): self
        {
            if (is_array($field)) {
                foreach ($field as $key => $item) {
                    if (is_string($key)) {
                        $this->wheres[] = array_merge(['field' => $key, 'value' => $item],
                            compact('operator', 'boolean'));
                    }
                }
            } else {
                $this->wheres[] = compact('field', 'value', 'operator', 'boolean');
            }

            return $this;
        }

        public function orWhere($field, $value = null, $operator = '='): self
        {
            $this->where($field, $value, $operator, 'OR');

            return $this;
        }

        public function buildWhere(array $wheres): string
        {
            $txtWhere   = [];
            $conditions = [];
            foreach ($wheres as ['field' => $field, 'value' => $value, 'operator' => $operator, 'boolean' => $boolean]) {
                $txtWhere[$boolean][] = $field . $operator . (is_string($value) ? $this->connection->quote($value) : $value);
            }

            foreach ($txtWhere as $boolean => $item) {
                $conditions[$boolean] = implode(" $boolean ", $item);
            }
            if ($conditions) {
                return ' WHERE ' . implode(' OR ', $conditions);
            }

            return '';
        }

        /**
         * @param string $query
         * @param array $params
         *
         * @return array|bool|int|string
         */
        private function exec(string $query, array $params = [])
        {
            $this->query = $this->connection->prepare($query);

            foreach ($params as $key => &$value) {
                $this->query->bindParam(":$key", $value);
            }
            unset($value);
            if ($this->query->execute()) {
                switch ($this->cmd) {
                    case self::SELECT_CMD:
                        return $this->query->fetchAll();
                        break;
                    case self::DELETE_CMD:
                        return $this->query->rowCount();
                        break;
                    case self::UPDATE_CMD:
                        return $this->query->rowCount();
                        break;
                    case self::INSERT_CMD:
                        return $this->connection->lastInsertId();
                        break;
                }
            }

            return false;
        }

        public function select()
        {
            $this->cmd = self::SELECT_CMD;
            $from      = ' FROM ' . $this->table;
            $fields    = implode(', ', $this->fields);
            $where     = $this->buildWhere($this->wheres);
            return $this->exec($this->cmd . ' ' . $fields . $from . $where,
                array_combine(array_column($this->wheres, 'field'), array_column($this->wheres, 'value')));

        }

        public function insert(array $data)
        {
            $this->cmd = self::INSERT_CMD;
            $into      = ' INTO ' . $this->table;
            $fields    = ' (' . implode(', ', array_keys($data)) . ')';
            $values    = ' VALUES (' . implode(', ', array_map(function ($key) {
                    return ":$key";
                }, array_keys($data))) . ')';

            return $this->exec($this->cmd . $into . $fields . $values, $data);
        }

        public function update(array $data)
        {
            $this->cmd = self::UPDATE_CMD;
            $set       = ' SET ' . implode(', ', array_map(function ($key) {
                    return "$key=:$key";
                }, array_keys($data)));
            $where     = $this->buildWhere($this->wheres);

            return $this->exec($this->cmd . ' ' . $this->table . $set . $where, $data);
        }

        public function delete()
        {
            $this->cmd = self::DELETE_CMD;
            $from      = ' FROM ' . $this->table;
            $where     = $this->buildWhere($this->wheres);

            $this->exec($this->cmd . $from . $where);
        }

        public function getConnection(){
            return $this->connection;
        }
    }
