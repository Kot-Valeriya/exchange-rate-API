<?php

namespace App\Repository;

use App\Entity\ExchangeRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExchangeRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeRate[]    findAll()
 * @method ExchangeRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRateRepository extends ServiceEntityRepository {

	public function __construct(ManagerRegistry $registry) {
		parent::__construct($registry, ExchangeRate::class);
	}

	public function findOneBySomeField($startDateString, $endDateString, $currency = null) {
		///getting criterias or assigning to default
		$startDate = \DateTime::createFromFormat('d.m.Y', $startDateString);
		$endDate = \DateTime::createFromFormat('d.m.Y', $endDateString);
		$startDate = $startDate ? $startDate : new \DateTime('1 month ago');
		$endDate = $endDate ? $endDate : new \DateTime('now');

		$query = $this->createQueryBuilder('rate')
			->where('rate.date >= :startDate')
			->andWhere('rate.date <= :endDate')
			->setParameter('startDate', $startDate)
			->setParameter('endDate', $endDate);

		if ($currency) {
			$query->andWhere('rate.currency = :currency')
				->setParameter('currency', $currency);
		}
		return $query->orderBy('rate.date', 'DESC')
			->getQuery()
			->getResult();
	}

}
