<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Controller\Employee;

use App\WorkingTime\Application\Command\Employee\CreateCommand;
use App\WorkingTime\Application\Controller\AbstractController;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class CreateController extends AbstractController
{
    public function __construct(
        MessageBusInterface $messageBus,
        ValidatorInterface $validator,
        private SerializerInterface $serializer,
    ) {
        parent::__construct($messageBus, $validator);
    }

    #[Route('/api/v1/employee', name: 'create_employee', methods: [Request::METHOD_POST])]
    public function __invoke(Request $request): JsonResponse
    {
        $command = $this->serializer->deserialize(
            data: $request->getContent(),
            type: CreateCommand::class,
            format: 'json',
        );
        $command->uuid = Uuid::v4()->toRfc4122();

        $response = $this->handle($command);
        if ($response instanceof JsonResponse) {
            return $response;
        }

        return new JsonResponse(
            ['uuid' => $command->uuid],
            JsonResponse::HTTP_CREATED
        );
    }
}
