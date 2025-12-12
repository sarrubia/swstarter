# SWStarter

## How to run it?

### Having Docker installed

Run it with Docker Compose

```shell
docker-compose up --build
``` 

## API Endpoints

### Films endpoints

Fetch Star Wars movies by title
```shell
curl --location 'http://localhost/api/films?title=hope'
```

Fetch Star Wars movie by id:
```shell
curl --location 'http://localhost/api/films/1'
```

### People endpoints

Fetch Star Wars people by name
```shell
curl --location 'http://localhost/api/people?name=yoda'
```

Fetch Star Wars people by id:
```shell
curl --location 'http://localhost/api/people/22'
```


### Stats endpoint

Once that the service is up and running the stats endpoint can be called following the next curl command:
```shell
curl --location 'http://localhost/api/stats'
```

## To have into account

 - **Database:** is a MySQL database (see docker-compose.yaml)

 - **Queue:** is the default redis configuration, that means jobs are scheduled to run in Redis DB 0.
 
 - **Cron** the Laravel scheduler has been added as cronjob. See the folder `.docker/cron`
 - **Cache:** due to SWAPI has Swapi now has rate slowing on top of the rate limiting and is currently set to slow by 100ms starting after the 5th API request within a 15 minute window. 
A Redis cache has been added to get a better user experience. 


