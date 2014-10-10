<?php

namespace Tornado\Bundle\HawkAuthenticationBundle\Security\Http\Firewall;

use Dflydev\Hawk\Header\HeaderParser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Tornado\Bundle\HawkAuthenticationBundle\Security\Core\Authentication\Token\HawkToken;

/**
 * HawkListener
 *
 * @author Vytautas Gimbutas <vytautas@gimbutas.net>
 * @package Tornado\Bundle\HawkAuthenticationBundle\Security\Http\Firewall
 */
class HawkListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;

    /**
     * @var HeaderParser
     */
    protected $hawkHeaderParser;

    public function __construct(
        SecurityContextInterface $securityContext,
        AuthenticationManagerInterface $authenticationManager,
        HeaderParser $hawkHeaderParser
    ) {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->hawkHeaderParser = $hawkHeaderParser;
    }

    /**
     * This interface must be implemented by firewall listeners.
     *
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $authorizationHeader = $request->headers->get('Authorization');

        if (strpos($authorizationHeader, 'Hawk') !== 0) {
            return;
        }

        $attributes = $this->hawkHeaderParser->parseFieldValue($authorizationHeader);
        $unauthenticatedToken = new HawkToken();

        $unauthenticatedToken
            ->setId($attributes['id'])
            ->setTimestamp($attributes['ts'])
            ->setNonce($attributes['nonce'])
            ->setMac($attributes['mac'])
            ->setMethod($request->getMethod())
            ->setHost($request->getHost())
            ->setPort($request->getPort())
            ->setUri($request->getRequestUri())
            ->setContentType($request->headers->get('Content-Type'))
            ->setPayload($request->getContent() ?: null)
            ->setAuthorizationHeader($authorizationHeader)
        ;

        try {
            $authenticatedToken = $this->authenticationManager->authenticate($unauthenticatedToken);

            $this->securityContext->setToken($authenticatedToken);
        } catch (AuthenticationException $exception) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            $event->setResponse($response);
        }
    }
}
