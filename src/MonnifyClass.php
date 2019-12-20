<?php

namespace Kenny\Payment;

class MonnifyClass
{
    private $userName;
    private $token;
    private $contract;
    private $bearerToken;
    private $url = "https://sandbox.monnify.com/api/v1";

    public function __construct($userName, $token, $contract)
    {
        $this->userName = $userName;
        $this->token = $token;
        $this->contract = $contract;
    }

    public function authenticator()
    {
        $header = [
            "Authorization: Basic " . $this->encodeLoginDetails()
        ];

        $response = $this->curlRequest($this->url."/auth/login", "POST", $header);
        if ((!empty($response)) && ($response->requestSuccessful)) {
            $this->bearerToken = $response->responseBody->accessToken;
            return $this;
        }
        return false;
    }

    private function encodeLoginDetails()
    {
        return base64_encode("$this->userName:$this->token");
    }

    private function curlRequest($endpoint, $method, $headers = [], $body = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    public function reserveAnAccount($body)
    {
        $header = [
            "Content-Type : application/json",
            "Authorization: Bearer ".$this->bearerToken,
        ];
        $response = $this->curlRequest($this->url."/bank-transfer/reserved-accounts", "POST", $header, $body);
        if (!empty($response)) {
            if ($response->requestSuccessful) {
                return $response->responseBody;
            }else{
                return $response;
            }
        }
        return false;
    }

    public function deallocateAccount($account)
    {
        $header = [
            "Content-Type : application/json",
            "Authorization: Bearer ".$this->bearerToken,
        ];

        $response = $this->curlRequest($this->url."/bank-transfer/reserved-accounts/" . $account, "DELETE", $header);
        if (!empty($response)) {
            if ($response->requestSuccessful) {
                return $response->responseBody;
            }else{
                return $response;
            }
        }
        return false;
    }

    public function transactionStatus($referenceCode){
        $header = [
            "Authorization: Bearer ".$this->bearerToken,
        ];
        $response = $this->curlRequest($this->url."/merchant/transactions/query?transactionReference=".$referenceCode, "GET", $header);
        if (!empty($response)) {
            if ($response->requestSuccessful) {
                return $response->responseBody;
            }else{
                return $response;
            }
        }
        return false;
    }
}