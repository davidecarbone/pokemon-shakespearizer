# Pokemon Shakespearizer

A translator from English to Shakespearean for Pokemon descriptions!

## Getting Started

### Installing

You can use Docker-compose to build a container with all requirements:
```
docker-compose up -d
```
**OR**  

With raw Docker:
```
1) docker build --file .docker/Dockerfile -t pokemon-docker .
2) docker run --rm -p 8080:80 pokemon-docker
```

### Usage
A single endpoint is available:
```
GET /pokemon/{pokemonName} 
```

## Running the tests

To run unit tests simply run from the project root:
```
$ vendor/bin/phpunit
```

End2end tests are not run by default. To run them:
```
$ vendor/bin/phpunit --testsuite end2end
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
