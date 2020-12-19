# GP - Hotel Management System

## Requirements

- Docker
- Docker Compose

## Configure

After cloning the project goto `project_dir/core/src` and run these commands

##### Copy env example

```bash
cp .env.example .env
```

Set your desired value in `.env` if you need

##### Copy docker compose file

```bash
cp docker-compose.yaml.example docker-compose.yaml
```

## Run project

To run this project please run the command bellow from `project_dir/core`

```bash
docker-compose --env-file=./src/.env up -d --build
```

## Install PHP Dependencies

After docker run successfully you have to install PHP dependencies by running these command

```bash
docker-compose exec app composer install
```

## Down

To stop all containers of docker please run this command

```bash
docker-compose --env-file=./src/.env down
```


Congratulations! You have successfully done configurations. We hope now this project will run properly from this URL `http://localhost:${APP_PORT}`