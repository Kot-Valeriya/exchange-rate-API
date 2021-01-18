<?php

namespace App;

use App\Entity\ExchangeRate;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateHelper {

	private $client;
	private $endpoint;

	const CURRENCY_NAMES = [
		'EUR' => 'European Euro',
		'GBP' => 'British Pound',
		'PLN' => 'Polish zloty',
		'USD' => 'U.S. Dollar',
		'BYN' => 'Belarusian ruble',
		'RUB' => 'Russian ruble',
		'DKK' => 'Danish krone',
		'JPY' => 'Japanese yen',
		'AUD' => 'Australian dollar',
		'CAD' => 'Canadian dollar',
		'CZK' => 'Czech koruna',
		'CHF' => 'Swiss franc',
	];

	public function __construct(HttpClientInterface $client, string $exchangeRateUrl) {
		$this->client = $client;
		$this->endpoint = $exchangeRateUrl;
	}

	public function getAllExchangeRates(): array
	{

		$response = $this->client->request(
			'GET',
			$this->endpoint
		);

		return $response->toArray();
	}

	public function getExchangeRates($inputDate, $valcode = null): array
	{
		$date = date('Ymd', strtotime($inputDate));

		//if date specified - get rates for needed date
		//if valcode specified - get rates on date in YYYYMMDD format on currency, the currency code is letter from the currency classifier, the register does not matter
		$response = $this->client->request(
			'GET',
			$this->endpoint . ($valcode ?
				'?valcode=' . $valcode . '&date=' . $date . '&json' :
				'?date=' . $date . '&json')
		);

		return $response->toArray();
	}

	//filtering array to get keys of exchange rates for only specified currencies
	private function getCurrencyKeys($content): array
	{
		$keys = array();
		foreach (self::CURRENCY_NAMES as $key => $val) {
			$keys[] = array_search($key, array_column($content, 'cc'));
		}
		return $keys;
	}

	//returning array of objects ExchangeRate
	public function getExchangeRatesArr($date): array
	{
		//getting rates for specified date
		$content = $this->getExchangeRates($date);

		//filtering array by specified currencies
		$filteredResponseArr =
			array_intersect_key(
			$content,
			array_flip($this->getCurrencyKeys($content))
		);

		$exchangeRateArray = [];
		foreach ($filteredResponseArr as $rate) {
			$exchangeRate = new ExchangeRate();
			$exchangeRateArray[] =
			$exchangeRate
				->setDate(\DateTime::createFromFormat('d.m.Y', $rate['exchangedate'], new \DateTimeZone('Europe/Kiev')))
				->setCurrency($rate['cc'])
				->setAmount($rate['rate']);
		}

		return $exchangeRateArray;
	}
}