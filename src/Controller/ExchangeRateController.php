<?php

namespace App\Controller;

use App\ExchangeRateHelper;
use App\Repository\ExchangeRateRepository;
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
		$response = $client->request(
			'GET',
			'https://bank.gov.ua/NBU_Exchange/exchange?json'
		);
		$content = $response->toArray();
		$keys = array();
		foreach (self::CURRENCY_NAMES as $key => $val) {
			$keys[] = array_search($key, array_column($content, 'CurrencyCodeL'));
		}

		$date = strtotime('2009-02-15');
		//$date_in = getDate($date);

		dd(date('Ymd', $date), array_intersect_key($content, array_flip($keys)));

		return $content;

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
	/*
		 * @Route("all/{currency}", name="get_all_exchange_rates", methods={"GET"})
	*/
	public function getExchangeRatesAction(ExchangeRateRepository $exchangeRateRepository, $currency = null) {
		$searchCriteria = $currency ? ['currency' => $currency] : [];
		return $this->response($exchangeRateRepository->findBy($searchCriteria, ['date' => 'DESC']));
	}

	public function response(array $responseArray) {
		return $this->json(array_map(function (ExchangeRate $exchangeRate): array{
			return $exchangeRate->toArray();
		}, $responseArray));
	}
}
