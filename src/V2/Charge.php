<?php

namespace MagicPay\API\V2;

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

    public function charge(float $amount, string $name, string $card_number, int $expiry_month, int $expiry_year, string $cvv2='', array $data=[]) {
        $data['name'] = $name;
        $data['expiry_month'] = $expiry_month;
        $data['expiry_year'] = $expiry_year;
        $data['cvv2'] = $cvv2;
        $data['card'] = $card_number;
        $data['amount'] = $amount;
        return $this->submitRequest($this->charge_url, $data);
    }

    public function verify(
        string $name,
        string $card_number,
        int $expiry_month,
        int $expiry_year,
        string $cvv2='',
        bool $save_card=true,
        bool $capture=true,
        array $data=[]
    ) {
        $data['name'] = $name;
        $data['expiry_month'] = $expiry_month;
        $data['expiry_year'] = $expiry_year;
        $data['cvv2'] = $cvv2;
        $data['card'] = $card_number;
        $data['amount'] = number_format( random_int(1, 200) / 100, 2);
        $data['capture'] = $capture;
        $data['save_card'] = $save_card;

        if ( empty($data["transaction_details"]) ) {
            try {
                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } elseif( !empty($_SERVER['REMOTE_ADDR'])) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
                $ip = "0.0.0.0";
            } catch (\Throwable $th) {
                logger($th->getMessage());
            }
            $data["transaction_details"] = [
                "description" => "Card Verification",
                "client_ip" => $ip
            ];
        }
        return $this->submitRequest($this->verify_url, $data);
    }

}
