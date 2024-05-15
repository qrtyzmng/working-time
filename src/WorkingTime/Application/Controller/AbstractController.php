<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Controller;

use App\WorkingTime\Application\Command\CommandInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract readonly class AbstractController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function handle(CommandInterface $command): ?JsonResponse
    {
        $validationErrors = $this->validator->validate($command);
        if (\count($validationErrors) > 0) {
            return new JsonResponse(
                ['error' => (string) $validationErrors],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        try {
            $this->messageBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            return new JsonResponse(
                ['error' => $e->getPrevious()?->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST,
            );
        }

        return null;
    }
}
