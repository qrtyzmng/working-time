<?php

declare(strict_types=1);

namespace Tests\Functional\Controller\WorkingTime;

use App\WorkingTime\Application\Controller\WorkingTime\CreateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\FunctionalTestCase;

final class CreateControllerTest extends FunctionalTestCase
{
    const string EMPLOYEE_EXISTING_UUID = '90de9dbe-007a-42e1-83a6-e342d26a88cc';
    const string URI = '/api/v1/working-time';

    /**
     * @test
     */
    public function it_returns_http_created(): void
    {
        $this->client->request(
            method: Request::METHOD_POST,
            uri: self::URI,
            content: json_encode([
                'employeeUuid' => self::EMPLOYEE_EXISTING_UUID,
                'startDateTime' => '2024-12-10T13:20:00P',
                'endDateTime' => '2024-12-10T15:20:00P',
            ]),
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $content = $response->getContent();
        $responseContents = json_decode($content, true);
        $this->assertSame(CreateController::SUCCESS_MESSAGE, $responseContents['message']);
    }

    /**
     * @test
     */
    public function it_returns_http_bad_request_on_non_existing_uuid(): void
    {
        $this->client->request(
            method: Request::METHOD_POST,
            uri: self::URI,
            content: json_encode([
                'employeeUuid' => '531f398f-bda7-488e-97da-907a473f43b9',
                'startDateTime' => '2024-12-12T13:20:00P',
                'endDateTime' => '2024-12-12T15:20:00P',
            ]),
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
    public function it_returns_http_bad_request_on_data_range_exceeding(): void
    {
        $this->client->request(
            method: Request::METHOD_POST,
            uri: self::URI,
            content: json_encode([
                'employeeUuid' => self::EMPLOYEE_EXISTING_UUID,
                'startDateTime' => '2024-12-12T07:20:00P',
                'endDateTime' => '2024-12-12T21:20:00P',
            ]),
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $content = $response->getContent();
        $responseContents = json_decode($content, true);
        $this->assertSame('Data range exceeds 12 hours', $responseContents['error']);
    }

    /**
     * @test
     */
    public function it_returns_http_bad_request_on_duplicated_day(): void
    {
        $content = json_encode([
            'employeeUuid' => self::EMPLOYEE_EXISTING_UUID,
            'startDateTime' => '2024-12-19T10:20:00P',
            'endDateTime' => '2024-12-19T21:20:00P',
        ]);
        $this->client->request(
            method: Request::METHOD_POST,
            uri: self::URI,
            content: $content,
        );

        $this->client->request(
            method: Request::METHOD_POST,
            uri: self::URI,
            content: $content,
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $content = $response->getContent();
        $responseContents = json_decode($content, true);
        $this->assertSame('Start date already exists for this date', $responseContents['error']);
    }

    /**
     * @test
     */
    public function it_returns_http_bad_request_on_missing_employees_uuid(): void
    {
        $this->client->request(
            method: Request::METHOD_POST,
            uri: self::URI,
            content: json_encode([
                'startDateTime' => '2024-12-12T13:20:00P',
                'endDateTime' => '2024-12-12T15:20:00P',
            ]),
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $content = $response->getContent();
        $responseContents = json_decode($content, true);
        $this->assertStringContainsString('This value should not be blank', $responseContents['error']);
    }
}
