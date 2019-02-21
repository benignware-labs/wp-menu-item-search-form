# wp-menu-item-search-form

Add a search-form to menus


## Development

Download [Docker CE](https://www.docker.com/get-docker) for your OS.

### Development Server

Point terminal to your project root and start up the container.

```cli
docker-compose up -d
```

Open your browser at [http://localhost:3010](http://localhost:3010).

Go through Wordpress installation and activate the demo theme.

### Useful commands

#### Startup services

```cli
docker-compose up -d
```
You may omit the `-d`-flag for verbose output.

#### Shutdown services

In order to shutdown services, issue the following command

```cli
docker-compose down
```

#### List containers

```cli
docker-compose ps
```

#### Remove containers

```cli
docker-compose rm
```

#### Open bash

Open bash at wordpress directory

```cli
docker-compose exec wordpress bash
```

#### Update composer dependencies

```cli
docker-compose run composer update
```
