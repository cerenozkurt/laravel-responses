<?php

namespace Cerenimo\LaravelResponses;


use Symfony\Component\HttpFoundation\JsonResponse;


trait Response
{
    protected $customData;
    protected $message;
    protected $statusCode;
    protected $validation;
    protected $exception;
    protected $pagination;
    protected $dataName;
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

    public function setException($exception)
    {
        $this->exception = $exception->getMessage();
        return $this;
    }
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
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

        return new JsonResponse($response, $this->statusCode ?? 200, ['Content-Type' => 'application/json']);
    }


    public function responseError()
    {
        $response = [
            'result' => false,
        ];
        if ($this->message != null) {
            $response['message'] = $this->message;
        }
        return new JsonResponse($response, $this->statusCode ?? 200, ['Content-Type' => 'application/json']);
    }

    public function responseValidation()
    {
        $response = [
            'result' => false,
        ];
        $response['validation_error'] = $this->validation;
        return new JsonResponse($response, 422, ['Content-Type' => 'application/json']);
    }

    public function responseNotFound()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Not found.'
        ], 404, ['Content-Type' => 'application/json']);
    }

    //Forbidden geçerli kimlik var ama kimlik sahibi işlem için yetkiye sahip değil 
    public function responseForbidden($message = null)
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'No access permission.'
        ], 403, ['Content-Type' => 'application/json']);
    }

    //Unauthorized geçersiz kimlik bilgisi
    public function responseUnauthorized($message = null)
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Not authorized.'
        ], 401, ['Content-Type' => 'application/json']);
    }

    public function responseTryCatch()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->exception ?? 'An error occurred.'
        ], $this->statusCode ?? 500, ['Content-Type' => 'application/json']);
    }

    public function responseBadRequest()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Bad request.'
        ], 400, ['Content-Type' => 'application/json']);
    }

    public function responseConflict()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Conflict.'
        ], 409, ['Content-Type' => 'application/json']);
    }

    public function responsePayloadTooLarge()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Payload too large.'
        ], 413, ['Content-Type' => 'application/json']);
    }

    public function responseTooManyRequests()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Too many requests.'
        ], 429, ['Content-Type' => 'application/json']);
    }

    public function responseInternalServer()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Internal server error.'
        ], 500, ['Content-Type' => 'application/json']);
    }

    public function responseNotImplemented()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Not implemented.'
        ], 501, ['Content-Type' => 'application/json']);
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
            $datas = [$this->dataName => $this->pagination->items()];
        } else {
            $datas = $this->pagination->items();
        }
        $pagination = [
            'links' => [
                'first' => $this->pagination->onFirstPage(),
                'last' => $this->pagination->onLastPage(),
                'prev' => $this->pagination->previousPageUrl(),
                'next' => $this->pagination->nextPageUrl(),
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
        $response['meta'] = $pagination;

        return new JsonResponse($response, $this->statusCode ?? 200, ['Content-Type' => 'application/json']);
    }

    public function responseInvalidToken($message = null)
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Invalid token.'
        ], 498, ['Content-Type' => 'application/json']);
    }
}
