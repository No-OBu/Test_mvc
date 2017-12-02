# Test MVC/Chat

## Requirement

* Docker

## Installation
```
> docker-compose build
> docker-compose up -d
```
When MySQL is ready, you can start importing the data :
```
> docker exec -i idchat_db_1 mysql -ui@d_user -pi@d_pass i@d_test < dump_i@d_test.sql
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
