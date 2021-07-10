This project is simple shopping cart system that allows you to create products,
define different offers and apply them on order.

## API's

### Product API's
You can create product, update it and get its data.
- `create product` POST /products
- `update product` PUT /products/{productId}
- `show product` GET /products/{productId}
---

#### POST /products
**Parameters**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `name` | required | string  | The product name. should be at most 255 characters. |
|     `price` | required | integer  | The product price. should be a numeric. |

**Response**

*successful creation HTTP 201*
```json
{
    "data": {
        "id": 1,
        "name": "test product",
        "price": 1000
    }
}
```
*validation errors HTTP 422*
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": [
            "The name field is required."
        ],
        "price": [
            "The price field is required."
        ]
    }
}
 ```
---

#### PUT /products/{productId}
**Parameters**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `name` | required | string  | The product name. should be at most 255 characters. |
|     `price` | required | integer  | The product price. should be a numeric. |

**Response**

*successful update HTTP 200*
```json
{
    "data": {
        "id": 1,
        "name": "new name",
        "price": 2000
    }
}
```
*validation errors HTTP 422*
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": [
            "The name field is required."
        ],
        "price": [
            "The price field is required."
        ]
    }
}
 ```
---
#### Get /products/{productId}
**Response**

*successful HTTP 200*
```json
{
    "data": {
        "id": 1,
        "name": "test product",
        "price": 1000
    }
}
```
### Offer API's
- `set product offers` POST /products/{productId}/offers
- `show product offers` GET /products/{productId}/offers
- `delete product offers` DELETE /products/{productId}/offers
#### POST /products/{productId}/offers
**Request Body**

```json
{
    "offers": [
        {
            "products_number": 2,
            "price": 1800
        },
        {
            "products_number": 3,
            "price": 2600
        }
    ]
}
```

**Response**

*successful creation HTTP 201*
```json
{
    "data": [
        {
            "product_id": 1,
            "products_number": 2,
            "price": 1800
        },
        {
            "product_id": 1,
            "products_number": 3,
            "price": 2600
        }
    ]
}
```

*validation error HTTP 422*
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "offers.0.price": [
            "The offers.0.price field is required."
        ],
        "offers.1.number_of_products": [
            "The offers.1.products_number is required."
        ]
    }
}
```
*error when 2 different offers have same products_number HTTP 422*
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "offers": [
            "The value of products_number must be unique within the given request."
        ]
    }
}
 ```
---

#### GET /products/{productId}/offers
**Response**

*successful HTTP 200*
```json
{
    "data": [
        {
            "product_id": 1,
            "products_number": 2,
            "price": 1800
        },
        {
            "product_id": 1,
            "products_number": 3,
            "price": 2600
        }
    ]
}
```
---

#### DELETE /products/{productId}/offers
**Response**

*successful HTTP 204*

---

### Order API

- `order` POST /order
---

#### POST /order
**Request Body**

client sends an array of product Ids.

```json
{
    "products": [
        {
            "id": 1
        },
        {
            "id": 2
        },
        {
            "id": 3
        },
        {
            "id": 1
        },
        {
            "id": 2
        },
        {
            "id": 3
        }
    ]
}
```

**Response**

*successful response HTTP 200*
```json
{
    "totalPrice": 200,
    "discount": 170800,
    "items": [
        {
            "products": {
                "name": "test",
                "price": 57000
            },
            "count": 3,
            "totalPrice": 200,
            "discount": 170800,
            "appliedOffers": [
                {
                    "productsToBuy": 3,
                    "totalPriceAfterOffer": 200
                }
            ]
        }
    ]
}
```

## Installation
1. make sure you have `git`, `docker` and `docker-compose`.
2. clone this repository
   ```shell
   git clone git@github.com:samirreza/shopping-cart.git
   ```
3. copy `.env.example` file and change variables as suits your environment.
4. run these commands :
   ```shell
   make up
   make install
   make db
   ```
