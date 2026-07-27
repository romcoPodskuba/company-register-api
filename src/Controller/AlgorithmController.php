<?php

namespace App\Controller;

use App\Service\AlgorithmService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AlgorithmController extends AbstractController
{
    public function __construct(
        private readonly AlgorithmService $algorithmService
    ) {}


    #[OA\Get(
        summary: 'Get algorithm',
        tags: ['Algorithm'],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Algorithm result retrieved successfully.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        oneOf: [
                            new OA\Schema(type: 'integer', example: 1),
                            new OA\Schema(type: 'string', example: 'SuperFaktura'),
                        ]
                    )
                )
            )
        ]
    )]
    #[Route('/algorithm', name: 'algorithm', methods: ['GET'])]
    public function algorithm(): JsonResponse
    {
        return $this->json($this->algorithmService->generate());
    }
}
