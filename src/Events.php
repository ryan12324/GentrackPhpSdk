<?php
namespace ryan12324\GentrackPhpSdk;

use ryan12324\GentrackPhpSdk\Exceptions\EventsException;

class Events
{

    private $privateKey;

    public function __construct($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * Takes the below params and verifies
     * if the message is valid or not
     *
     * @param string $signature Value of the header `X-Payload-Signature` from the request
     * @param string $payload Entire body from the request
     * @return bool
     * @throws EventsException
     */
    public function verifySignature($signature, $payload) {
        if(empty($signature)) {
            throw new EventsException('Empty signature', $signature);
        }

        $parts = explode(',',$signature);
        if(count($parts) !== 2) {
            throw new EventsException('Invalid signature format', $signature);
        }

        list($timestamp, $digest) = $parts;
        if(substr($timestamp, 0, 2) !== 't='){
            throw new EventsException('Invalid timestamp', $signature);
        }

        $t = (int) substr($timestamp,2, count($timestamp));
        if(is_nan($t)) {
            throw new EventsException('Invalid timestamp', $signature);
        }

        $now = time();
        if(strtotime('+10 Minutes', $t)  >= $now) {
            throw new EventsException('Timestamp validation error', $signature);
        }

        $verificationHash = openssl_encrypt($payload, 'SHA512', $this->privateKey);
        if($verificationHash !== substr($digest,2, count($digest))) {
            throw new EventsException('Invalid signature', $signature);
        }

        return true;
    }
}