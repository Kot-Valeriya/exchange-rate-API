<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class UserFixture extends Fixture {

	public static function getGroups(): array
	{
		return ['authorizationGroup'];
	}

	public function load(ObjectManager $manager) {
		$user = new User();
		$user
			->setEmail('admin@admin.com')
			->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

		$user->setApiToken(new PostAuthenticationGuardToken($user, 'main', array('ROLE_ADMIN')));

		$manager->persist($user);
		$manager->flush();
	}
}
