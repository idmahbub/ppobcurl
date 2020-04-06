<?php

namespace Idmahbub\PPOB\Providers;

use GuzzleHttp\Client;
use Idmahbub\PPOB\Products\Pulsa;
use Idmahbub\PPOB\AbstractProvider;
use Idmahbub\PPOB\Contracts\Product;
use Idmahbub\PPOB\Products\TokenPLN;
use Idmahbub\PPOB\Products\PaketData;
use Idmahbub\PPOB\Products\TopupRequest;

class MobilePulsa extends AbstractProvider
{
    protected $commands = [
        'balance' => 'balance',
        'topup' => 'topup',
        'status' => 'inquiry',
        'pricelist' => 'pricelist',
    ];

    protected $prefix = [
        Pulsa::class => [
            'telkomsel' => 'htelkomsel',
            'indosat' => 'hindosat',
            'xl' => 'xld',
            'axis' => 'haxis',
            'three' => 'hthree',
            'smart' => 'hsmart',
        ],
        PaketData::class => [
            'telkomsel' => 'tseldata',
            'indosat' => 'isatdata',
            'xl' => 'xldata',
            'axis' => 'axisdata',
            'three' => 'threedata',
            'smartfren' => 'smartdataVOL',
            'bolt' => 'hbolt',
        ]
    ];

    private $username;

    private $apikey;

    private $production = false;

    public function __construct($username, $apikey, $production = false, Client $client = null)
    {
        parent::__construct($client);

        $this->username = $username;
        $this->apikey = $apikey;
        $this->production = $production;
    }

    public function topup(Product $product, $refId)
    {
        return $this->send($this->signedTopup([
            'hp' => $product->subscriberId(),
            'ref_id' => $refId,
            'pulsa_code' => $this->getCode($product)
        ]));
    }

    public function balance()
    {
        return $this->send($this->signedBalance([
            'ref_id' => 'bl'
        ]));
    }

    public function pricelist()
    {
        return $this->send($this->signedPricelist([
            'ref_id' => 'pl'
        ]));
    }

    public function status($refId)
    {
        return $this->send($this->signedStatus([
            'ref_id' => $refId,
        ]));
    }

    public function codePulsa(Pulsa $product)
    {
        return $this->prefix[Pulsa::class][$product->operator()] . $product->nominal();
    }

    public function codePaketData(PaketData $product)
    {
        return $this->prefix[PaketData::class][$product->operator()] . $product->nominal();
    }

    public function codeTokenPLN(TokenPLN $pln)
    {
        return 'hpln' . $pln->nominal();
    }

    protected function signRequest($command, $data = [])
    {
        return array_merge($data, [
            'commands' => $this->commands[$command],
            'username' => $this->username,
            'sign' => md5($this->username . $this->apikey . $data['ref_id'])
        ]);
    }

    protected function send($data)
    {
        $response = $this->client->request('POST', $this->endpoint(), [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($data)
        ]);

        return $this->buildResult($response);
    }

    protected function endpoint()
    {
        return $this->production ?
            'https://api.mobilepulsa.net/v1/legacy/index' :
            'https://testprepaid.mobilepulsa.net/v1/legacy/index';
    }

    public function __call($method, $arguments)
    {
        if (strpos($method, 'signed') !== 0) {
            throw new \Exception('Method not exist');
        }

        $command = strtolower(str_replace('signed', '', $method));

        return $this->signRequest($command, ...$arguments);
    }
}
