<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\MessageProvider;
use Illuminate\Support\MessageBag;

abstract class BaseResponse implements MessageProvider
{
    public const TYPE_INT = 1;
    public const TYPE_FLOAT = 2;

    /**
     * @var bool
     */
    protected $successful = false;

    /**
     * @var string
     */
    protected $response = '';

    /**
     * @var int
     */
    protected $httpStatusCode;

    /**
     * @var array
     */
    protected $errorMessages = [];

    /**
     * BaseResponse constructor.
     *
     * @param int $intHttpStatusCode
     */
    public function __construct(int $intHttpStatusCode)
    {
        $this->httpStatusCode = $intHttpStatusCode;
    }

    /**
     * Add an error message, with optional key.
     *
     * @param string $message Error message
     * @param null   $key     Key for message
     */
    public function addErrorMessage($message, $key = null)
    {
        if ($key === null) {
            $this->errorMessages[] = $message;

            return;
        }
        $this->errorMessages[$key] = $message;
    }

    /**
     * Set error messages.
     *
     * @param $messages
     */
    public function setErrorMessages(array $messages)
    {
        $this->errorMessages = $messages;
    }

    /**
     * Set response.
     *
     * @param mixed $response Response
     */
    public function setResponse($response)
    {
        $this->response = $response;
        if ($this->hasBeenSuccessful()) {
            $this->parseResponse();
        }
    }

    /**
     * Set that response is a positive response.
     */
    public function setSuccessful()
    {
        $this->successful = true;
    }

    /**
     * Returns true if request has been successful.
     *
     * @return bool
     */
    public function hasBeenSuccessful() : bool
    {
        return $this->successful;
    }

    /**
     * Returns response.
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns array with error messages.
     *
     * @return array
     */
    public function getErrorMessages() : array
    {
        return $this->errorMessages;
    }

    /**
     * @return MessageBag
     */
    public function getMessageBag() : MessageBag
    {
        return new MessageBag($this->getErrorMessages());
    }

    /**
     * Http status code.
     *
     * @return int
     */
    public function getHttpStatusCode() : int
    {
        return $this->httpStatusCode;
    }

    /**
     * Parse a string or integer property, making sure it's set and not an empty string
     *
     * @param $property
     * @param $cast
     * @return string|int|null
     */
    protected function parseProperty($property, $cast = null)
    {
        if (isset($this->getResponse()->{$property}) === false || $this->getResponse()->{$property} === '') {
            return null;
        }
        if ($cast === 'int') {
            return (int) $this->getResponse()->{$property};
        }
        if ($cast === 'float') {
            return (float) $this->getResponse()->{$property};
        }
        return $this->getResponse()->{$property};
    }

    protected function parseResponse() {}
}