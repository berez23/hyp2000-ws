[![CircleCI](https://circleci.com/gh/INGV/hyp2000-ws.svg?style=svg)](https://circleci.com/gh/INGV/hyp2000-ws)

# hyp2000-ws

```
$ git clone https://github.com/ingv/hyp2000-ws
$ cd hyp2000-ws
$ git submodule update --init --recursive
```

## Configure
Copy docker environment file:
```
$ cp ./Docker/env-example ./Docker/.env
```

Copy laravel environment file:
```
$ cp ./.env.example ./.env
```

Set `NGINX_HOST_HTTP_PORT` in `./Docker/.env` file.

### !!! On Linux machine and no 'root' user !!!
To run container as *linux-user* (intead of `root`), set `WORKSPACE_PUID` and `WORKSPACE_PGID` in `./Docker/.env` file with:
- `WORKSPACE_PUID` should be equal to the output of `id -u` command
- `WORKSPACE_PGID` should be equal to the output of `id -g` command

## Start hyp2000-ws
First, build docker images:

```
$ cd Docker
$ COMPOSE_HTTP_TIMEOUT=200 docker-compose up -d nginx redis workspace docker-in-docker
$ cd ..
```

## Configure Laravel
### !!! On Linux machine and no 'root' user !!!
```
$ cd Docker
$ docker-compose exec -T --user=laradock workspace composer install
$ docker-compose exec -T --user=laradock workspace php artisan key:generate
$ cd ..
```

### !!! Others !!!
```
$ cd Docker
$ docker-compose exec -T workspace composer install
$ docker-compose exec -T workspace php artisan key:generate
$ cd ..
```

## Install hyp2000 
build **hyp2000** docker image into *php-fpm* container:
```
$ cd Docker
$ docker-compose exec -T php-fpm sh -c "if docker image ls | grep -q hyp2000 ; then echo \" nothing to do\"; else cd hyp2000 && docker build --tag hyp2000:ewdevgit -f DockerfileEwDevGit .; fi"
$ cd ..
```

### Keep on mind!
The **hyp2000** docker image is built in the *php-fpm* container; if you destroy or rebuild *php-fpm* container, remember to rebuild install hyp2000 image.

## How to use it
When all containers are started, connect to: 
- http://<your_host>:<your_port>/

If all works, you should see a web page with OpenAPI3 specification to interact with WS.

## Test
```
$ cd Docker
$ docker-compose exec -T --user=laradock workspace bash -c "vendor/bin/phpunit -v"
```

## Thanks to
This project uses the [Laradock](https://github.com/laradock/laradock) idea to start docker containers

## Contribute
Please, feel free to contribute.

## Author
(c) 2019 Valentino Lauciani valentino.lauciani[at]ingv.it \
(c) 2019 Matteo Quintiliani matteo.quintiliani[at]ingv.it

Istituto Nazionale di Geofisica e Vulcanologia, Italia
