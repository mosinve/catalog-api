# catalog-api

test project 

>**docker** is very recommended to checkout this project

1. Clone or download;
2. Install project dependencies by: `composer install`
3. Go to docker-enviroment directory;
4. At `.env` file edit parameter `{DOCKER_HOST_IP}` to your Docker host IP;
5. To build containers, execute: `docker-compose build`;
6. To run docker containers execute: `docker-compose up -d`;
7. In config.php change `basePath` to `http://{DOCKER_HOST_IP}/api/v1/products` 
8. Also change mysql server ip to `{DOCKER_HOST_IP}`
9. Open browser and go to `http://{DOCKER_HOST_IP}/api/v1/products`
