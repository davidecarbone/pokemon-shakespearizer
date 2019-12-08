# Pokemon Shakespearizer

A translator from English to Shakespearean for Pokemon descriptions!

## Getting Started

### Installing

You can use Docker to build a container with all requirements:
```
docker build --file .docker/Dockerfile -t pokemon-docker .
docker run --rm -p 8080:80 pokemon-docker
```
### Prerequisites (without Docker)

* PHP 7.1 or above
* Apache2
* Composer

The following steps should be enough to run the app in your local environment:
```
$ composer install
$ php -S localhost:8080 -r /path/to/your/local/project/location
```

### Usage
A single endpoint is available:
```
GET /pokemon/{pokemonName} 
```

## Running the tests

To run unit and end2end tests simply run from the project root:
```
$ vendor/bin/phpunit
```

## Built With
* PHP 7
* [Slim v3](http://www.slimframework.com/) - For the routes layer and Dependency Injection
* [PokéAPI](https://pokeapi.co/docs/v2.html/) - For Pokemon descriptions
* [Shakespeare translator API](https://funtranslations.com/api/shakespeare) - For the English to Shakespearean translations

## Authors

* **Davide Carbone** - *Initial work* - [Davide Carbone](https://github.com/davidecarbone)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
