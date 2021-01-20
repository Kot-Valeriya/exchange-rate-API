<?php

//
declare (strict_types = 1);

namespace App\GraphQL\Resolver;

use Doctrine\ORM\EntityManager;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class ExchangeRateResolver implements ResolverInterface, AliasedInterface {

	private $em;

	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	public function resolve(Argument $args) {

		$rate = $this->em->getRepository('App:ExchangeRate')->findOneBy(['currency' => $args['currency']]);

		return $rate;
	}

	public static function getAliases(): array{
		return [
			'resolve' => 'ExchangeRate',
		];
	}
}