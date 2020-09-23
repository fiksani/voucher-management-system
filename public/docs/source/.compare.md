---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.

<!-- END_INFO -->

#Campaign


APIs for managing campaign
<!-- START_37676c62cd7e694510cd03e41696f824 -->
## List of campaign

> Example request:

```bash
curl -X GET -G "http://103.14.21.56:9580/api/v1/campaign" \
    -H "Authorization: Bearer {token}" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json"
```

```javascript
const url = new URL("http://103.14.21.56:9580/api/v1/campaign");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/campaign`


<!-- END_37676c62cd7e694510cd03e41696f824 -->

<!-- START_c6e238630d13a3979783364e09e71e5d -->
## Create a campaign

> Example request:

```bash
curl -X POST "http://103.14.21.56:9580/api/v1/campaign" \
    -H "Authorization: Bearer {token}" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"Yellofit 20% Discount","short_code":"YFK20P","period":1,"expired_date":"2020-09-06","total_voucher_qty":100,"value":20,"type":"percentage"}'

```

```javascript
const url = new URL("http://103.14.21.56:9580/api/v1/campaign");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "Yellofit 20% Discount",
    "short_code": "YFK20P",
    "period": 1,
    "expired_date": "2020-09-06",
    "total_voucher_qty": 100,
    "value": 20,
    "type": "percentage"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "message": "New Campaign is created.",
        "campaign": {
            "id": 1,
            "name": "Yellowfit 20% Discount",
            "short_code": "YFK20B11",
            "period_day": 7,
            "value": 20,
            "type": "percentage",
            "generate_voucher_qty": 1,
            "total_voucher_qty": 100,
            "expired_date": "2020-09-20",
            "active": true
        }
    }
}
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`POST api/v1/campaign`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    name | string |  required  | The name of the campaign.
    short_code | string |  required  | The code of the campaign.
    period | number |  required  | The period of the campaign in days.
    expired_date | date |  required  | The expired date of the campaign.
    total_voucher_qty | number |  required  | The maximum voucher quantity.
    value | number |  required  | The value of the campaign based on type.
    type | string |  required  | The type of the campaign. Should be: percentage,virtual_currency.

<!-- END_c6e238630d13a3979783364e09e71e5d -->

<!-- START_28e8f21e1a547c2cbf464a396eb98b25 -->
## Update campaign

> Example request:

```bash
curl -X PUT "http://103.14.21.56:9580/api/v1/campaign/1/edit" \
    -H "Authorization: Bearer {token}" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"Yellofit 20% Discount","short_code":"YFK20P","period":1,"expired_date":"2020-09-06","total_voucher_qty":100,"active":false,"value":20,"type":"percentage"}'

```

```javascript
const url = new URL("http://103.14.21.56:9580/api/v1/campaign/1/edit");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "Yellofit 20% Discount",
    "short_code": "YFK20P",
    "period": 1,
    "expired_date": "2020-09-06",
    "total_voucher_qty": 100,
    "active": false,
    "value": 20,
    "type": "percentage"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "message": "Campaign is already updated.",
        "campaign": {
            "id": 1,
            "name": "Yellowfit 20% Discount",
            "short_code": "YFK20B11",
            "period_day": 7,
            "value": 20,
            "type": "percentage",
            "generate_voucher_qty": 1,
            "total_voucher_qty": 100,
            "expired_date": "2020-09-20",
            "active": true
        }
    }
}
```
> Example response (404):

```json
{
    "message": "Campaign Not Found"
}
```

### HTTP Request
`PUT api/v1/campaign/{id}/edit`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    name | string |  required  | The name of the campaign.
    short_code | string |  required  | The code of the campaign.
    period | number |  required  | The period of the campaign in days.
    expired_date | date |  required  | The expired date of the campaign.
    total_voucher_qty | number |  required  | The maximum voucher quantity.
    active | boolean |  required  | The status of the campaign.
    value | number |  required  | The value of the campaign based on type.
    type | string |  required  | The type of the campaign. Should be: percentage,virtual_currency.

<!-- END_28e8f21e1a547c2cbf464a396eb98b25 -->

#Customer


APIs for show list of customer and customer vouchers
<!-- START_8f4c9c0c4cc829e4a5f6445053993128 -->
## List Customers

> Example request:

```bash
curl -X GET -G "http://103.14.21.56:9580/api/v1/customer" \
    -H "Authorization: Bearer {token}" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json"
```

```javascript
const url = new URL("http://103.14.21.56:9580/api/v1/customer");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/customer`


<!-- END_8f4c9c0c4cc829e4a5f6445053993128 -->

<!-- START_ae58865385746a3fd2cc9e8e0232b0bc -->
## List Customer Vouchers

> Example request:

```bash
curl -X GET -G "http://103.14.21.56:9580/api/v1/customer/1/vouchers" \
    -H "Authorization: Bearer {token}" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json"
```

```javascript
const url = new URL("http://103.14.21.56:9580/api/v1/customer/1/vouchers");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/customer/{id}/vouchers`


<!-- END_ae58865385746a3fd2cc9e8e0232b0bc -->

#Voucher


APIs for register and redeem voucher
<!-- START_852df5c378a514ac816f2de04778a0a4 -->
## Register voucher

> Example request:

```bash
curl -X POST "http://103.14.21.56:9580/api/v1/campaign/1/vouchers" \
    -H "Authorization: Bearer {token}" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"Suherman","email":"suherman@site.domain"}'

```

```javascript
const url = new URL("http://103.14.21.56:9580/api/v1/campaign/1/vouchers");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "Suherman",
    "email": "suherman@site.domain"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "code": "Y-2081600950810",
        "expired_date": "2020-09-24T12:33:30.734968Z"
    }
}
```
> Example response (404):

```json
{
    "message": "Voucher Not Found"
}
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`POST api/v1/campaign/{id}/vouchers`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    name | string |  required  | The name of the customer.
    email | string |  required  | The email of the customer.

<!-- END_852df5c378a514ac816f2de04778a0a4 -->

<!-- START_0dd68512e9f5faf7846135a7f351c3d5 -->
## Redeem voucher

> Example request:

```bash
curl -X POST "http://103.14.21.56:9580/api/v1/redeem" \
    -H "Authorization: Bearer {token}" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"code":"Y-2081600950810"}'

```

```javascript
const url = new URL("http://103.14.21.56:9580/api/v1/redeem");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "code": "Y-2081600950810"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "message": "You've successfully redeemed your code",
        "voucher": {
            "code": "Y-2081600950810",
            "voucher": "YELLOWFIT-20",
            "type": "percentage",
            "value": 20
        }
    }
}
```
> Example response (404):

```json
{
    "message": "Voucher Not Found"
}
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`POST api/v1/redeem`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    code | string |  required  | The code of the customer voucher.

<!-- END_0dd68512e9f5faf7846135a7f351c3d5 -->

<!-- START_80110ad69e4c78451154070683f64991 -->
## Check voucher

> Example request:

```bash
curl -X POST "http://103.14.21.56:9580/api/v1/check" \
    -H "Authorization: Bearer {token}" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"code":"Y-2081600950810"}'

```

```javascript
const url = new URL("http://103.14.21.56:9580/api/v1/check");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "code": "Y-2081600950810"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "voucher": {
            "code": "Y-2081600950810",
            "voucher": "YELLOWFIT-20",
            "type": "percentage",
            "value": 20,
            "status": "used"
        }
    }
}
```
> Example response (404):

```json
{
    "message": "Voucher Not Found"
}
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`POST api/v1/check`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    code | string |  required  | The code of the customer voucher.

<!-- END_80110ad69e4c78451154070683f64991 -->


