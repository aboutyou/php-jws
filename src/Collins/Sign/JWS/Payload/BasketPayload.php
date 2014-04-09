<?php
/**
 * Created by PhpStorm.
 * User: georg
 * Date: 18.02.14
 * Time: 16:50
 */

namespace Collins\Sign\JWS\Payload;


class BasketPayload extends Payload
{
    protected $basket_id;
    protected $user_token;
    protected $app_token;

    protected $flow = 'basket';

    public function __construct($basket_id, $app_token, $user_token)
    {
        $this->app_token = $app_token;
        $this->basket_id = $basket_id;
        $this->user_token = $user_token;
    }

    public function jsonSerialize()
    {
        return parent::jsonSerialize();
    }

} 