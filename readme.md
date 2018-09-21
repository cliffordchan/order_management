# Logistic Order Management System

## Introduction

This project provides a reliable backend system to clients. Three endpoints have been implemented to list/place/take orders.

## Features

1. A **clean**, **simple** working solution.
2. A `start.sh` bash script at the root of the project, which should setup all relevant applications. It works on Ubuntu.
3. Unit/integration tests.
4. Production ready.

## Problem Statement

1. A RESTful HTTP API listening to port `8080`
2. The API has 3 endpoints with path, method, request and response body as specified
    - One endpoint to create an order (see sample)
        - To create an order, the API client must provide an origin and a destination (see sample)
        - The API responds an object containing the distance and the order ID (see sample)

    - One endpoint to take an order (see sample)
        - An order must not be takable multiple time.
        - An error response should be sent if a client tries to take an order already taken.

    - One endpoint to list orders (see sample)

3. Google maps API to get the distance for the order: https://cloud.google.com/maps-platform/routes/
4. MySQL is used. The DB installation &amp; initialisation is done in `start.sh`.


## Api interface example

#### Place order

  - Method: `POST`
  - URL path: `/order`
  - Request body:

    ```
    {
        "origin": ["START_LATITUDE", "START_LONGITUDE" ],
        "destination": ["END_LATITUDE", "END_LONGITUDE"]
    }
    ```

  - Response:

    Header: `HTTP 200`
    Body:
      ```
      {
          "id": <order_id>,
          "distance": <total_distance>,
          "status": "UNASSIGN"
      }
      ```
    or

    Header: `HTTP 500`
    Body:
      ```json
      {
          "error": "ERROR_DESCRIPTION"
      }
      ```

#### Take order

  - Method: `PUT`
  - URL path: `/order/:id`
  - Request body:
    ```
    {
        "status":"taken"
    }
    ```
  - Response:
    Header: `HTTP 200`
    Body:
      ```
      {
          "status": "SUCCESS"
      }
      ```
    or

    Header: `HTTP 409`
    Body:
      ```
      {
          "error": "ORDER_ALREADY_BEEN_TAKEN"
      }
      ```

#### Order list

  - Method: `GET`
  - Url path: `/orders?page=:page&limit=:limit`
  - Response:

    ```
    [
        {
            "id": <order_id>,
            "distance": <total_distance>,
            "status": <ORDER_STATUS>
        },
        ...
    ]
    ```
## Installation

1. Git clone the project 
``` git clone git@github.com:cliffordchan/order_management.git demo```
2. Change directory into the *demo* folder 
```cd demo```
3. Copy .env.example to .env
```cp .env.example .env ```
4. Generate a new application key with the following command
```php artisan key:generate ```
5. Replace *GOOGLE_DISTANCE_MATRIX_API_KEY* key with the actual Google API key in .env of the root directory
6. Execute *./start.sh*
7. Endpoint will be available at *http://localhost:8080*
8. UnitTests will be available by executing at the root directory of the project
```angular2html
./vendor/bin/phpunit
```