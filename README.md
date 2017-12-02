# Test MVC/Chat

## Requirement

* Docker
* DockerCompose v3

## Installation
```
> docker-compose build
> docker-compose up -d
```
When MySQL is ready, you can start importing the data :
```
> docker exec -i chat_db mysql -ui@d_user -pi@d_pass i@d_test < dump_i@d_test.sql
```

## Run
```
> docker-compose up -d
```

## Stop
```
> docker-compose down
```
or
```
> docker-compose down --volume
```

*The last command remove also the data volume (MySQL).*

## Access
```
http://localhost:8000/
```

*If you change the server host you must also change the configuration otherwise the csrf simplified system will not work.*


## Account

The system automatically create an account if it does not exist when you try to sign in.
