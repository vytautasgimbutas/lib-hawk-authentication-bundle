<?php

namespace Tornado\Bundle\HawkAuthenticationBundle\Security\Http\EntryPoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * HawkEntryPoint
 *
 * @author Vytautas Gimbutas <vytautas@gimbutas.net>
 */
class HawkEntryPoint implements AuthenticationEntryPointInterface
{
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response('', 401);
    }
}
