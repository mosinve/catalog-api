<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 27.10.2017
     * Time: 21:21
     */

    namespace CatalogAPI;

    use PHPUnit\Framework\TestCase;

    class APITest extends TestCase
    {

        protected function setUp()
        {

        }

        public function testIndex()
        {
            $ch = curl_init('http://127.0.0.1:8000/api/v1/product');
            $data = ['name'   => 'Картон G',
                'typeid' => 1,
                'size'   => 20,
                'weight' => 40,
                'price'  => 200];
            $data_string = json_encode($data);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
            );

            print $result = curl_exec($ch);
        }
    }
