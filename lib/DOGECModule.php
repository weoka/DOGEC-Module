<?php 

namespace DOGECModule;

use GuzzleHttp\Client;

class DOGECModule{

    const EXPLORER_URL = "https://explorer.dogec.io/api/v2/";
    
    public function __construct()
    {
        $this->client = new client();
    }

    //
    // Look in the explorer if a newer transaction than $timestap in ms (int) with $amount (Float) exists for $address (varchar)
    //
    public function existsTransaction($address, $amount, $timestamp)
    {
        try{
            $transactions = $this->getAddressTransactions($address);

            foreach($transactions as $transaction)
            {
                $transaction = $this->getTransaction($transaction);
                if($transaction['time'] < $timestamp)
                {
                    //transaction doesn't exist
                    return [
                        'exists' => false,
                        'txid' => ""
                    ];
                }

                foreach($transaction['vout'] as $vout)
                {
                    if($vout['value'] == $amount && $vout['scriptPubKey']['addresses'][0] == $address)
                    {
                        return [
                            'exists' => true,
                            'txid' => $transaction;
                        ]
                    }
                }
            }

        }
        catch(\Throwable $e)
        {
            throw $e;
        }
        
    }

    //
    // Check how many confirmations does $txid have (varchar)
    //
    public function checkConfirmations($txid)
    {
        try{
            $transaction = $this->getTransaction($txid);
            return $transaction['confirmations'];
        }
        catch (\Throwable $e){
            throw $e;
        }
    }

    //
    //Get transactions from address
    //
    public function getAddressTransactions($address)
    {
        try{
            $addressEndpoint = EXPLORER_URL . "/address/$address"; 
            
            $transactions = $this->client('GET', $addressEndpoint);

            //convert response into array
            $transactions_array = (json_decode($response->getBody()->getContents(), true))['transactions'];

            return $transactions_array;
        }
        catch (\Throwable $e){
            throw $e;
        }
    }

    //
    //Get trasnsaction
    //
    public function getTransaction($txid)
    {
        try{
            $transactionEndpoint = EXPLORER_URL . "/tx/$txid"; 
            
            $transaction = $this->client('GET', $transactionEndpoint);

            //convert response into array
            $transaction = json_decode($response->getBody()->getContents(), true);

            return $transaction;
        }
        catch (\Throwable $e){
            throw $e;
        }
    }

}
