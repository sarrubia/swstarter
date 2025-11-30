# SWStarter

## How to run it?

### Having Docker installed

Building the Docker image:
```shell
docker build -t lawnstarter/swstarter:latest .
```

Running a container based on built image:
```shell
docker run -d -p 8000:8000 --name swstarter-web lawnstarter/swstarter:latest
```
Or with Docker Compose

```shell
docker-compose up
``` 

## Stats endpoint

Once that the service is up and running the stats endpoint can be called following the next curl command:
```shell
curl --location 'http://localhost:8000/api/stats'
```

## To have into account

 - **Database:** is the default Laravel SQLite installed at starter-kit initialization.

 - **Queue:** is the default configuration, that means jobs are scheduled to run in DB. 
   - A Redis instance could be a better option for production environments 

 - **Cron** the Laravel scheduler has been added as cronjob. See the folder `.docker/cron`


