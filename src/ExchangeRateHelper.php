<?php

namespace App;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateHelper {

	private $client;
	//private $endpoint;

	public function __construct(HttpClientInterface $client, string $exchangeRateUrl) {
		$this->client = $client;
		//$this->endpoint = $exchangeRateUrl;
	}

	const CURRENCY_NAMES = [
		'EUR' => 'Євро',
		'GBP' => 'Фунт стерлінгів',
		'PLN' => 'Злотий',
		'USD' => 'Долар США',
		'BYN' => 'Білоруський рубль',
		'RUB' => 'Російський рубль',
		'DKK' => 'Данська крона',
		'JPY' => 'Єна',
		'AUD' => 'Австралійський долар',
		'CAD' => 'Канадський долар',
		'CZK' => 'Чеська крона',
		'CHF' => 'Швейцарський франк',
	];

	private function getAllExchangeRates(): array
	{

		$response = $this->client->request(
			'GET',
			$exchangeRateUrl
		);

		return $response->toArray();
	}

	private function getExchangeRates($inputDate, $valcode = null): array
	{
		$date = date('Ymd', strtotime($inputDate));

		//if date specified - get rates for needed date
		//if valcode specified - get rates on date in YYYYMMDD format on currency, the currency code is letter from the currency classifier, the register does not matter
		$response = $this->client->request(
			'GET',
			$exchangeRateUrl . ($valcode ?
				'?valcode=' . $valcode . '&date=' . $date :
				'?date=' . $date)
		);

		return $response->toArray();
	}

	private function getCurrencyKeys($content): array
	{
		$keys = array();
		foreach (self::CURRENCY_NAMES as $key => $val) {
			$keys[] = array_search($key, array_column($content, 'CurrencyCodeL'));
		}
		return $keys;
	}

	protected function getExchangeRate(string $ratesString): array
	{
		$ratesArray =
			array_intersect_key(
			$this->getAllExchangeRates(),
			array_flip($this->getCurrencyKeys));

		foreach ($ratesArray as $rate) {
			$exchangeRate = new ExchangeRate();
			$exchangeRate->setDate(\DateTime::createFromFormat(
				'DD.MM.YY', $ratesArray['StartDate'],
				new \DateTimeZone('	Europe/Kiev')))
				->setCurrency($ratesArray['CurrencyCodeL'])
				->setAmount($ratesArray['Amount']);
		}

		return $ratesArray;
	}

}
