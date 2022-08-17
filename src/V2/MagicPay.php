<?php

namespace MagicPay\API\V2;

class MagicPay {

    protected $production = 'https://api.magicpaysecure.com/api/v2';
    protected $sandbox = 'https://api.sandbox.magicpaysecure.com/api/v2';
    protected $url;
    protected $token;
    protected $services = [];

    public function __construct($sandbox=false)
    {
        $this->url = ( $sandbox )? $this->sandbox : $this->production;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return mixed
     */
    public function setBase64Token($token) {
        $this->token = $token;
        return $this;
    }

    /**
     * Set token via key:pin pair
     *
     * @param string $source_key
     * @param string $pin
     * @return mixed
     */
    public function setKeyPairToken($source_key, $pin) {
        $this->token = \base64_encode("$source_key:$pin");
        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function url() {
        return $this->url;
    }

    /**
     * Get sandbox url
     *
     * @return string
     */
    public function getSandboxUrlAttribute() {
        return $this->sandbox;
    }


    /**
     * Get production url
     *
     * @return string
     */
    public function getProductionAttribute() {
        return $this->production;
    }

    /**
     * Get service
     *
     * @param string $service_name
     * @param mixed $service_class
     * @return mixed
     */
    protected function __service(string $service_name, $service_class) {
        if ( !empty($this->services[$service_name])) {
            return $this->services[$service_name];
        }
        $endpoint = $this;
        return $this->services[$service_name] = new $service_class($endpoint);
    }

    /**
     * Get the Charge service endpoint
     *
     * @return \MagicPay\API\V2\Charge
     */
    public function charge() {
        return $this->__service('charge', Charge::class);
    }

    /**
     * Get the Customer service endpoint
     *
     * @return \MagicPay\API\V2\Customer
     */
    public function customers() {
        return $this->__service('customer', Customer::class);
    }

    /**
     * Get the Transaction service endpoint
     *
     * @return \MagicPay\API\V2\Transaction
     */
    public function transactions() {
        return $this->__service('transaction', Transaction::class);
    }

}

?>
