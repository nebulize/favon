<?php

namespace App\Http\Clients;


use Illuminate\Contracts\Support\MessageProvider;
use Illuminate\Support\MessageBag;

class Response implements MessageProvider
{
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
     * Construct.
     *
     * @param int $intHttpStatusCode Status code
     */
    public function __construct($intHttpStatusCode)
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

}