[![CircleCI](https://circleci.com/gh/INGV/hyp2000-ws.svg?style=svg)](https://circleci.com/gh/INGV/hyp2000-ws)

# hyp2000-ws

```
$ git clone https://github.com/ingv/hyp2000-ws
$ cd hyp2000-ws
$ git submodule update --init --recursive
```

## Configure
```
$ cp ./Docker/.env.example ./Docker/.env
```
Set:
- **HTTP_PORT** in `./Docker/.env` file.
- `./.env` file with same ports set in `./Docker/.env` file

## Start hyp2000-ws
First, build docker images:

```
$ cd Docker
$ COMPOSE_HTTP_TIMEOUT=200 docker-compose up -d nginx redis workspace docker-in-docker
```

then, build **hyp2000** docker image into *php-fpm* container:
```
$ cd Docker
$ docker-compose exec -T php-fpm sh -c "if docker image ls | grep -q hyp2000 ; then echo \" nothing to do\"; else cd hyp2000 && docker build --tag hyp2000:ewdevgit -f DockerfileEwDevGit .; fi"
```
**ATTENTION**: Remember to re-build **hyp2000** docker image into *php-fpm* container every time the docker container starts.