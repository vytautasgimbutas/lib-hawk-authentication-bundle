<?php

namespace Tornado\Bundle\HawkAuthenticationBundle\Service;

use Dflydev\Hawk\Credentials\Credentials;
use Dflydev\Hawk\Credentials\CredentialsProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * UserProviderCredentialsProviderAdapter
 *
 * @author Vytautas Gimbutas <vytautas@gimbutas.net>
 * @package Tornado\Bundle\HawkAuthenticationBundle\Service
 */
class UserProviderCredentialsProviderAdapter implements CredentialsProviderInterface
{
    protected $userProvider;
    protected $cryptographyAlgorithm;

    public function __construct(UserProviderInterface $userProvider, $cryptographyAlgorithm = 'sha256')
    {
        $this->userProvider = $userProvider;
        $this->cryptographyAlgorithm = $cryptographyAlgorithm;
    }

    /**
     * @param mixed $id
     *
     * @return Credentials
     *
     * @throws UsernameNotFoundException
     */
    public function loadCredentialsById($id)
    {
        $user = $this->userProvider->loadUserByUsername($id);

        return new Credentials($user->getPassword(), $this->cryptographyAlgorithm, $user->getUsername());
    }
}
