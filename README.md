# Test MVC/Chat

## Requirement

* Docker
* MySQL (for cli)

## Installation
```
> docker-compose build
> docker-compose up -d
> mysql -h 127.0.0.1 -u i@d_user -pi@d_pass i@d_test < dump_i@d_test.sql
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
