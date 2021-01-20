<?php

namespace App\GraphQL\Resolver;

use Doctrine\ORM\EntityManager;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class ExchangeRatesListResolver implements ResolverInterface, AliasedInterface {
	private $em;
	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	public function resolve(Argument $args) {

		$startDateStr = \DateTime::createFromFormat('d.m.Y', $args['startDate']);
		$endDateStr = \DateTime::createFromFormat('d.m.Y', $args['endDate']);
		$startDate = $startDateStr ? $startDateStr : new \DateTime('1 month ago');
		$endDate = $endDateStr ? $endDateStr : new \DateTime('now');

		$query = $this->em->getRepository('App:ExchangeRate')->createQueryBuilder('rate')
			->where('rate.date >= :startDate')
			->andWhere('rate.date <= :endDate')
			->setParameter('startDate', $startDate)
			->setParameter('endDate', $endDate);

		if ($args['currency']) {
			$query->andWhere('rate.currency = :currency')
				->setParameter('currency', $args['currency']);
		}
		$exchangeRates = $query->orderBy('rate.date', 'DESC')
			->getQuery()
			->getResult();

		return ['exchangeRates' => $exchangeRates];
	}

	public static function getAliases(): array{
		return [
			'resolve' => 'ExchangeRatesList',
		];
	}
}