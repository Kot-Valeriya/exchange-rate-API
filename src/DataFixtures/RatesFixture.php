<?php

namespace App\DataFixtures;

use App\ExchangeRateHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RatesFixture extends Fixture {

	private $helper;

	public function __construct(ExchangeRateHelper $helper, string $exchangeRateUrl) {
		$this->helper = $helper;
		$this->endpoint = $exchangeRateUrl;
	}

	public function load(ObjectManager $manager) {
		$startDate = new \DateTime('1 month ago');
		$endDate = new \DateTime('now');
		$interval = \DateInterval::createFromDateString('1 day');
		$period = new \DatePeriod($startDate, $interval, $endDate);

		foreach ($period as $date) {
			$date = $date->format('Y-m-d');
			$exchangeRatesArray = $this->helper->getExchangeRatesArr($date);
			foreach ($exchangeRatesArray as $exchangeRate) {

				$manager->persist($exchangeRate);
			}
			$manager->flush();
		}
	}
}
