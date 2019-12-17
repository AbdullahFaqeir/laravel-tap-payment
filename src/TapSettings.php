<?php


namespace Rywan\LaravelTapPayment;


class TapSettings
{
  protected $Charge_url = "https://api.tap.company/v2/charges";
  protected $Refund_url = "https://api.tap.company/v2/refunds";
  protected $Customer;
  protected $Source = array();
  protected $Items = array();
  protected $Amount = 0;
  protected $Currencies = array();
  protected $PostUrl = array();
  protected $RedirectUrl = array();
  //
  protected $tap_secret_api_Key;
  protected $tap_publishable_api_Key;
  protected $tap_live_mode;
  protected $tap_acceptable_currencies;
  protected $tap_currency;
  protected $tap_lang;
  protected $tap_error_url;






  public function __construct(){
    $this->tap_secret_api_Key = config('tappayment.tap-secret-api-Key');
    $this->tap_publishable_api_Key = config('tappayment.tap-publishable-api-Key');
    $this->tap_live_mode = config('tappayment.tap-live-mode');
    $this->tap_acceptable_currencies = config('tappayment.tap-acceptable-currencies');
    $this->tap_lang = config('tappayment.tap-lang');
    $this->tap_error_url = config('tappayment.tap-error-url');

  }

}
