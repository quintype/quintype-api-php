# quintype-api-php
A php composer plugin for communication with sketches api

##How to use
To use this package in your project, follow the steps below.

###  In composer.json, require this package.
```sh
"require": {
        ...
        ...
        "quintype/api":"1.0.0",
    },
```

###  Install or update the composer packages.
```sh
$ composer install
or
$ composer update
```

###  In the Laravel config/app.php file, give an alias to the API class for convenience.
```sh
'aliases' => [
        ...
        ...
        'Api' => Quintype\Api\Api::class
    ],
```

### Include the API class in necessary controllers.
```sh
use Api;
```

### Create an instance(Pass api-host to it).
```sh
$this->client = new Api(config("quintype.api-host"));
```

### Use the above created instance to call required functions. For example,
```sh
$this->client->config();
$this->client->storyBySlug(["slug"=> $slug]);
```
