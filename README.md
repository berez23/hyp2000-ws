# hyp2000-ws

```
$ git clone https://github.com/ingv/hyp2000-ws
$ cd hyp2000-ws
$ git submodule update --init --recursive
```

Set HTTP_PORT in the `./Docker/.env` file.
Set ./.env file with same port set in `./Docker/.env` file, starting from .env.example

Run all:
```
$ cd Docker
$ COMPOSE_HTTP_TIMEOUT=200 docker-compose up -d nginx redis workspace docker-in-docker
```

Build hyp2000 image into php-fpm container:
```
$ cd Docker
$ docker-compose exec -T php-fpm sh -c "if docker image ls | grep -q hyp2000 ; then echo \" nothing to do\"; else cd hyp2000 && docker build --tag hyp2000:ewdevgit -f DockerfileEwDevGit .; fi"
```
