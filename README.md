# Response Messages
```php
composer require cerenimo/laravel-responses
 ```

<br>

The ResponseTrait is a trait used to standardize the HTTP response messages in Laravel and Lumen. You can install it using the composer command mentioned above. To use it in your class, add the following line with the "use" keyword:


```php
use Cerenimo\LaravelResponses\Response;

class YourClass {

use Response;
// ...
}
```

<br>

Inside your function, you can use it as follows:

```php 
return $this->setMessage('successfully')
->responseSucces();
```
Response / 200 OK
```php
{
    "result": true,
    "message": "successfully"
}
```
<br>
<hr>
<br>
Parameters:

<br>

- **setCustomData($key,$value)**
- **setMessage($message)**
- **setStatusCode($statusCode)**
- **setValidation($validation)**
- **setException($exception)**
- **setPagination($pagination)**
- **setDataName($dataName)**
<br>
<br>
<hr>
<br>
The package provides the following functions:

<br>

<details>
<summary> responseSuccess() </summary> 

- *Use if the request is successful.*
  
  - *Parameters optional. setCustomData, setMessage and setStatusCode can be added.*
  - *Usage examples:*
    ```php
        $this->setCustomData('user', $user)
        ->setCustomData('token', $token)
        ->setMessage('User Created')
        ->setStatusCode(201)
        ->responseSuccess(); 
    ```
    ```php
        $this->responseSuccess();
    ```
    ```php
        $this->setMessage('Successfully!')->responseSuccess();
    ```
  
  - *Response example:*
    ```php
    {
        "result": true,
        "message": "User Created",
        "data": {
            "user": {
                "id": 372,
                "name": "Ceren Özkurt"
            },
            "token" : "Bfafa98a7f8afkafafaf98afajfka"
        }
    }
    ```
</details>
<br>
<details>
<summary> responseDataWithPagination() </summary> 

- *Use if the server does not support the features required to fulfill the request.*
  - *setPagination should be added and setMessage and setDataName can be added.*
  - *Usage examples:*
    ```php
        $this->setPagination($setPagination)
        ->setDataName('files')
        ->responseDataWithPagination(); 
    ```

  - *Response example:*
    ```php
    {
        "result": true,
        "data": {
            "files": [
                {
                    "id": 60,
                    "userId": "auth0|64a7fd7bc8e7f423cb5285b2",
                    "filename": "seller-288138-barkod-bazlı-iade-raporu-2023.05.03-18.19.04.xlsx",
                    "fullPathName": "/home/rontest/public_html/uploads/64a7fd7bc8e7f423cb5285b2/1688731195_jaTuvvO7dSrFzcJy.xlsx",
                    "type": "excel",
                    "createdAt": "2023-07-07T11:59:55.000000Z",
                    "updatedAt": "2023-07-07T11:59:55.000000Z"
                },
                {
                    "id": 59,
                    "userId": "auth0|64a7fd7bc8e7f423cb5285b2",
                    "filename": "Ağrı Dağı Efsanesi .pdf",
                    "fullPathName": "/home/rontest/public_html/uploads/64a7fd7bc8e7f423cb5285b2/1688731130_74bI3pGXa9yq7Fda.pdf",
                    "type": "pdf",
                    "createdAt": "2023-07-07T11:58:50.000000Z",
                    "updatedAt": "2023-07-07T11:58:50.000000Z"
                }
            ]
            },
            "page": {
                "links": {
                    "first": true,
                    "last": true,
                    "prev": null,
                    "next": null
                },
                "meta": {
                    "current_page": 1,
                    "from": 1,
                    "to": 2,
                    "per_page": 25,
                    "total": 2
                }
            }       
    }

    ```
</details>
<br>
<details>
<summary> responseError() </summary> 

