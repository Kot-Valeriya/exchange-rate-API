<?php

namespace App\DataFixtures;

use App\ExchangeRateHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RatesFixture extends Fixture {
	public function load(ObjectManager $manager) {
		$ratesArray = ExchangeRateHelper::create()->getExchangeRates();
		foreach ($exchangeRatesArray as $exchangeRate) {
			$manager->persist($exchangeRate);
		}
		$manager->flush();
	}
}
