<?php
/**
 * Created by PhpStorm.
 * User: georg
 * Date: 18.02.14
 * Time: 17:04
 */

namespace Collins\Sign\JWS\Payload;


class AuthPayload extends Payload
{
    protected $redirect_uri;
    protected $scope;
    protected $state;
    protected $popup;
    protected $flow = 'auth';
    protected $response_type = 'code';

    function __construct($redirect_uri, $scope, $popup, $state)
    {
        $this->redirect_uri = $redirect_uri;
        $this->scope = $scope;
        $this->popup = $popup;
        $this->state = $state;
    }
} 