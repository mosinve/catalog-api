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

        public function doCurl($method, $data = null, $id='')
        {
            $ch = curl_init('http://192.168.99.10/api/v1/products/'.$id);
            $data_string = json_encode($data);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
            );

            return curl_exec($ch);
        }

        public function testIndex()
        {
            $result = json_decode($this->doCurl('GET'), true);

            // Select all products
            $this->assertEquals('200', $result['status']);
        }

        public function testGetProduct()
        {
            $result = json_decode($this->doCurl('GET', '',1), true);
            // select first product
            $this->assertEquals('200', $result['status']);


            $result = json_decode($this->doCurl('GET', '',100), true);

            // select non-existence product
            $this->assertEquals('404', $result['status']);
        }

        public function testInsertProduct()
        {
            $result = json_decode($this->doCurl('GET'), true);

            $before = $result['data']['length'];
            $data = ['name' => 'test', 'type' => 1, 'size'=> 10, 'weight' => '20', 'price' => 111];
            $result = $this->doCurl('POST', $data);
            $decoded_result = json_decode($result, true);

            $after = json_decode($this->doCurl('GET'), true)['data']['length'];
            //Insert new product
            $this->assertEquals('201', $decoded_result['status']);
            $this->assertNotEquals($after, $before, 'Number of records not changed');

            $data = ['name' => 'test', 'typeid' => 1, 'size'=> 10, 'weight' => '20', 'price' => 111];
            $result = $this->doCurl('POST', $data);
            $decoded_result = json_decode($result, true);

            //Insert new product with incorrect field
            $this->assertEquals('400', $decoded_result['status']);
        }

        public function testUpdateProduct()
        {

            $data = ['name' => 'test', 'type' => 1, 'size'=> 10, 'weight' => '20', 'price' => 111];
            $result = $this->doCurl('POST', $data);
            $decoded_result = json_decode($result, true);
            $this->assertEquals('201', $decoded_result['status'], 'Model insert error');

            $id = $decoded_result['data']['items']['id'];
            $data = ['name' => 'test test', 'type' => 2, 'size'=> 1000, 'weight' => '200', 'price' => 1111];
            $result = $this->doCurl('PUT', $data, $id);
            $decoded_result = json_decode($result, true);

            //update product
            $this->assertEquals('200', $decoded_result['status'], 'Model update error');

            $result = $this->doCurl('PUT', $data, $id);
            $decoded_result = json_decode($result, true);
            $this->assertEquals('', $decoded_result['status'], 'PUT idempotence failed');
        }
    }
