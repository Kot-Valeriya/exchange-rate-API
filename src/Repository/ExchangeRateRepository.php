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

	// /**
	//  * @return ExchangeRate[] Returns an array of ExchangeRate objects
	//  */
	/*
	    public function findByExampleField($value)
	    {
	        return $this->createQueryBuilder('e')
	            ->andWhere('e.exampleField = :val')
	            ->setParameter('val', $value)
	            ->orderBy('e.id', 'ASC')
	            ->setMaxResults(10)
	            ->getQuery()
	            ->getResult()
	        ;
	    }
*/

	public function findOneBySomeField($startDate, $endDate, $currency = null) {

		$query = $this->createQueryBuilder('rate')
			->where('rate.date >= :startDate')
			->andWhere('rate.date <= :endDate')
			->setParameter('startDate', $startDate)
			->setParameter('endDate', $endDate);

		if ($currency) {
			$query->andWhere('exchangeRate.currency = :currency')
				->setParameter('currency', $currency);
		}
		return $query->orderBy('exchangeRate.date', 'DESC')
			->getQuery()
			->getResult();
	}

}
