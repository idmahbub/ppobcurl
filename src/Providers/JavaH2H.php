<?php

namespace Idmahbub\PPOB\Providers;

use GuzzleHttp\Client;
use Idmahbub\PPOB\Products\Pulsa;
use Idmahbub\PPOB\AbstractProvider;
use Idmahbub\PPOB\Contracts\Product;
use Illuminate\Support\Str;

class JavaH2H extends AbstractProvider
{
    protected $endpoint = 'https://javah2h.com/api/connect/';

    protected $prefix = [
        Pulsa::class => [
            'telkomsel' => 'S',
            'indosat' => 'I',
            'xl' => 'X',
            'axis' => 'AX',
            'three' => 'T',
            'smartfren' => 'SM',
        ]
    ];

    private $username;

    private $apikey;

    private $secret;


    public function __construct($username, $apikey, $secret, Client $client = null)
    {
        parent::__construct($client);

        $this->username = $username;
        $this->apikey = $apikey;
        $this->secret = $secret;
    }

    public function topup(Product $product, $refId)
    {
        return $this->send([
            'inquiry' => 'I',
            'code' => $this->getCode($product),
            'phone' => $product->subscriberId(),
            'trxid_api' => $refId,
            'no' => '1'
        ]);
    }

	public function balance()
	{
		return $this->send([ 'inquiry' => 'S' ]);
	}

	public function prabayar($prov=null)
	{
		if (!empty($prov)){
			return $this->send(['inquiry' => 'HARGA','code' => $prov]);
		}else {
			return $this->send(['inquiry' => 'HARGA']);
		}
		
	}

    public function status($refId)
    {
        return $this->send([
            'inquiry' => 'STATUS',
            'trxid_api' => $refId
        ]);
    }

    public function pricelist($param)
    {
        return $this->send([
            'inquiry' => 'HARGA',
            'code' => $param['type'] ?? 'pulsa'
        ]);
    }

    public function codePulsa(Pulsa $product)
    {
        return $this->prefix[Pulsa::class][$product->operator()] . Str::substr($product->nominal(), 0, -3);
    }

    protected function send($data)
    {
        $response = $this->client->request('POST', $this->endpoint, [
            'headers' => [
                'h2h-userid' => $this->username,
                'h2h-key' => $this->apikey,
                'h2h-secret' => $this->secret,
            ],
            'form_params' => $data
        ]);

        return $this->buildResult($response);
    }
}
