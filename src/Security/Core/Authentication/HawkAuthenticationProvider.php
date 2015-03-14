<?php

namespace Tornado\Bundle\HawkAuthenticationBundle\Security\Core\Authentication;

use Dflydev\Hawk\Server\ServerInterface;
use Dflydev\Hawk\Server\UnauthorizedException;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Tornado\Bundle\HawkAuthenticationBundle\Security\Core\Authentication\Token\HawkToken;

/**
 * HawkAuthenticationProvider
 *
 * @author Vytautas Gimbutas <vytautas@gimbutas.net>
 * @package Tornado\Bundle\HawkAuthenticationBundle\Security\Core\Authentication
 */
class HawkAuthenticationProvider implements AuthenticationProviderInterface
{
    protected $hawkServer;
    protected $userProvider;
    protected $userChecker;

    public function __construct(ServerInterface $hawkServer, UserProviderInterface $userProvider, UserCheckerInterface $userChecker)
    {
        $this->hawkServer = $hawkServer;
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
    }

    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param TokenInterface $token The TokenInterface instance to authenticate
     *
     * @return TokenInterface An authenticated TokenInterface instance, never null
     *
     * @throws AuthenticationException if the authentication fails
     */
    public function authenticate(TokenInterface $token)
    {
        if (!$token instanceof HawkToken) {
            throw new \InvalidArgumentException('Provided token is not HawkToken');
        }

        try {
            $user = $this->userProvider->loadUserByUsername($token->getId());

            $this->userChecker->checkPreAuth($user);

            $this->hawkServer->authenticate(
                $token->getMethod(),
                $token->getHost(),
                $token->getPort(),
                $token->getUri(),
                $token->getContentType(),
                $token->getPayload(),
                $token->getAuthorizationHeader()
            );

            $this->userChecker->checkPostAuth($user);

            $authenticatedToken = new HawkToken($user->getRoles());
            $authenticatedToken->copy($token);

            $authenticatedToken->setAuthenticated(true);
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        } catch (UnauthorizedException $exception) {
            throw new AuthenticationException('Invalid Hawk authentication data');
        }
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return bool    true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof HawkToken;
    }
}
