<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 30.10.2017
     * Time: 1:32
     */

    namespace CatalogAPI;

    use CatalogAPI\Models\Product;

    /**
     * Class Controller
     * @package CatalogAPI
     */
    class ProductsController
    {
        /**
         * @var QueryBuilder
         */
        private $catalog;

        /**
         * Controller constructor.
         */
        public function __construct(QueryBuilder $catalog)
        {
            $this->catalog = $catalog;
        }

        /**
         * @param Request $request
         *
         * @return JSONResponse
         *
         * @throws NotFoundException
         */
        public function getProduct(Request $request):JSONResponse
        {
            return new JSONResponse(200, [], Product::find($request->get('id')));
        }

        /**
         * @return JSONResponse
         */
        public function getProducts():JSONResponse
        {
            return new JSONResponse(200, [], Product::all());
        }

        /**
         * @param Request $request
         *
         * @return JSONResponse
         */
        public function addProduct(Request $request)
        {
            $result = Product::create(json_decode($request->getBody()->getContents(), true));

            return new JSONResponse(201, ['Location' => $request->getUri().'/'.$result->id], $result);
        }

        /**
         * @param Request $request
         *
         * @return JSONResponse
         */
        public function editProduct(Request $request)
        {
            $product = Product::find($request->get('id'));

            $product->fill(json_decode($request->getBody()->getContents(),1));
            $result = $product->save();

            if ($result){
                return new JSONResponse(200, [], $product);
            }

            return new JSONResponse(204, [], '');
        }

        /**
         * @param $request Request
         *
         * @return JSONResponse
         */
        public function deleteProduct(Request $request)
        {
            $product = Product::find($request->get('id'));
            $product->delete();

            return new JSONResponse(204);
        }
    }
