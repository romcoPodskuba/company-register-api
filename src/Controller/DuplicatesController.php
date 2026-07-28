<?php

namespace App\Controller;

use App\Service\DuplicatesService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/duplicates', name: 'duplicates_')]
class DuplicatesController extends AbstractController
{
    public function __construct(
        private readonly DuplicatesService $duplicatesService
    ) {}

    #[OA\Get(
        summary: 'Get duplicates',
        description: 'Returns all rows whose value appears more than once.',
        tags: ['Duplicates'],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Duplicates list retrieved successfully.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        required: ['id', 'value'],
                        properties: [
                            new OA\Property(
                                property: 'id',
                                type: 'integer',
                                example: 1
                            ),
                            new OA\Property(
                                property: 'value',
                                type: 'integer',
                                example: 1
                            )
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Database is not initialized or duplicates table is empty (fixtures not loaded).',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'integer', example: 404),
                        new OA\Property(
                            property: 'detail',
                            type: 'string',
                            example: 'Database is not initialized. Please run make db-init or check the database.'
                        ),
                    ]
                )
            )
        ]
    )]
    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json($this->duplicatesService->list());
    }
}
