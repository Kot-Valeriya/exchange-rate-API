<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class TestGetExchangeRatesCommandTest extends KernelTestCase {
	public function testExecute() {
		$kernel = static::createKernel();
		$application = new Application($kernel);
		$command = $application->find('app:update-exchange-rates');
		$commandTester = new CommandTester($command);

		$commandTester->execute([
			'command' => $command->getName(),
		]);
		$output = $commandTester->getDisplay();

		$this->assertStringContainsString('Exchange rates updated successfully', $output);
	}
}