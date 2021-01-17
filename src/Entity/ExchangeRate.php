<?php

namespace App\Entity;

use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExchangeRateRepository::class)
 */
class ExchangeRate {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $date;

	/**
	 * @ORM\Column(type="string", length=10)
	 */
	private $currency;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $amount;

	public function getId():  ? int {
		return $this->id;
	}

	public function getDate() :  ? \DateTimeInterface {
		return $this->date;
	}

	public function setDate(\DateTimeInterface $date) : self{
		$this->date = $date;

		return $this;
	}

	public function getCurrency():  ? string {
		return $this->currency;
	}

	public function setCurrency(string $currency) : self{
		$this->currency = $currency;

		return $this;
	}

	public function getAmount():  ? string {
		return $this->amount;
	}

	public function setAmount(string $amount) : self{
		$this->amount = $amount;

		return $this;
	}

}
