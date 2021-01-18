<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchageRateControllerTest extends WebTestCase {
	/**
	 * @dataProvider provideUrls
	 */
	public function testVisitingWhileLoggedOut() {

		$client = static::createClient();

		$client->request('GET', 'currencies/EUR');
		$finishedData = json_decode($client->getResponse()->getContent(true), true);

		$this->assertEquals(401, $client->getResponse()->getStatusCode());

		$this->assertArrayHasKey('message', $finishedData);
	}

	public function provideUrls() {
		return [
			['/currencies/EUR'],
			['/currencies'],
			['/archive'],
		];
	}
}
