<?php

declare(strict_types=1);

namespace Tests\Functional\Controller\WorkingTime;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\FunctionalTestCase;

final class SummaryControllerTest extends FunctionalTestCase
{
    const string EMPLOYEE_EXISTING_UUID = '90de9dbe-007a-42e1-83a6-e342d26a88cc';
    const string URI = '/api/v1/working-time/summary';

    /**
     * @test
     */
    public function it_returns_http_bad_request_on_non_existing_uuid(): void
    {
        $this->client->request(
            method: Request::METHOD_GET,
            uri: self::URI . '?employeeUuid=2ea30e1b-0cfb-44d1-b661-645215a53901&date=2024-12-12',
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $content = $response->getContent();
        $responseContents = json_decode($content, true);
        $this->assertSame('Employee not found', $responseContents['error']);
    }

    /**
     * @test
     */
    public function it_returns_http_bad_request_on_missing_employees_uuid(): void
    {
        $this->client->request(
            method: Request::METHOD_GET,
            uri: self::URI . '?date=2024-12-12',
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $content = $response->getContent();
        $responseContents = json_decode($content, true);
        $this->assertStringContainsString('This value should not be blank', $responseContents['error']);
    }
}
