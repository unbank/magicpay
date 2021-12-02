<?php

namespace MagicPay\API\Traits;


trait Request {


    /**
     * Send API request
     *
     * @uses Mitek::auth        Get the authentication token from the response.
     * @param [type] $url       API Endpoint
     * @param [type] $postData  HTTP Post Data
     * @return array            Returns Mitek JSON repsonse as an array.
     */
    protected function submitRequest($url, $postData) {

        if ( is_array($postData) ) {
            $postData = json_encode($postData);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$postData,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic '.$this->endpoint->getToken()
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

}

?>
