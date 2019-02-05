<?php

namespace Omnipay\AfterPay;

use Omnipay\Tests\GatewayTestCase;

/**
 * @property Gateway gateway
 */
class GatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\PayPal\ExpressGateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $options;


    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->options = array(
            'testMode' => true
        );

    }

    /** @test */
    public function configuration()
    {
        $request = $this->gateway->configuration();

        $this->assertInstanceOf('Omnipay\AfterPay\Message\ConfigurationRequest', $request);
    }

    /** @test */
    public function configurationRequest()
    {
        $this->setMockHttpResponse('ConfigurationSuccess.txt');

        $response = $this->gateway->configuration()->send();
        $contents = $response->getData();

        $expected = [
            "type" => "PAY_BY_INSTALLMENT",
            "description" => "Pay over time",
            "minimumAmount" => [
              "amount" => "0.00",
              "currency" => "NZD"
            ],
            "maximumAmount" => [
              "amount" => "1000.00",
              "currency" => "NZD"
            ]
        ];

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($expected, $contents[0]);
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\AfterPay\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testPurchaseReturn()
    {
        $request = $this->gateway->completePurchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\AfterPay\Message\CompletePurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }


}