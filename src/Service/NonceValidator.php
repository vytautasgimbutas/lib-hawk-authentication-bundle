<?php

namespace Tornado\Bundle\HawkAuthenticationBundle\Service;

use Dflydev\Hawk\Nonce\NonceValidatorInterface;
use Dflydev\Hawk\Time\TimeProviderInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * NonceValidator
 *
 * @author Vytautas Gimbutas <vytautas@gimbutas.net>
 * @package Tornado\Bundle\HawkAuthenticationBundle\Service
 */
class NonceValidator implements NonceValidatorInterface
{
    protected $timeProvider;
    protected $connection;
    protected $tableName;

    public function __construct(TimeProviderInterface $timeProvider, Connection $connection, $tableName)
    {
        $this->timeProvider = $timeProvider;
        $this->connection = $connection;
        $this->tableName = $tableName;
    }

    public function validateNonce($nonce, $timestamp)
    {
        if ($this->connection->isTransactionActive()) {
            throw new AuthenticationException('Nonce can not be validated inside a database transaction');
        }

        try {
            $this->connection->insert($this->tableName, array(
                'nonce'      => sha1($nonce),
                'created_at' => date('Y-m-d H:i:s', $this->timeProvider->createTimestamp()),
            ));
            return true;
        } catch (DBALException $exception) {
            return false;
        }
    }
}
