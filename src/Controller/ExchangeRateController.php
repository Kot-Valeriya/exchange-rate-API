<?php

namespace App\Controller;

use App\Entity\ExchangeRate;
use App\ExchangeRateHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateController extends AbstractController {

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
	/**
	 * @Route("/exchange/rate", name="exchange_rate")
	 */
	public function index(ExchangeRateHelper $helper, HttpClientInterface $client): Response{
		$startDate = new \DateTime('1 month ago');
		$endDate = new \DateTime('now');
		$interval = \DateInterval::createFromDateString('1 day');
		$period = new \DatePeriod($startDate, $interval, $endDate);
		$exchangeRateArray1 = [];
		foreach ($period as $date) {
			$date = $date->format('Y-m-d');
			$filteredResponseArr =
				array_intersect_key(
				$this->getExchangeRates($date, $client),
				$this->getCurrencyKeys($this->getExchangeRates($date, $client)));

			$exchangeRateArray = [];
			foreach ($filteredResponseArr as $rate) {
//date('Ymd', strtotime($rate['exchangedate']))
				$exchangeRate = new ExchangeRate();
				$exchangeRateArray[] =
				$exchangeRate
					->setDate(\DateTime::createFromFormat('d.m.Y', $rate['exchangedate'], new \DateTimeZone('Europe/Kiev')))
					->setCurrency($rate['cc'])
					->setAmount($rate['rate']);
			}
			$exchangeRateArray1[] = $exchangeRateArray;
		}

		dd($exchangeRateArray1);
	}
	public function getExchangeRates($inputDate, HttpClientInterface $client, $valcode = 'EUR'): array
	{
		$exchangeRateUrl = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchangenew?json';
		$date = date('Ymd', strtotime($inputDate));

		$response = $client->request(
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

/**
 * @Route("/exchange", name="exchange")
 */
	public function EXCNAHGE(ExchangeRateHelper $helper, HttpClientInterface $client): Response{
		$date = '2020-12-15';
		$date1 = date('Ymd', strtotime($date));
		$valcode = null;
		$exchangeRateUrl = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchangenew?json';
		//dd($exchangeRateUrl . ($valcode ? '?valcode=' . $valcode . '&date=' . $date : '?date=' . $date));
		$response = $client->request(
			'GET',
			$exchangeRateUrl . ($valcode ? '?valcode=' . $valcode . '&date=' . $date : '?date=' . $date)
		);

		dd($response->toArray());
	}

}
