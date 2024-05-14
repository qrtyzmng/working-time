<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Controller\WorkingTime;

use App\WorkingTime\Application\Command\WorkingTime\CreateCommand;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

readonly class CreateController
{
    public const SUCCESS_MESSAGE = 'Czas pracy został dodany!';

    public function __construct(
        private MessageBusInterface $messageBus,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route('/api/v1/working-time', name: 'create_working_time', methods: [Request::METHOD_POST])]
    public function __invoke(Request $request): JsonResponse
    {
        $command = $this->serializer->deserialize(
            data: $request->getContent(),
            type: CreateCommand::class,
            format: 'json',
        );

        try {
            $this->messageBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            return new JsonResponse(
                ['error' => $e->getPrevious()?->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST,
            );
        }

        return new JsonResponse(
            ['message' => self::SUCCESS_MESSAGE],
            JsonResponse::HTTP_CREATED,
        );
    }
}
