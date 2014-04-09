<?php
/**
 * Created by PhpStorm.
 * User: georg
 * Date: 18.02.14
 * Time: 18:28
 */

namespace Collins\Sign\JWS;


class SignServiceConfig
{

    protected $_appId;
    protected $_appSecret;
    protected $_info;

    public function __construct($appId, $appSecret, $info = '')
    {

        if (!is_string($appSecret)) {
            throw new \Exception('constuctor argument $info has be to a string');
        }
        if (!is_string($info)) {
            throw new \Exception('constuctor argument $info has be to a string');
        }

        $this->_appId = $appId;
        $this->_appSecret = $appSecret;
        $this->_info = $info;
    }

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->_appId;
    }

    /**
     * @return string
     */
    public function getAppSecret()
    {
        return $this->_appSecret;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this->_info;
    }

} 