# segrax/opa-php-examples

Examples of using Open Policy Agent (OPA) with the segrax/open-policy-agent library with PHP 7.3.

These examples are pre-configured with insecure JWTs which should never be reused.
The API exmaple is setup for xdebug including working [@code](https://code.visualstudio.com/) configuration.

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Build Status](https://api.travis-ci.com/segrax/opa-php-examples.svg)](https://travis-ci.com/segrax/openpolicyagent)


## Includes
* Plain PHP usage of client
* Slim 4 Skeleton API with policy authorization


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
    cd slim4-api
    make start
```
The containers will now build and be started, by default the API will be listening on port 80.
This example serves up policies in a bundle to the running OPA using the Distributor PSR-15 middleware that is included in segrax/open-policy-agent.

A [Postman](https://www.getpostman.com/) collection is included inside slim4-api, import it and test both endpoints.

This is the policy for the included example endpoint,
```
package slim.api

default allow = false

# OPA Bundle
allow {
    input.path = ["opa", "bundles", "{name}"]
    input.token.sub == "opa"
}

# Allow use to access their account
allow {
    input.method == "GET"
    input.path = ["welcome", userid ]
    userid == input.token.sub
}

# Allow authed user to create a location
allow {
    input.path = ["public"]
    input.method == "GET"
}
```