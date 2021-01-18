<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchageRateControllerTest extends WebTestCase {
	/**
	 * @dataProvider provideUrls
	 */
	public function testVisitingWhileLoggedOut($url) {

		$client = static::createClient();

		$client->request('GET', $url);
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

	/**
	 * @dataProvider provideUrls
	 */
	public function testVisitingWhileLoggedIn($url) {
		$client = static::createClient();
		$userRepository = static::$container->get(UserRepository::class);
		// retrieve the test user
		$testUser = $userRepository->findOneByEmail('admin@admin.com');
		// simulate $testUser being logged in
		$client->loginUser($testUser);

		$client->request('GET', $url);

		$this->assertResponseIsSuccessful();
	}
}
