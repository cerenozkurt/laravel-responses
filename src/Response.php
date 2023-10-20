<?php

namespace Cerenimo\LaravelResponses;

use Cerenimo\LaravelResponses\Helpers\PaginationHelper;
use Illuminate\Http\JsonResponse;

trait Response
{

    protected $customData;
    protected $message;
    protected $statusCode;
    protected $validation;
    protected $exceptionError;
    protected $pagination;
    protected $dataName;
    protected $collection;
    public function setCustomData($key, $value)
    {
        if (!is_array($this->customData)) {
            $this->customfield = [];
        }
        $this->customData[$key] = $value;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setValidation($validation)
    {
        $this->validation = $validation->errors();
        return $this;
    }

    public function setException($exceptionError)
    {
        $this->exceptionError = $exceptionError->getMessage();
        return $this;
    }

    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
        return $this;
    }

    public function setCollectionToPagination($collection, $perPage = 10)
    {
        $paginationHelper = new PaginationHelper();
        $this->pagination = $paginationHelper->collectToPaginate($collection, $perPage);
        return $this;
    }

    public function setDataName($dataName)
    {
        $this->dataName = $dataName;
        return $this;
    }

    public function responseSuccess()
    {
        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        $response = [
            'result' => true,
        ];
        if ($this->message != null) {
            $response['message'] = $this->message;
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }
        
        $this->customData = [];
        return new JsonResponse($response, $this->statusCode ?? 200);
    }

    public function responseError()
    {
        $response = [
            'result' => false,
        ];
        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }
        $response['error'] = $this->message ?? 'An error occurred.';
        $this->customData = [];
        return new JsonResponse($response, $this->statusCode ?? 500);
    }

    public function responseValidation()
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        if (!$this->validation && !$this->message) {
            return $this->setMessage('No validation data found.')->responseBadRequest();
        }

        $response['validation_error'] = $this->validation ?? $this->message;

        $this->customData = [];
        return new JsonResponse($response, 422);
    }

    public function responseNotFound()
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        $response['error'] = $this->message ?? 'Not found.';

        $this->customData = [];
        return new JsonResponse($response, 404);
    }

    //Forbidden geçerli kimlik var ama kimlik sahibi işlem için yetkiye sahip değil 
    public function responseForbidden()
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        $response['error'] = $this->message ?? 'No access permission.';

        $this->customData = [];
        return new JsonResponse($response, 403);
    }

    //Unauthorized geçersiz kimlik bilgisi
    public function responseUnauthorized()
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        $response['error'] = $this->message ?? 'Not authorized.';

        $this->customData = [];
        return new JsonResponse($response, 401);
    }

    public function responseTryCatch()
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        if (!$this->exceptionError) {
            return $this->setMessage('No exception data found.')->responseBadRequest();
        }

        $response['error'] = $this->exceptionError;

        $this->customData = [];
        return new JsonResponse($response, 500);
    }

    public function responseBadRequest()
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        $response['error'] = $this->message ?? 'Bad request.';

        $this->customData = [];
        return new JsonResponse($response, 400);
    }

    public function responseConflict()
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        $response['error'] = $this->message ?? 'Conflict';

        $this->customData = [];
        return new JsonResponse($response, 409);
    }

    public function responsePayloadTooLarge()
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        $response['error'] = $this->message ?? 'Payload too large.';

        $this->customData = [];
        return new JsonResponse($response, 413);
    }

    public function responseTooManyRequests()
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        $response['error'] = $this->message ?? 'Too many requests.';

        $this->customData = [];
        return new JsonResponse($response, 429);
    }

    public function responseInternalServer()
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        $response['error'] = $this->message ?? 'Internal server error.';

        $this->customData = [];
        return new JsonResponse($response, 500);
    }

    public function responseNotImplemented()
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        $response['error'] = $this->message ?? 'Not implemented.';

        $this->customData = [];
        return new JsonResponse($response, 501);
    }

    public function responseDataWithPagination()
    {
        $response = [
            'result' => true,
        ];
        if ($this->message != null) {
            $response['message'] = $this->message;
        }
        if (!isset($this->pagination)) {
            return $this->setMessage('No pagination data found.')->responseBadRequest();
        }
        if ($this->dataName) {
            $datas = [$this->dataName => array_values($this->pagination->items())];
        } else {
            $datas = array_values($this->pagination->items());
        }

        $perPage = $this->pagination->perPage();

        $prevLink = $this->pagination->previousPageUrl() != false ? $this->pagination->previousPageUrl() . '&perPage=' . $perPage : false;
        $nextLink = $this->pagination->nextPageUrl() != false ? $this->pagination->nextPageUrl() . '&perPage=' . $perPage : false;

        $pagination = [
            'links' => [
                'first' => $this->pagination->onFirstPage(),
                'last' => $this->pagination->onLastPage(),
                'prev' => $prevLink,
                'next' => $nextLink,
            ],
            'meta' => [
                'current_page' => $this->pagination->currentPage(),
                'from' => $this->pagination->firstItem(),
                'to' => $this->pagination->lastItem(),
                'per_page' => $this->pagination->perPage(),
                'total' => $this->pagination->total(),
            ],
        ];
        $response['data'] = $datas;
        $response['page'] = $pagination;

        $this->customData = [];
        return new JsonResponse($response, $this->statusCode ?? 200);
    }

    public function responseInvalidToken($message = null)
    {
        $response = [
            'result' => false,
        ];

        $datas = [];
        if ($this->customData) {
            foreach ($this->customData as $key => $value) {
                $datas[$key] = $value;
            }
        }
        if ($this->customData != []) {
            $response['data'] = $this->customData;
        }

        $response['error'] = $this->message ?? 'Invalid token.';

        $this->customData = [];
        return new JsonResponse($response, 498);
    }
}
