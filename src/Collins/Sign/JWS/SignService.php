<?php
namespace Collins\Sign\JWS;

use Akita_JOSE_JWS;
use Collins\Sign\JWS\Crypto\CSPRNG;
use Collins\Sign\JWS\Payload\Payload;

class SignService
{
    protected $_config;

    public function __construct(SignServiceConfig $config)
    {

        $this->_config = $config;
    }

    public function sign(Payload $data)
    {
        $salt = $this->createSalt();
        $data->init($this->_config, base64_encode($salt));

        $okm = $this->createORMToken($salt,$this->_config->getInfo());

        $jws = new Akita_JOSE_JWS('HS256');
        $jws->setPayload(json_encode($data));
        $jws->sign($okm,$this->_config->getInfo());

        return $jws->getTokenString();
    }

    public function getPayload($signedData)
    {

        if (empty($signedData)) {
            throw new \Exception('args $signedData can not be empty');
        };

        $jws = Akita_JOSE_JWS::load($signedData, true);
        if(!$jws) throw new \Exception('could not jws get create');
        $json = $jws->getPayload($signedData);
        if(!$jws) throw new \Exception('could not jws get payload');
        $payload = json_decode($json);
        if(is_null($payload)) throw new \Exception('could not jws get json decode');
        $okm = $this->createORMToken(base64_decode($payload->salt),$payload->info);

        if ($jws->verify($okm)) {
            return $payload;
        } else {
            throw new \Exception('could not jws get json decode');
        }
    }

    private function createSalt()
    {
        $csprng = new CSPRNG();
        $salt = $csprng->GetBytes(16);

        if (!$salt) {
            throw new \Exception('salt could not be generated');
        }

        return $salt;
    }

    private function createORMToken($salt,$info)
    {
        $okm = $this->hkdf(
            'sha256',
            $this->_config->getAppSecret(),
            32,
            $info,
            $salt
        );
        if (!$okm) {
            throw new \Exception('okm could not be generated');
        }
        return $okm;
    }

    //Create a new derived key for each request (different salt + app original secret)
    //from https://github.com/defuse/php-encryption/blob/master/Crypto.php
    private function hkdf($hash, $ikm, $length, $info = '', $salt = null)
    {

        $digest_length = strlen(hash_hmac($hash, '', '', true));

        // Sanity-check the desired output length.
        if (empty($length) || !is_int($length) ||
            $length < 0 || $length > 255 * $digest_length
        ) {
            return false;
        }

        // "if [salt] not provided, is set to a string of HashLen zeroes."
        if (is_null($salt)) {
            $salt = str_repeat("\x00", $digest_length);
        }

        // HKDF-Extract:
        // PRK = HMAC-Hash(salt, IKM)
        // The salt is the HMAC key.
        $prk = hash_hmac($hash, $ikm, $salt, true);

        // HKDF-Expand:

        // This check is useless, but it serves as a reminder to the spec.
        if (strlen($prk) < $digest_length) {
            return false;
        }

        // T(0) = ''
        $t = '';
        $last_block = '';
        for ($block_index = 1; strlen($t) < $length; $block_index++) {
            // T(i) = HMAC-Hash(PRK, T(i-1) | info | 0x??)
            $last_block = hash_hmac(
                $hash,
                $last_block . $info . chr($block_index),
                $prk,
                true
            );
            // T = T(1) | T(2) | T(3) | ... | T(N)
            $t .= $last_block;
        }

        // ORM = first L octets of T
        $orm = substr($t, 0, $length);
        return $orm;
    }

}