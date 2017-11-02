<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 02.11.2017
     * Time: 0:33
     */

    namespace CatalogAPI\Models;


    use CatalogAPI\DB;
    use CatalogAPI\QueryBuilder;
    use CatalogAPI\ValidationException;

    abstract class Model implements \JsonSerializable
    {

        /**
         * @var string
         */
        static protected $table;
        /**
         * @var string
         */
        static protected $primaryKey = 'id';

        protected $attributes = [];
        protected $fillable = [];
        protected $visible = [];
        protected $errors = [];

        /**
         * @var DB
         */
        protected static $connection;

        public function __set($name, $value)
        {
            $this->attributes[$name] = $value;
        }

        public function __get($name)
        {

            if (isset($this->attributes[$name])){
                return  $this->attributes[$name];
            }
        }

        public function __construct(array $data = [])
        {
            $this->fill($data);
        }

        protected function processData(array $data):array
        {
            return array_filter($data, function ($key){
                return in_array($key, $this->getFillable(), true);
            }, ARRAY_FILTER_USE_KEY);
        }

        public function fill(array $data)
        {
            $data = $this->processData($data);
            $this->validate($data);
            foreach ($data as $key=>$value){
                $this->$key = $value;
            }

            return $this;
        }

        /**
         * @return array
         */
        protected function getAttributes(): array
        {
            return $this->attributes;
        }

        /**
         * @return array
         */
        protected function getFillable(): array
        {
            return $this->fillable;
        }

        /**
         * @param array $attributes
         */
        protected function setAttributes(array $attributes)
        {
            $this->attributes = $attributes;
        }

        /**
         * @param array $fillable
         */
        protected function setFillable(array $fillable)
        {
            $this->fillable = $fillable;
        }

        /**
         * @return string
         */
        static public function getTable(): string
        {
            return static::$table;
        }

        /**
         * @return string
         */
        static public function getPrimaryKey(): string
        {
            return static::$primaryKey;
        }

        /**
         * @param mixed $builder
         */
        public static function setConnection($connection)
        {
            static::$connection = $connection;
        }

        public static function find($where)
        {
            $model = new static();
            $data = $model->getBuilder()->setModel(static::class)->find($where);
            return $model->fill($data);
        }

        public static function all()
        {
            $result =  array_map(function($item){
                return new static($item);
            },(new static())->getBuilder()->setModel(static::class)->all());

            return $result;
        }

        public static function create(array $data)
        {
            $item = new static();
            $item->fill($data);
            $item->id = (new static())->getBuilder()->setModel(static::class)->create($data);

            return $item;
        }

        public function save()
        {
            return (new static())->getBuilder()->setModel(static::class)
                            ->update(array_diff_key($this->attributes, array_flip([static::getPrimaryKey()])), $this->id);
        }

        public function delete()
        {
            $this->getBuilder()->setModel(static::class)->delete($this->id);
        }

        public function __toString()
        {
            return json_encode($this->toArray(), JSON_NUMERIC_CHECK);
        }

        public function toArray()
        {
            return $this->attributesToArray();
        }

        public function jsonSerialize() {
            return $this->toArray();
        }

        protected function getFieldMethods()
        {
            preg_match_all('/(?<=^|;)get([^;]+?)Field(;|$)/', implode(';', get_class_methods(static::class)), $matches);

            return $matches[1];
        }

        protected function getValidateMethods()
        {
            preg_match_all('/(?<=^|;)validate([^;]+?)(;|$)/', implode(';', get_class_methods(static::class)), $matches);

            return $matches[1];
        }

        protected function attributesToArray()
        {
            $fieldMethods = $this->getFieldMethods();
            foreach ($fieldMethods as $key) {
                if(!array_key_exists(lcfirst($key), $this->attributes)){
                    $this->attributes[lcfirst($key)] = $this->{'get'.ucfirst($key).'Field'}();
                }
            }
            return $this->attributes;
        }

        protected function getBuilder()
        {
            return new QueryBuilder(static::$connection);
        }

        protected function validate($data)
        {
            $validateMethods = $this->getValidateMethods();

            foreach ($data as $key=>$value){
                if (array_key_exists(ucfirst($key), array_flip($validateMethods))){
                    if ($this->{'validate'.$key}($data) === false){
                        $this->errors[] = $key;
                    }
                }
            }

            if (!empty($this->errors)){
                throw new ValidationException('Validation error. Failing fields:'.json_encode($this->errors));
            }
        }
    }