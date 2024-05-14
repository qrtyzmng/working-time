<?php

declare(strict_types=1);

namespace Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

class FunctionalTestCase extends WebTestCase
{
    protected HttpKernelBrowser $client;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }
}
