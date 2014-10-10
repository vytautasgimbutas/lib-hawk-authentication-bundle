<?php

namespace Tornado\Bundle\HawkAuthenticationBundle\Security\Core\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * HawkToken
 *
 * @author  Vytautas Gimbutas <vytautas@gimbutas.net>
 * @package Tornado\Bundle\HawkAuthenticationBundle\Security\Core\Authentication\Token
 */
class HawkToken extends AbstractToken
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $nonce;

    /**
     * @var string
     */
    protected $mac;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var string
     */
    protected $payload;

    /**
     * @var string
     */
    protected $authorizationHeader;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return HawkToken
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get mac
     *
     * @return string
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * Set mac
     *
     * @param string $mac
     *
     * @return HawkToken
     */
    public function setMac($mac)
    {
        $this->mac = $mac;

        return $this;
    }

    /**
     * Get nonce
     *
     * @return string
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * Set nonce
     *
     * @param string $nonce
     *
     * @return HawkToken
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;

        return $this;
    }

    /**
     * Get authorizationHeader
     *
     * @return string
     */
    public function getAuthorizationHeader()
    {
        return $this->authorizationHeader;
    }

    /**
     * Set authorizationHeader
     *
     * @param string $authorizationHeader
     *
     * @return HawkToken
     */
    public function setAuthorizationHeader($authorizationHeader)
    {
        $this->authorizationHeader = $authorizationHeader;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     *
     * @return HawkToken
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set host
     *
     * @param string $host
     *
     * @return HawkToken
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set method
     *
     * @param string $method
     *
     * @return HawkToken
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get payload
     *
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set payload
     *
     * @param string $payload
     *
     * @return HawkToken
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Get port
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set port
     *
     * @param int $port
     *
     * @return HawkToken
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set timestamp
     *
     * @param int $timestamp
     *
     * @return HawkToken
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set uri
     *
     * @param string $uri
     *
     * @return HawkToken
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Returns the user credentials.
     *
     * @return mixed The user credentials
     */
    public function getCredentials()
    {
        return '';
    }

    public function copy(HawkToken $token)
    {
        $this
            ->setId($token->getId())
            ->setTimestamp($token->getTimestamp())
            ->setNonce($token->getNonce())
            ->setMac($token->getMac())
            ->setMethod($token->getMethod())
            ->setHost($token->getHost())
            ->setPort($token->getPort())
            ->setUri($token->getUri())
            ->setContentType($token->getContentType())
            ->setPayload($token->getPayload())
            ->setAuthorizationHeader($token->getAuthorizationHeader())
        ;

        $this->setAttributes($token->getAttributes());
    }
}
