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

## Start hyp2000-ws
First, build docker images:

```
$ cd Docker
$ COMPOSE_HTTP_TIMEOUT=200 docker-compose up -d nginx redis workspace docker-in-docker
$ cd ..
```

## Configure Laravel
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
**ATTENTION**: Remember to re-build **hyp2000** docker image into *php-fpm* container every time the docker container starts.
