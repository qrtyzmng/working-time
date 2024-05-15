<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Controller\Employee;

use App\WorkingTime\Application\Command\Employee\CreateCommand;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class CreateController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
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
        $validationErrors = $this->validator->validate($command);
        if (\count($validationErrors) > 0) {
            return new JsonResponse(
                ['error' => (string) $validationErrors],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $this->messageBus->dispatch($command);

        return new JsonResponse(
            ['uuid' => $command->uuid],
            JsonResponse::HTTP_CREATED
        );
    }
}
