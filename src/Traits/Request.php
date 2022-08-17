<?php

namespace MagicPay\API\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;

trait Request {


    /**
     * Send Post API request
     *
     * @uses Mitek::auth        Get the authentication token from the response.
     * @param string $url       API Endpoint
     * @param mixed $postData  HTTP Post Data
     * @return mixed            Returns Mitek JSON repsonse as an array.
     */
    protected function submitRequest($url, $postData, $method="POST") {

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
          CURLOPT_CUSTOMREQUEST => $method,
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



    /**
     * Send Get API request
     *
     * @uses Mitek::auth        Get the authentication token from the response.
     * @param string $url       API Endpoint
     * @return mixed            Returns Mitek JSON repsonse as an array.
     */
    protected function getRequest($url, $params=[], $method="GET", $headers=[]) {

        if ( !empty($params) ) {
            $url = "$url?".http_build_query($params);
        }

        $client = new Client();
        $headers = array_merge([
            'Authorization' => 'Basic '.$this->endpoint->getToken()
        ]);
        $request = new Psr7Request($method, $url, $headers);
        $res = $client->sendAsync($request)->wait();
        $content = $res->getBody()->getContents();
        if ( empty($content) ) {
            return [];
        }
        return json_decode($content, true);
    }





    /**
     * Send Get API request
     *
     * @uses Mitek::auth        Get the authentication token from the response.
     * @param string $url       API Endpoint
     * @return mixed            Returns Mitek JSON repsonse as an array.
     */
    protected function delRequest($url, $params=[]) {
        return $this->getRequest($url, $params, "delete");
    }

}

?>
