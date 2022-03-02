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
class Transaction
{
    use Request;

    protected $endpoint;

    /**
     * Creates a new authorization / charge. For credit cards, by default, the authorization will be captured into the current batch.
     * @param \MagicPay\API\V2\Endpoint $endpoint
     */
    public function __construct(MagicPay $endpoint) {
        $this->endpoint = $endpoint;
    }


    /**
     * Get multiple transactions
     *
     * @see https://docs.magicpaysecure.com/api/v2#tag/transactions
     *
     * @param array $params
     * @return array
     */
    public function query(array $params=[]) {
        $query_str = http_build_query($params);
        $url = ($this->endpoint->url())."/transactions?$query_str";
        return $this->getRequest($url);
    }


    /**
     * Query date from
     *
     * @param integer $date_from        The UNIX epoch, which is an integer value
     *      representing the number of milliseconds since January 1, 1970, 00:00:00 UTC.
     * @param integer $date_to        The UNIX epoch, which is an integer value
     *      representing the number of milliseconds since January 1, 1970, 00:00:00 UTC.
     * @return array
     */
    public function queryByDate(int $date_from, int $date_to=null) {
        $params = ['date_from' => $date_from];
        if ( !empty($date_to) ) {
            $params['date_to'] = $date_to;
        }
        return $this->query($params);
    }


    /**
     * Get single transaction
     *
     * @see https://docs.magicpaysecure.com/api/v2#tag/transactions/paths/~1transactions~1{id}/get
     *
     * @param array $params
     * @return array
     */
    public function get($id) {
        $url = ($this->endpoint->url())."/transactions/$id";
        return $this->getRequest($url);
    }

}
