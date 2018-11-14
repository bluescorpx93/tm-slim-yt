### Slim PHP Tryout


##### To Test This

- Update DB Config Info
```sh
$ cd tm-slimphp-api-yt

# Update src/config/mysqldb.php

# Line 4
private $db_engine = <YOUR_DB_ENGINE>;

# Line 5
private $db_host = <YOUR_DB_HOST>;

#Line 6
private $db_user = <YOUR_DB_USERNAME>;

#Line 7
private $db_pass = <YOUR_DB_NAME>;

#Line 8
private $db_name = <YOUR_DB_NAME>;
```

- Install Package Dependencies & Run App
```sh
$ cd tm-slimphp-api-yt
$ composer install
$ php -S localhost:5000
```
