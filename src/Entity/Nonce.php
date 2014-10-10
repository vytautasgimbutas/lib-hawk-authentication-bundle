<?php

namespace Tornado\Bundle\HawkAuthenticationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nonce
 */
abstract class Nonce
{
    protected $id;

    /**
     * @var string
     */
    protected $nonce;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nonce
     *
     * @param string $nonce
     * @return Nonce
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Nonce
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
