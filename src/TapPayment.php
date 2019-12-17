<?php


namespace Rywan\LaravelTapPayment;
use GuzzleHttp\Client;

use Rywan\LaravelTapPayment\Tap;
use Rywan\LaravelTapPayment\TapSettings;
/**
 *
 */
class TapPayment extends TapSettings implements Tap
{
  public function __construct(){
    parent::__construct();
  }

  public function setCustomer(array $info,$notification = true){
    $this->Customer = array();
    $Fields = ['first_name'=>'require','middle_name'=>'optional','last_name'=>'optional','email'=>'require','phone'=>'optional','country_code'=>'optional','number'=>'optional'];

    foreach ($info as $key => $value) {
      if (is_array($value)) {
        $this->Customer[$key] = array();
        foreach ($value as $k => $v) {
          if (!key_exists($key,$Fields)) {
            throw new \Exception("Error Processing Request", 1);
          }
          if ($value == null && $Fields[$k] == "require") {
           throw new \Exception("Error Processing Request", 1001);
          }

          array_push($this->Customer[$key],[$k=>$v]);
        }
      }else{
        if (!key_exists($key,$Fields)) {
          throw new \Exception("Error Processing Request", 1);
        }
        if ($value == null && $Fields[$key] == "require") {
           throw new \Exception("Error Processing Request", 1001);
        }

        $this->Customer[$key] = $value;
      }
    }

  }

  public function getCustomer(){
    return $this->Customer;
  }

  public function setSourceID($source_id){
    if (!in_array($source_id,['src_kw.knet','src_card'])) {
      throw new \Exception("error_source_id_not_correct");
    }
    $this->Source['id'] = $source_id;
  }


  public function  setPostUrl($url){
    $this->PostUrl['url'] = $url;
  }

  public function  setRedirectUrl($url){
    $this->RedirectUrl['url'] = $url;
  }



  public function setItems(array $items){
    $Filds = ['amount'=>'require','currency'=>'require','description'=>'require','discount'=>'optional','type'=>'optional','value'=>'optional'];
    foreach ($items as $item) {
      foreach ($Filds as $key => $value) {
         if (!key_exists($key,$item) && $value== "require" || key_exists($key,$item) && $value== "require" && $item[$key] == null) {
           throw new \Exception("error_miss_item_require_field");
         }
      }

      if (!is_numeric($item['amount'])) {
         throw new \Exception("error_item_amount_must_be_numeric");
      }

      if (!in_array($item['currency'],$this->tap_acceptable_currencies)) {
         throw new \Exception("error_unacceptable_item_currency");
      }

      if (!in_array($item['currency'],$this->Currencies)) {
        array_push($this->Currencies,$item['currency']);
      }
      $this->Amount += $item['amount'];
      ;
      if (count($this->Currencies) > 1) {
        throw new \Exception("error_many_item_currencies");
      }

      $this->Currency = $this->Currencies[0];
    }
  }

  public function makeCharge(){
    $response__ = new \stdClass();
    $client = new Client();
    $charge_data = array();
    $charge_data['amount'] = $this->Amount;
    $charge_data['currency'] = $this->Currency;
    $charge_data['customer'] = $this->Customer;
    $charge_data['source'] = $this->Source;
    $charge_data['post'] = $this->PostUrl;
    $charge_data['redirect'] = $this->RedirectUrl;

    $response = $client->request('POST', $this->Charge_url,
      [
          'body'        => json_encode($charge_data),
          'headers' => [
              'content-type' => 'application/json',
              "Authorization" => "Bearer ".$this->tap_secret_api_Key,
              'Accept' => 'application/json',
          ]
      ]);

    $response__->isSuccess = false;

    if ($response->getStatusCode() == "200") {

      $data = json_decode($response->getBody(),true);
      if (in_array('id',$data) && in_array('object',$data) && in_array('method',$data) && in_array('threeDSecure',$data)) {
        if (in_array($data['status'],['INITIATED','CAPTURED'])) {
          $response__ = (object) $data;
          $response__->isSuccess = true;

        }

      }



    }
    return $response__;
  }

  public function getCharge($charge_id){

  }

  public function chargesList(){

  }

  public function makeRefund($charge_id){

  }

  public function getRefund($refund_id){
    $url = $this->$Refund_url."/".$refund_id;
  }


  public function refundList(){
    $url = $this->$Refund_url."/list";
  }

}
