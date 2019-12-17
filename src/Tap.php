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

  public function setProducts(array $products);

  public function makeCharge(array $data);

  public function getCharge($charge_id);

  public function chargesList();

  public function makeRefund($charge_id);

  public function getRefund($refund_id);

  public function getChargeRefund($charge_id);

  public function refundList();
}
