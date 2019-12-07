# segrax/opa-php-examples

Examples of using OPA with the segrax/open-policy-agent library.

## Includes

* Plain PHP usage of client
* Slim 4 API Authorization

## Usage

### Plain PHP
This example can be used for making queries to an instance of OPA.
```bash
    cd plain
    make composer-install
    make test
```

### Slim 4 API Authorization
This example demonstrates how to invoke a policy for making a decision on access to a route.
```bash
    cd Slim4-Api
	make start
```

The containers will now build and be started, by default the API will be listening on port 80.
