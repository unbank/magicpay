<?php

namespace MagicPay\API\V2;

use MagicPay\API\Traits\Endpoint;


class Customer {

    use Endpoint;


    /**
     * Create a customer on MagicPay
     *
     * @see https://docs.magicpaysecure.com/api/v2#tag/customers/paths/~1customers/post
     *
     * @param string $identifier    Something that identifies the customer, e.g. the customer's name or company.
     * @param string $first_name
     * @param string $last_name
     * @param boolean $active
     * @param array $billing_info
     * @param array $data
     * @return array
     */
    public function create(
        string $identifier,
        string $first_name,
        string $last_name,
        bool $active=true,
        array $billing_info=[],
        array $data=null
    ) {

        if ( empty($data) ) {
            $data = [];
        }

        $data["identifier"] = $identifier;
        $data["first_name"] = $first_name;
        $data["last_name"] = $last_name;
        $data["active"] = $active;
        $data["billing_info"] = $billing_info;

        $url = ($this->endpoint->url()).'/customers';
        return $this->submitRequest($url, $data);
    }



    /**
     * Update a customer
     *
     * @see https://docs.magicpaysecure.com/api/v2#tag/customers/paths/~1customers~1{id}/patch
     *
     * @param int $magicpay_id  The customer ID.
     * @param array $data
     * @return array
     */
    public function update(
        $magicpay_id,
        array $data=[]
    ) {
        $url = ($this->endpoint->url())."/customers/$magicpay_id";
        return $this->submitRequest($url, $data, "PATCH");
    }


    /**
     * Get a single customer from MagicPay
     *
     * @see https://docs.magicpaysecure.com/api/v2#tag/customers/paths/~1customers~1{id}/get
     *
     * @param array $params     key:value pair for order, limit, offset and active request params.
     * @return array    Returns an array of customers
     */
    public function get($magicpay_id) {
        $url = ($this->endpoint->url())."/customers/$magicpay_id";
        return $this->getRequest($url);
    }


    /**
     * Get multiple customers from MagicPay
     *
     * @see https://docs.magicpaysecure.com/api/v2#tag/customers/paths/~1customers/get
     *
     * @param array $params     key:value pair for order, limit, offset and active request params.
     * @return array    Returns an array of customers
     */
    public function query($params=[]) {
        $url = ($this->endpoint->url()).'/customers';
        return $this->getRequest($url);
    }


    /**
     * Delete a customer on MagicPay
     *
     * @see https://docs.magicpaysecure.com/api/v2#tag/customers/paths/~1customers~1{id}/delete
     *
     * @param int $magicpay_id  The customer ID.
     * @return mixed
     */
    public function delete($magicpay_id) {
        $url = ($this->endpoint->url())."/customers/$magicpay_id";
        return $this->delRequest($url);
    }

}


?>
