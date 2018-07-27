<?php

namespace App;
use Log;

class Indodax {

    
    private $key,
            $secret,
            $url,
            $curl;
    

    
    /**
     * Get all Indodax API configuration and initialize curl
     */
    public function __construct() {
        $this->key    = config('indodax.api_key');
        $this->secret = config('indodax.secret_key');
        $this->url    = config('indodax.private_api_url');
        $this->curl   = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; INDODAXCOM PHP client;' . php_uname('s') . '; PHP/' . phpversion() . ')');
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    }
    
    
    
    /**
     * Build request which will be sent to the API
     * @param String    $method     API method
     * @param Array     $data       API parameters to be sent
     */
    private function buildRequest($method, $data = []) {
        $data = [
            'method' => $method,
            'nonce'  => time()
        ];
        $data = http_build_query($data, '', '&');
        $headers = [
            'Sign: ' . hash_hmac('sha512', $data, $this->secret),
            'Key: '  . $this->key,
        ];
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
    }
    
    
    
    /**
     * Get response from builded query
     * @return StdClass     Response from Indodax API or null if failed
     */
    private function getResponse() {
        $response = null;
        try {
            curl_setopt($this->curl, CURLOPT_URL, $this->url);
            $res = curl_exec($this->curl);
            $res !== false ?: Log::warning('Could not get reply: ' . curl_error($this->curl));
            
            $response = json_decode($res);
            $response ?: Log::warning('Invalid data received, please make sure connection is working and requested API exists: ' . $res);
            
            curl_close($this->curl);
        } catch (Throwable $e) {
            Log::warning('an error occured while get response from indodax: '.$e->getMessage());
        }
        return $response;
    }
    
    
    
    /**
     * Get personal info of registered API account
     * @return StdClass     Personal Info
     */
    public static function info() {
        $self = new self;
        $self->buildRequest('getInfo');
        return $self->getResponse();
    }
    

    
    /**
     * Get personal transaction history of registered API account
     * @return StdClass             Transaction history
     */
    public static function transhistory() {
        $self = new self;        
        $self->buildRequest('transHistory');
        return $self->getResponse();
    }
    
    
    /**
     * Get personal order history of both order buy and sell.
     * @param String    $pair       Pair to get the information from ex: btc_idr, ltc_btc, doge_btc, etc
     * @param Integer   $count      Total requested record count
     * @param Integer   $from       Start index of order history
     * @return StdClass             Order History
     */
    public static function orderHistory($pair = 'btc_idr', $count = 100, $from = 0) {
        $self = new self;
        $self->buildRequest('orderHistory', compact('pair','count','from'));
        return $self->getResponse();
    }
    
    
    /**
     * Get personal specific order details
     * @param Integer   $order_id   Order Id
     * @param String    $pair       Pair to get the information from ex: btc_idr, ltc_btc, doge_btc, etc
     * @return StdClass             Order details
     */
    public static function orderDetails($order_id, $pair = 'btc_idr') {
        $self = new self;
        $self->buildRequest('getOrder', compact('pair','order_id'));
        return $self->getResponse();
    }
    
    
    
    /**
     * Set request to get last price of BTC/IDR
     * @return StdClass             Last Price of BTC/wIDR
     */
    public static function lastBtcIdrPrice() {
        $self = new self;
        $self->url = config('indodax.btc_idr_ticker_url');
        return $self->getResponse();
    }
    
    
}



