<?php
namespace Collins\Sign\JWS\Payload;


use Collins\Sign\JWS\SignServiceConfig;

abstract class Payload implements \JsonSerializable
{

    protected $app_id;
    protected $info;
    protected $salt;
    protected $flow;

    public function init(SignServiceConfig $config, $salt)
    {
        $this->info = $config->getInfo();
        $this->app_id = $config->getAppId();
        $this->salt = $salt;
    }

    public function jsonSerialize()
    {
        if ($this->checkRequiredProperties()) {
            $return = array();
            $properties = get_object_vars($this);
            foreach ($properties as $key => $value) {
                $return[$key] = $value;
            }
            return $return;
        }
        throw new \Exception('not all required properties are set');
    }

    protected function checkRequiredProperties()
    {
        $properties = get_object_vars($this);
        foreach ($properties as $value) {
            if (!isset($value)) {
                return false;
            }
        }
        return true;
    }

}
