<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Controller\WorkingTime;

use App\WorkingTime\Application\Command\WorkingTime\CreateCommand;
use App\WorkingTime\Application\Controller\AbstractController;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class CreateController extends AbstractController
{
    public const string SUCCESS_MESSAGE = 'Czas pracy został dodany!';

    public function __construct(
        MessageBusInterface $messageBus,
        private SerializerInterface $serializer,
        ValidatorInterface $validator,
    ) {
        parent::__construct($messageBus, $validator);
    }

    #[Route('/api/v1/working-time', name: 'create_working_time', methods: [Request::METHOD_POST])]
    public function __invoke(Request $request): JsonResponse
    {
        $command = $this->serializer->deserialize(
            data: $request->getContent(),
            type: CreateCommand::class,
            format: 'json',
        );

        $response = $this->handle($command);
        if ($response instanceof JsonResponse) {
            return $response;
        }

        return new JsonResponse(
            ['message' => self::SUCCESS_MESSAGE],
            JsonResponse::HTTP_CREATED,
        );
    }
}
