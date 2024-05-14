<?php

declare(strict_types=1);

namespace Tests\Functional\Controller\Employee;

use App\WorkingTime\Domain\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\FunctionalTestCase;

final class CreateControllerTest extends FunctionalTestCase
{
    /**
     * @test
     */
    public function it_returns_http_created(): void
    {
        $this->client->request(
            method: Request::METHOD_POST,
            uri: '/api/v1/employee',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(['firstname' => 'Bob', 'lastname' => 'Doe']),
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertStringContainsString('uuid', $response->getContent());
        $responseContents = json_decode($content, true);
        $employee = $this->entityManager->find(Employee::class, $responseContents['uuid']);
        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertSame('Bob', $employee->getFirstname());
        $this->assertSame('Doe', $employee->getLastname());
    }
}
