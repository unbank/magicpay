<?php

namespace MagicPay\API\V2;

use App\Helpers\AppHelper;
use MagicPay\API\Traits\Request;

/**
 * Creates a new authorization / charge. For credit cards, by default, the authorization will be captured into the current batch.
 *
 * A charge can be from one of 4 different types of sources:
 *
 * Credit Card: This requires passing fields with credit card data.
 * Credit Card Magstripe: This requires passing magstripe data from a swiped credit card.
 * Check / ACH
 * Source: This includes charging based on a previous transaction, a stored payment method, a token, or a nonce.
 * For check transactions, this request requires the Check Charge permission on the source key. For CC transactions, if capture is false, it requires the Auth Only permission, otherwise, it requires the Charge permission.
 *
 */
class Charge
{

    use Request;

    protected $endpoint;
    protected $charge_url;
    protected $verify_url;

    /**
     * Creates a new authorization / charge. For credit cards, by default, the authorization will be captured into the current batch.
     * @param \MagicPay\API\V2\Endpoint $endpoint
     */
    public function __construct(MagicPay $endpoint) {
        $this->endpoint = $endpoint;
        $this->charge_url = ($this->endpoint->url()).'/transactions/charge';
        $this->verify_url = ($this->endpoint->url()).'/transactions/verify';
    }

    public function getChargeUrl() {
        return $this->charge_url;
    }

    public function getVerifyUrl() {
        return $this->verify_url;
    }

    public function charge(string $name, string $expiry_month, string $expiry_year, $cvv2='', array $data=[]) {
        $data['name'] = $name;
        $data['expiry_month'] = $expiry_month;
        $data['expiry_year'] = $expiry_year;
        $data['cvv2'] = $cvv2;
        $data['save_card'] = true;
        AppHelper::setArrayDefault($data, 'software', 'Unbank Web');
        return $this->submitRequest($this->charge_url, $data);
    }

    public function verify(string $name, string $card_number, string $expiry_month, string $expiry_year, $cvv2='', $save_card=true, $capture=true) {
        $ip = AppHelper::clientIP();
        $data = '{
            "amount": 2.00,
            "name": "'. $name .'",
            "transaction_details": {
                "description": "Card Verification",
                "client_ip": "'.$ip.'"
            },
            "software": "Unbank App",
            "expiry_month": '.$expiry_month.',
            "expiry_year": '.$expiry_year.',
            "cvv2": "'.$cvv2.'",
            "card": "'.$card_number.'",
            "capture": '. (( $capture )? 'true' : 'false')  .',
            "save_card": '. (( $save_card )? 'true' : 'false')  .'
        }
        ';
        return $this->submitRequest($this->verify_url, $data);
    }

}