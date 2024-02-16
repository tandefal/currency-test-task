# Currency Exchange API

This API provides access to currency exchange rates.

## Base URL

The base URL for all endpoints is: `https://example.com/`

## Endpoints

### Get All Currency Rates

- **URL:** `/rates`
- **Method:** `GET`
- **Description:** Returns a list of all currency rates.
- **Response:**
    ```json
    [
        {"currency_code": "USD", "value": 1.23, ...},
        {"currency_code": "EUR", "value": 0.89, ...},
        ...
    ]
    ```

### Get Currency Rate

- **URL:** `/rate/{currency_code}`
- **Method:** `GET`
- **Description:** Returns the exchange rate for the specified currency code.
- **Parameters:**
    - `{currency_code}`: The code of the currency (e.g., USD, EUR).
- **Response:**
    ```json
    {"currency_code": "USD", "value": 1.23, ...}
    ```
- **Example:**
    - Request: `/rate/USD`
    - Response:
        ```json
        {"currency_code": "USD", "value": 1.23, ...}
        ```

## Error Responses

- **400 Bad Request**: Invalid request or parameters.
    ```json
    {"error": "Invalid request"}
    ```

- **404 Not Found**: Resource not found.
    ```json
    {"error": "Resource not found"}
    ```

- **500 Internal Server Error**: Server error occurred.
    ```json
    {"error": "Internal server error"}
    ```

## Authentication

This API does not require authentication for accessing public endpoints.

