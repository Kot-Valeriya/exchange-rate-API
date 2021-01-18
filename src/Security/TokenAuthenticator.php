<?php
namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator {
	private $em;

	public function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

	/**
	 * Called to decide if this authenticator should be
	 * used for the request.
	 */
	public function supports(Request $request) {
		return $request->headers->has('X-AUTH-TOKEN');
	}

	/**
	 * Called on every request. Return credentials
	 * for getUser() as $credentials.
	 */
	public function getCredentials(Request $request) {
		return $request->headers->get('X-AUTH-TOKEN');
	}

	public function getUser($credentials, UserProviderInterface $userProvider) {
		if (null === $credentials) {
			// The token header was empty, authentication fails with HTTP Status
			// Code 401 "Unauthorized"
			return null;
		}

		return $userProvider->loadUserByUsername($credentials);
	}

	public function checkCredentials($credentials, UserInterface $user) {
		// API token - so no credential check is needed.
		return true;
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
		return null; //allowed to continue
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
		return new JsonResponse([
			'message' => 'failure',
		], 401);
	}

	/**
	 * Called when authentication is needed, but it's not sent
	 */
	public function start(Request $request, AuthenticationException $authException = null) {
		$data = [

			'message' => 'Authentication Required',
		];

		return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
	}

	public function supportsRememberMe() {
		return false;
	}
}