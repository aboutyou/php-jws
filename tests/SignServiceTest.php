<?php
/**
 * Created by PhpStorm.
 * User: georg
 * Date: 17.02.14
 * Time: 23:09
 */



use Collins\Sign\JWS\SignService;

class SignServiceTest extends PHPUnit_Framework_TestCase
{

    public function testSignedRequestWithSameSecretSucceeds()
    {
        $config = new \Collins\Sign\JWS\SignServiceConfig(999,'lsdj2}ßßäoAA$$$2356as','singService Test');
        $service = new SignService($config);
        $payload = new \Collins\Sign\JWS\Payload\BasketPayload('adfadf','test','test');

        $signed = $service->sign($payload);
        $stdClass = $service->getPayload($signed);
        $this->assertNotEquals(false,$stdClass);

        $stdClassArray = (array)$stdClass;
        $payloadArray = (array)json_decode(json_encode($payload));

        foreach($stdClassArray as $key => $value){
            $this->assertEquals($payloadArray[$key],$value);
        }

    }

    /**
     * @expectedException Exception
     */
    public function testSignedRequestFails()
    {
        $config = new \Collins\Sign\JWS\SignServiceConfig(999,'lsdj2}ßßäoAA$$$2356as','singService Test');
        $service = new SignService($config);
        $payload = new \Collins\Sign\JWS\Payload\BasketPayload('adfadf','test','test');

        $signed = $service->sign($payload);
        $signed = $signed.'change';
        $service->getPayload($signed);

    }
}
