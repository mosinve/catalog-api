<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 30.10.2017
     * Time: 1:32
     */

    namespace CatalogAPI;

    use Psr\Http\Message\ServerRequestInterface;

    /**
     * Class Controller
     * @package CatalogAPI
     */
    class Controller
    {
        /**
         * @var Catalog
         */
        private $catalog;

        /**
         * Controller constructor.
         */
        public function __construct(Catalog $catalog)
        {
            $this->catalog = $catalog;
        }

        /**
         * @param Request|ServerRequestInterface $request
         *
         * @return JSONResponse
         *
         * @throws NotFoundException
         */
        public function getProduct(ServerRequestInterface $request):JSONResponse
        {
            $result = $this->catalog->getProduct($request->get('id'));
            return new JSONResponse(200, [], $result);
        }

        /**
         * @return JSONResponse
         */
        public function getProducts()
        {
            $result = $this->catalog->getProducts();
            return new JSONResponse(200, [], $result);
        }

        /**
         * @param Request $request
         *
         * @return JSONResponse
         */
        public function addProduct(Request $request)
        {
            $result = $this->catalog->createProduct(json_decode($request->getBody()->getContents(), true));

            return new JSONResponse(201, ['Location' => $request->getUri().$result->id], $result);
        }

        /**
         * @param Request $request
         *
         * @return JSONResponse
         */
        public function editProduct(Request $request)
        {
            $result = $this->catalog->editProduct(json_decode($request->getBody()->getContents(), true),
                $request->get('id'));

            if ($result){
                return new JSONResponse(200, [], $result);
            }

            return new JSONResponse(204, [], '');
        }

        /**
         * @param ServerRequestInterface $request
         *
         * @return JSONResponse
         */
        public function deleteProduct(ServerRequestInterface $request)
        {
                $prod = $this->catalog->getProduct($request->get('id'));

                if ($prod) {
                    $this->catalog->deleteProduct($request->get('id'));
                }

                return new JSONResponse();
        }
    }