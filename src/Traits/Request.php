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
        $httpClass = '\Illuminate\Support\Facades\Http';
        if ( ! class_exists($httpClass) ) {
            return $this->psr7Request($url, $postData, $method);
        }

        $response = $httpClass::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.$this->endpoint->getToken()
        ])->send($method, $url, [
            'json' => $postData,
        ]);
        return $response->json();
    }




    /**
     * Send Post API request
     *
     * @uses Mitek::auth        Get the authentication token from the response.
     * @param string $url       API Endpoint
     * @param mixed $postData  HTTP Post Data
     * @return mixed            Returns Mitek JSON repsonse as an array.
     */
    protected function psr7Request($url, $postData, $method="POST") {

        if ( is_array($postData) ) {
            $postData = json_encode($postData);
        }

        $client = new Client();
        $headers = array_merge([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.$this->endpoint->getToken()
        ]);
        $request = new Psr7Request($method, $url, $headers, $postData);
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
