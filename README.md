# hyp2000-ws

```
$ git clone https://github.com/ingv/hyp2000-ws
$ cd hyp2000-ws
$ git submodule update --init --recursive
```

Set HTTP_PORT in the `./Docker/.env` file.
Set ./.env file with same port set in `./Docker/.env` file, starting from .env.example

```
$ cd Docker
$ COMPOSE_HTTP_TIMEOUT=200 docker-compose up -d nginx redis workspace docker-in-docker
```
