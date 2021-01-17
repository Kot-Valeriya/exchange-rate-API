<?php

namespace App\Command;

use App\ExchangeRateHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateExchangeRatesCommand extends Command {

	use LockableTrait;

	protected static $defaultName = 'app:update-exchange-rates';
	private $entityManager;
	private $helper;

	public function __construct(EntityManagerInterface $entityManager, ExchangeRateHelper $helper) {
		parent::__construct();
		$this->entityManager = $entityManager;
		$this->helper = $helper;
	}

	protected function configure() {
		$this
			->setDescription('Updates exchange rates.')
			->setHelp('This command allows you to update exchange rates.')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int {

		if (!$this->lock()) {
			$output->writeln('The command is already running in another process.');

			return Command::SUCCESS;
		}

		$io = new SymfonyStyle($input, $output);
		$date = new \DateTime('now');
		$exchangeRatesArray = $this->helper->getExchangeRatesArr($date->format('Y-m-d'));
		foreach ($exchangeRatesArray as $exchangeRate) {
			$this->entityManager->persist($exchangeRate);
		}
		$this->entityManager->flush();

		$io->success('Exchange rates updated successfully');
		$this->release();
		return Command::SUCCESS;
	}
}
