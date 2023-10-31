<p align="center">
<img src="art/routail.png"/>
  
A spidey PHP router like Tarantulas
</p>

## Features
* Support `get`, `post`, `put`, `patch`, `delete` and `any` method
* Support optional parameter
* Middlewares
* Route group
* Url generator
##  Requirements
PHP >= 8.2


## Getting Started


#### Installation
via Composer
```
composer require alirezasalehizadeh/routail
```

#### Route definition
The example below, is a quick view of how you can define an route and run it
```php
use AlirezaSalehizadeh\Routail\Router;

$router = new Router();

$router->get(string $pattern, string|array|Closure $action)
  ->name(string $name)
  ->prefix(string $prefix)
  ->middleware(array $middlewares);

$router->run();

```

#### Route group definition
```php
use AlirezaSalehizadeh\Routail\Router;

$router = new Router();

$router->group(Closure $action, array $middlewares, string $prefix);

$router->run();

```
## Usage

#### Middlewares
To use middlewares, you need to create a class that extends from the `AlirezaSalehizadeh\Routail\Middleware` class and implement the `handle` method that returns a boolean
```php
use AlirezaSalehizadeh\Routail\Request;
use AlirezaSalehizadeh\Routail\Middleware\Middleware;

class FooMiddleware extends Middleware
{
    public function handle(Request $request): bool
    {
        return true;
    }
}

```

#### Url generator
By `url` method, you can create url from route name easily
```php
use AlirezaSalehizadeh\Routail\Router;

$router = new Router();

$router->get('/users/{id}', 'UserController@show')->name('user_show');

$router->url('user_show', ['id' => '1']);

// output: /users/1
```

#### Route parameter types
Route parameters can have a type, which can be optional
```
any
id
int
string
uuid
slug
bool
date
int?  // optional
any?  // optional
```
## Examples
```php
use AlirezaSalehizadeh\Routail\Router;

$router = new Router();

$router->get('/users', 'UserController@index');

$router->any('/users', [UserController::class, 'index']);

// route pattern with parameter
$router->get('/users/{id}', 'UserController@show');

// route pattern with parameter and type
$router->get('/users/{id:int}', function($id){
  return "User id is $id";
});

// route pattern with optional parameter
$router->get('/users/{id:int?}', function($id = 1){
  return "User id is $id";
});

// set name for route
$router->get('/users/{id}', 'UserController@index')->name('user_index');

// set prefix for route
$router->get('/users/{id}', 'UserController@index')->prefix('/api/v1');

// set middleware for route
$router->get('/users/{id}', 'UserController@index')->middleware([FooMiddleware::class, BarMiddleware::class]);

// route group
$router->group(function($router){
  $router->get('/users', 'UserController@index');
  $router->get('/users/{id}', 'UserController@show');
}, [FooMiddleware::class, BarMiddleware::class], '/api/v1');


```

## Contributing
Please read the [CONTRIBUTING.md](CONTRIBUTING.md) file.


## License

[MIT](LICENSE.md).
