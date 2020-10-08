<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Lumen\Routing\Controller as BaseController;

class ApiController extends BaseController
{

    /**
     *
     */
    const DEFAULT_MAX_LIMIT = 1000;

    /**
     * @var int
     */
    protected $statusCode = 200;
    /**
     * @var string
     */
    protected $message = '';
    /**
     * @var bool
     */
    protected $error = false;
    /**
     * @var array
     */
    protected $debugInfo = [];
    /**
     * @var int
     */
    protected $errorCode = 0;


    /**
     * Function to return an error response.
     *
     * @param $message
     * @return mixed
     */
    public function respondWithError($message)
    {
        $this->error = true;
        $this->message = $message;
        return $this->respond(array());
    }

    /**
     * Function to return a bad request response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondBadRequestError($message = 'Bad Request!')
    {
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        return $this->respondWithError($message);
    }

    /**
     * Function to return a Not Found response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($message = 'Resource Not Found')
    {
        $this->statusCode = Response::HTTP_NOT_FOUND;
        return $this->respondWithError($message);
    }

    /**
     * Function to return an internal error response.
     *
     * @param string $message
     * @param null $errors
     *
     * @return mixed
     */
    public function respondInternalError($message = 'Internal Server Error!', $errors = null)
    {
        $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        return $this->respondWithError($message);
    }

    /**
     * Function to return an internal error response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondMethodNotAllowed($message = 'Method not allowed!')
    {
        $this->statusCode = Response::HTTP_METHOD_NOT_ALLOWED;
        return $this->respondWithError($message);
    }

    /**
     * Function to return a service unavailable response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondServiceUnavailable($message = "Service Unavailable!")
    {
        $this->statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        return $this->respondWithError($message);
    }

    /**
     * Throws a bad request exception with the validator's error messages.
     *
     * @param Validator $validator The validator to get the message from
     *
     * @return mixed
     */
    public function showValidationError($validator)
    {
        $this->error = true;
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        $this->message = implode("|", $validator->errors()->all());
        return $this->respond();
    }

    /**
     * Function to return a created response
     *
     * @param $data array The data to be included
     *
     * @return mixed
     *
     */
    public function respondCreated($data)
    {
        $this->statusCode = Response::HTTP_CREATED;
        return $this->respond($data);
    }

    /**
     * Function to return a response with a message
     *
     * @param $data array The data to be included
     *
     * @param $message string The message to be shown in the meta of the response
     *
     * @return mixed
     */
    public function respondWithMessage($data, $message)
    {
        $this->statusCode = Response::HTTP_OK;
        $this->message = $message;
        return $this->respond($data);
    }

    /**
     * Function to return a generic response.
     *
     * @param $data array to be used in response.
     * @param array $headers Headers to b used in response.
     * @return mixed Return the response.
     */
    public function respond($data = [], $headers = [])
    {
        $meta = [
            'meta' => [
                'error' => $this->error,
                'message' => $this->message,
                'statusCode' => $this->statusCode,
            ]
        ];
        if (empty($data) && !is_array($data))
            $data = array_merge($meta, ['response' => null]);
        else
            $data = array_merge($meta, ['response' => $data]);

        return response()->json($data, $this->statusCode, $headers);
    }

    /**
     * Responds paginated items
     *
     * @param Request $request
     * @param array|\Illuminate\Contracts\Pagination\LengthAwarePaginator $items
     *
     * @param array $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondPagination($request, $items, $options=[])
    {
        if (!($items instanceof LengthAwarePaginator)) {
            $pagination = $this->paginate($request, $items);
        } else {
            $pagination = $items;
        }
        return $this->respond(['pagination' => $this->getPagination($pagination), 'items' => $pagination->items(), 'options' =>$options]);
    }

    /**
     * Retrieves the pagination meta in an array format
     *
     * @param LengthAwarePaginator $item
     * @return array
     */
    public function getPagination(LengthAwarePaginator $item)
    {
        return [
            'total' => $item->total(),
            'current_page' => $item->currentPage(),
            'last_page' => $item->lastPage(),
            'from' => $item->firstItem(),
            'to' => $item->lastItem()
        ];
    }

    /**
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function successJson($message = '', $data = null)
    {
        return response()->json([
            'error' => false,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * @param $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorJson($message, $data = null)
    {
        return response()->json([
            'error' => true,
            'message' => $message,
            'data' => $data
        ]);
    }
}
