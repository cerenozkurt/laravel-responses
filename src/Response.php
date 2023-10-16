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

        return new JsonResponse($response, $this->statusCode ?? 200);
    }

    public function responseError()
    {
        $response = [
            'result' => false,
        ];
        if ($this->message != null) {
            $response['error'] = $this->message ?? 'An error occurred.';
        }
        return new JsonResponse($response, $this->statusCode ?? 500);
    }

    public function responseValidation()
    {
        $response = [
            'result' => false,
        ];
        if (!$this->validation && !$this->message) {
            return $this->setMessage('No validation data found.')->responseBadRequest();
        }
        $response['validation_error'] = $this->validation ?? $this->message;
        return new JsonResponse($response, 422);
    }

    public function responseNotFound()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Not found.'
        ], 404);
    }

    //Forbidden geçerli kimlik var ama kimlik sahibi işlem için yetkiye sahip değil 
    public function responseForbidden()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'No access permission.'
        ], 403);
    }

    //Unauthorized geçersiz kimlik bilgisi
    public function responseUnauthorized()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Not authorized.'
        ], 401);
    }

    public function responseTryCatch()
    {
        if ($this->exceptionError) {
            return $this->setMessage('No exception data found.')->responseBadRequest();
        }
        return new JsonResponse([
            'result' => false,
            'error' => $this->exceptionError
        ], $this->statusCode ?? 500);
    }

    public function responseBadRequest()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Bad request.'
        ], 400);
    }

    public function responseConflict()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Conflict.'
        ], 409);
    }

    public function responsePayloadTooLarge()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Payload too large.'
        ], 413);
    }

    public function responseTooManyRequests()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Too many requests.'
        ], 429);
    }

    public function responseInternalServer()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Internal server error.'
        ], 500);
    }

    public function responseNotImplemented()
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Not implemented.'
        ], 501);
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

        $pagination = [
            'links' => [
                'first' => $this->pagination->onFirstPage(),
                'last' => $this->pagination->onLastPage(),
                'prev' => $this->pagination->previousPageUrl() . '&perPage=' . $perPage,
                'next' => $this->pagination->nextPageUrl() . '&perPage=' . $perPage,
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


        return new JsonResponse($response, $this->statusCode ?? 200);
    }

    public function responseInvalidToken($message = null)
    {
        return new JsonResponse([
            'result' => false,
            'error' => $this->message ?? 'Invalid token.'
        ], 498);
    }
}
