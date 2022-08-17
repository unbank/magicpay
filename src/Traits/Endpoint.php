<?php

namespace MagicPay\API\Traits;

use MagicPay\API\V2\MagicPay;

trait Endpoint {

    use Request;

    protected $endpoint;

    /**
     * Creates a new authorization / charge. For credit cards, by default, the authorization will be captured into the current batch.
     * @param \MagicPay\API\V2\Endpoint $endpoint
     */
    public function __construct(MagicPay $endpoint) {
        $this->endpoint = $endpoint;
    }

}


?>
