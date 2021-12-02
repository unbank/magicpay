<?php

namespace MagicPay\API\V2;

use MagicPay\API\Traits\Request;

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

    public function setBase64Token($token) {
        $this->token = $token;
        return $this;
    }

    public function setKeyPairToken($source_key, $pin) {
        $this->token = \base64_encode("$source_key:$pin");
        return $this;
    }

    public function getToken() {
        return $this->token;
    }

    public function url() {
        return $this->url;
    }

    public function getSandboxUrlAttribute() {
        return $this->sandbox;
    }

    public function getProductionAttribute() {
        return $this->production;
    }

    public function charge() {
        if ( !empty($this->services['charge'])) {
            return $this->services['charge'];
        }
        $endpoint = $this;
        return $this->services['charge'] = new Charge($endpoint);
    }

}

?>
