<?php 

namespace DOGECModule;

use GuzzleHttp\Client;
use Exception;
use DateTime;

class DOGECModule{

    const EXPLORER_URL = "https://explorer.dogec.io/api/v2/";
    
    //
    // Look in the explorer if a newer transaction than $timestap in ms (int) with $amount (Float) exists for $address (varchar)
    //
    public function existsTransaction($address, $amount, $timestamp)
    {

    }

    public function checkConfirmations($txid)
    {

    }

}
