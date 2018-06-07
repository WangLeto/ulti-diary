<?php

namespace App\Support;

use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class Response
 * @package App\Support
 */
class Response
{
    /**
     * HTTP Response.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    private $response;

    /**
     * HTTP status code.
     *
     * @var int
     */
    private $statusCode = HttpResponse::HTTP_OK;

    /**
     * Create a new class instance.
     *
     * @param $response
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    /**
     * Return a 201 response with the given created resource.
     *
     * @param null $resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withCreated($resource = null)
    {
        $this->statusCode = HttpResponse::HTTP_CREATED;

        if (is_null($resource)) {
            return $this->json();
        }

        return $this->result($resource);
    }

    /**
     * Make a 204 no content response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withNoContent()
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_NO_CONTENT
        )->json();
    }

    /**
     * Make a 400 'Bad Request' response.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withBadRequest($message = 'Bad Request')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_BAD_REQUEST
        )->withError($message);
    }

    /**
     * Make a 401 'Unauthorized' response.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_UNAUTHORIZED
        )->withError($message);
    }

    /**
     * Make a 403 'Forbidden' response.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_FORBIDDEN
        )->withError($message);
    }

    /**
     * Make a 404 'Not Found' response.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_NOT_FOUND
        )->withError($message);
    }

    /**
     * Make a 429 'Too Many Requests' response.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withTooManyRequests($message = 'Too Many Requests')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_TOO_MANY_REQUESTS
        )->withError($message);
    }

    /**
     * Make a 500 'Internal Server Error' response.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withInternalServer($message = 'Internal Server Error')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_INTERNAL_SERVER_ERROR
        )->withError($message);
    }

    /**
     * Make an error response.
     *
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withError($message)
    {
        return $this->json([
            'message' => $message
        ]);
    }

    /**
     * Make a JSON response with the transformed items.
     *
     * @param $data
     * @param TransformerAbstract|null $transformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data)
    {
        return $this->json($data);
    }

    /**
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($data = [], array $headers = [])
    {
        return $this->response->json($data, $this->statusCode, $headers);
    }

    /**
     * Set HTTP status code.
     *
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Gets the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}