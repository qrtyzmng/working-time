<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Controller\WorkingTime;

use App\WorkingTime\Application\Query\WorkingTime\SummaryQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class SummaryController
{
    public function __construct(private MessageBusInterface $queryBus, private ValidatorInterface $validator)
    {
    }

    #[Route('/api/v1/working-time/summary', name: 'summary_working_time', methods: [Request::METHOD_GET])]
    public function __invoke(Request $request): JsonResponse
    {
        $query = new SummaryQuery();
        $query->employeeUuid = $request->get('employeeUuid');
        $query->date = $request->get('date');

        $validationErrors = $this->validator->validate($query);
        if (\count($validationErrors) > 0) {
            return new JsonResponse(
                ['error' => (string) $validationErrors],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        try {
            $envelope = $this->queryBus->dispatch($query);
            /** @var HandledStamp $handled */
            $handled = $envelope->last(HandledStamp::class);
            $summary = $handled->getResult();
        } catch (HandlerFailedException $e) {
            return new JsonResponse(
                ['error' => $e->getPrevious()?->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST,
            );
        }

        return new JsonResponse(
            [$summary],
            JsonResponse::HTTP_OK,
        );
    }
}
