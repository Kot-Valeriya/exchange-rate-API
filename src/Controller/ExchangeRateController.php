<?php

namespace App\Controller;

use App\Entity\ExchangeRate;
use App\Repository\ExchangeRateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRateController extends AbstractController {

	public function response(array $responseArray) {

		return $this->json(array_map(function (ExchangeRate $exchangeRate) {
			return $this->get('serializer')->serialize($exchangeRate, 'json'));
		}, $responseArray));
	}

	/**
	 * @Route("all/{currency}", name="get_exchange_rates", methods={"GET"})
	 * @param ExchangeRateRepository $exchangeRateRepository
	 * @param null $currency
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function getExchangeRatesAction(ExchangeRateRepository $exchangeRateRepository, $currency = null) {
		$searchCriteria = $currency ? ['currency' => $currency] : [];

		return $this->response($exchangeRateRepository->findBy($searchCriteria, ['date' => 'DESC']));
	}

}
