<?php

namespace App\Http\Controllers\Response;

class Response
{
    public $_status;
    public $_error_message;
    public $_body;

    function __construct($status = null, $error_message = null, $body = null){
        $this->_status = $status;
        $this->_error_message = $error_message;
        $this->_body = $body;
    }

    public function setResponse($status = null, $error_message = null, $body = null)
    {
        $this->_status = $status;
        $this->_error_message = $error_message;
        $this->_body = $body;
    }

    public function getResponse()
    {
        $header = ['status' => $this->_status, 'error_message' => $this->_error_message];
        return json_encode(['HEADER' => $header, 'BODY' => $this->_body]);
    }
}
