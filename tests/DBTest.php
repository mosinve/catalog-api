<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 25.10.2017
     * Time: 21:02
     */

    use CatalogAPI\DB;
    use PHPUnit\Framework\TestCase;
    use PHPUnit\DbUnit\TestCaseTrait;

    class DBTest extends TestCase
    {
        use TestCaseTrait;

        private $db;

        /**
         * DBTest constructor.
         */
        public function __construct()
        {
            $config   = require '../config.php';
            $this->db = new DB($config['database']);

            parent::__construct();
        }

        public function getConnection()
        {

            $database = 'test';
            $user     = 'root';
            $password = '';
            $host     = '192.168.99.10';
            $pdo      = new PDO("mysql:host={$host}:3306;dbname={$database}",
                $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->createDefaultDBConnection($pdo, $database);
        }

        public function testDeleteProduct()
        {
            $this->assertEquals(0, $this->getConnection()->getRowCount('products'), 'Start');

            $this->db->table('products')->insert([
                'name'   => 'Картон A',
                'typeid' => 1,
                'size'   => 20,
                'weight' => 40,
                'price'  => 100
            ]);


            $this->assertEquals(1, $this->getConnection()->getRowCount('products'), 'Insert failed');

            $this->db->table('products')->where('id',$this->db->getConnection()->lastInsertId())->delete();

            $this->assertEquals(0, $this->getConnection()->getRowCount('products'), 'Delete failed');
        }

        public function testSelectProduct()
        {
            $this->db->table('products')->insert([
                'name'   => 'Картон A',
                'typeid' => 1,
                'size'   => 20,
                'weight' => 40,
                'price'  => 100
            ]);

            $result = $this->db->table('products')->where('id', $this->db->getConnection()->lastInsertId())->select();

            $this->assertNotNull($result, 'Result empty');
        }

        protected function getDataSet()
        {
            return $this->createFlatXMLDataSet(__DIR__ . '/db-seed.xml');
        }

        public function testCreateProduct()
        {
            $this->assertEquals(0, $this->getConnection()->getRowCount('products'), 'Start');

            $this->db->table('products')->insert([
                'name'   => 'Картон A',
                'typeid' => 1,
                'size'   => 20,
                'weight' => 40,
                'price'  => 100
            ]);

            $this->assertEquals(1, $this->getConnection()->getRowCount('products'), 'Insert failed');
        }

        public function testUpdateProduct()
        {
            $this->db->table('products')->insert([
                'name'   => 'Картон A',
                'typeid' => 1,
                'size'   => 20,
                'weight' => 40,
                'price'  => 100
            ]);

            $this->assertEquals(1, $this->getConnection()->getRowCount('products'), 'Insert failed');

            $this->db->table('products')->update([
                'name'   => 'Картон A',
                'typeid' => 1,
                'size'   => 20,
                'weight' => 40,
                'price'  => 200
            ]);

            $queryTable = $this->getConnection()->createQueryTable('products', 'SELECT * FROM products');

            $queryTable->assertContainsRow([
                'name'   => 'Картон A',
                'typeid' => 1,
                'size'   => 20,
                'weight' => 40,
                'price'  => 200
            ]);
        }


    }
