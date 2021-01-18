<?php

namespace App\Controller;

use App\Entity\ExchangeRate;
use App\ExchangeRateHelper;
use App\Repository\ExchangeRateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRateController extends AbstractController {

	/**
	 * @Route("currencies", name="get_all_currencies", methods={"GET"})
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function getCurrenciesAction() {
		$responseArray = [];
		foreach (ExchangeRateHelper::CURRENCY_NAMES as $key => $value) {
			$responseArray[$key] = [
				'abbreviation' => $key,
				'description' => $value,
			];
		}
		return $this->json($responseArray);
	}

	/**
	 * @Route("currencies/{currency}", name="get_exchange_rate", methods={"GET"})
	 * @param ExchangeRateRepository $exchangeRateRepository
	 * @param null $currency
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function getExchangeRatesAction(ExchangeRateRepository $exchangeRateRepository, $currency = null) {

		$searchCriteria = $currency ? ['currency' => $currency] : [];

		return $this->response($exchangeRateRepository->findBy($searchCriteria, ['date' => 'DESC']));
	}

	/**
	 * @Route("archive", name="get_archived_exchange_rates", methods={"GET"})
	 * @param ExchangeRateRepository $exchangeRateRepository
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function getExchangeRatesForDateAction(ExchangeRateRepository $exchangeRateRepository, Request $request) {
		//getting request parameters
		$criterias = $request->query->all();

		$startDate = $criterias['startDate'] ?? null;
		$endDate = $criterias['endDate'] ?? null;
		$currency = !array_key_exists('currency', $criterias) ? null : str_replace('"', '', $criterias['currency']);

		return $this->response($exchangeRateRepository->findOneBySomeField($startDate, $endDate, $currency));
	}

	public function response(array $responseArray) {

		return $this->json(array_map(function (ExchangeRate $exchangeRate) {

			return $exchangeRate->serializeExchangeRate();
		}, $responseArray));
	}
}