- *Use if the request is error.*
  - *Parameters optional. setMessage and setStatusCode can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('Failed')
        ->setStatusCode(400)
        ->responseError(); 
    ```
    ```php
        $this->responseError(); //default message and status code
    ```
    ```php
        $this->setMessage('Failed!')
        ->responseSuccess();
    ```
  
  - *Response example:*
    ```php
    {
        "result": false,
        "message": "Failed",
    }
    ```
</details>
<br>

<details>
<summary> responseValidation() </summary> 

- *Use if there is a validation error.*
  - *Parameter required. setValidation should be added.*
  - *Usage example:*
    ```php
        $this->setValidation($validation)
        ->responseValidation(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "validation_error": {
            "name": [
                "The name field is required."
            ]
        }
    }
    ```
</details>
<br>

<details>
<summary> responseNotFound() </summary> 

- *Use if not found error.*
  - *Parameter optional. setMessage can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('Failed')
        ->responseNotFound(); 
    ```
    ```php
        $this->responseNotFound(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": "Failed"
    }
    ```
</details>
<br>
<details>
<summary> responseForbidden() </summary> 

- *Use in forbidden error.*
  - *Parameter optional. setMessage can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('No Permission')
        ->responseForbidden(); 
    ```
    ```php
        $this->responseForbidden(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": "No access permission."
    }
    ```
</details>
<br>

<details>
<summary> responseUnauthorized() </summary> 

- *Use in unauthorized error.*
  - *Parameter optional. setMessage can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('No Authorized')
        ->responseUnauthorized(); 
    ```
    ```php
        $this->responseUnauthorized(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": "Not authorized."
    }
    ```
</details>
<br>

<details>
<summary> responseTryCatch() </summary> 

- *Use in try-catch error.*
  - *Parameter required. setException should be added and setStatusCode can be added. *
  - *Usage examples:*
    ```php
        $this->setException($e)
        ->responseTryCatch(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": "sql connection error"
    }
    ```
       
  - *Parameter optional. setMessage can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('Failed')
        ->responseValidation(); 
    ```
    ```php
        $this->responseValidation(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": "Failed"
    }
    ```
</details>
<br>
<details>
<summary> responseBadRequest() </summary> 

- *Use if the sent request is incorrect.*
  - *Parameter optional. setMessage can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('Failed')
        ->responseBadRequest(); 
    ```
    ```php
        $this->responseBadRequest(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": "Bad request."
    }
    ```  
</details>
<br>
<details>
<summary> responseConflict() </summary> 

- *Use if a mismatch occurs due to a predetermined rule or version differences on the web server of your request.*
  - *Parameter optional. setMessage can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('Failed')
        ->responseConflict(); 
    ```
    ```php
        $this->responseConflict(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": "Conflict."
    }
    ```
</details>
<br>
<details>
<summary> responsePayloadTooLarge() </summary> 

- *Use if the request entity is much larger than the limits defined by the server.*
  - *Parameter optional. setMessage can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('Failed')
        ->responsePayloadTooLarge(); 
    ```
    ```php
        $this->responsePayloadTooLarge(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": 'Payload too large.'
    }
    ```
</details>
<br>
<details>
<summary> responseTooManyRequests() </summary> 

- *Use if the website exceeded the specified request limit.*
  - *Parameter optional. setMessage can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('Failed')
        ->responseTooManyRequests(); 
    ```
    ```php
        $this->responseTooManyRequests(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": "Too many requests."
    }
    ```
</details>
<br>
<details>
<summary> responseInternalServer() </summary> 

- *Use if a server-side error occurred.*
  - *Parameter optional. setMessage can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('Failed')
        ->responseInternalServer(); 
    ```
    ```php
        $this->responseInternalServer(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": 'Internal server error.'
    }
    ```
</details>
<br>
<details>
<summary> responseNotImplemented() </summary> 

- *Use if the server does not support the features required to fulfill the request.*
  - *Parameter optional. setMessage can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('Failed')
        ->responseNotImplemented(); 
    ```
    ```php
        $this->responseNotImplemented(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": 'Not implemented.'
    }
    ```
</details>
<br>
<details>
<summary> responseInvalidToken() </summary> 

- *Use if token is invalid.*
  - *Parameter optional. setMessage can be added.*
  - *Usage examples:*
    ```php
        $this->setMessage('Failed')
        ->responseInvalidToken(); 
    ```
    ```php
        $this->responseInvalidToken(); 
    ```

  - *Response example:*
    ```php
    {
        "result": false,
        "error": 'Invalid token.'
    }
    ```
    </details>
