# catalog-api

test project 

>**docker** is very recommended to checkout this project

1. Clone or download;
2. Go to docker-enviroment directory;
3. At `.env` file edit parameter `{DOCKER_HOST_IP}` to your Docker host IP;
4. To build containers, execute: `docker-compose build`;
5. To run docker containers execute: `docker-compose up -d`;
6. In config.php change `basePath` to `http://{DOCKER_HOST_IP}/api/v1/products` 
7. Also change mysql server ip to `{DOCKER_HOST_IP}`
8. Open browser and go to `http://{DOCKER_HOST_IP}/api/v1/products`
