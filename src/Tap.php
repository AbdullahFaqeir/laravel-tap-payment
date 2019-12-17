<?php


namespace Rywan\LaravelTapPayment;
/**
 *
 */
/**
 *
 */

interface Tap
{

  public function setCustomer(array $info,$notification = true);

  public function setItems(array $items);

  public function makeCharge();

  public function getCharge($charge_id);

  public function chargesList();

  public function makeRefund($charge_id);

  public function getRefund($refund_id);


  public function refundList();
}
