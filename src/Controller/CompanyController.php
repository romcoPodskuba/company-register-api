<?php

namespace App\Controller;

use App\Service\CompanyService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/company', name: 'company_')]
class CompanyController extends AbstractController
{
    public function __construct(
        private readonly CompanyService $companyService
    ) {}

    #[OA\Get(
        summary: 'Get company data from register',
        tags: ['Company'],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Company data retrieved successfully.',
                content: new OA\JsonContent(
                    type: 'object',
                    required: ['businessId', 'taxId', 'name', 'address', 'dateCreated'],
                    properties: [
                        new OA\Property(property: 'businessId', type: 'string', example: '12345678'),
                        new OA\Property(
                            property: 'taxId',
                            type: 'string',
                            nullable: true,
                            example: 'CZ12345678'
                        ),
                        new OA\Property(property: 'name', type: 'string', example: 'Company Name'),
                        new OA\Property(
                            property: 'address',
                            type: 'object',
                            required: ['houseNumber', 'street', 'city', 'postalCode', 'country'],
                            properties: [
                                new OA\Property(property: 'houseNumber', type: 'string', example: '1'),
                                new OA\Property(
                                    property: 'street',
                                    type: 'string',
                                    nullable: true,
                                    example: 'Street'
                                ),
                                new OA\Property(property: 'city', type: 'string', example: 'City'),
                                new OA\Property(property: 'postalCode', type: 'string', example: '12345'),
                                new OA\Property(property: 'country', type: 'string', example: 'Country'),
                            ]
                        ),
                        new OA\Property(
                            property: 'dateCreated',
                            type: 'string',
                            format: 'date',
                            example: '2026-07-29'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_BAD_REQUEST,
                description: 'Invalid business ID.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'integer',
                            example: JsonResponse::HTTP_BAD_REQUEST,
                        ),
                        new OA\Property(
                            property: 'detail',
                            type: 'string',
                            example: 'Invalid business ID.',
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_UNAUTHORIZED,
                description: 'Unauthorized access.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'integer',
                            example: JsonResponse::HTTP_UNAUTHORIZED,
                        ),
                        new OA\Property(
                            property: 'detail',
                            type: 'string',
                            example: 'Unauthorized access.',
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_FORBIDDEN,
                description: 'Access denied.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'integer',
                            example: JsonResponse::HTTP_FORBIDDEN,
                        ),
                        new OA\Property(
                            property: 'detail',
                            type: 'string',
                            example: 'Access denied.',
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_NOT_FOUND,
                description: 'Company not found.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'integer',
                            example: JsonResponse::HTTP_NOT_FOUND,
                        ),
                        new OA\Property(
                            property: 'detail',
                            type: 'string',
                            example: 'Company not found.',
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_TOO_MANY_REQUESTS,
                description: 'Rate limit exceeded.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'integer',
                            example: JsonResponse::HTTP_TOO_MANY_REQUESTS,
                        ),
                        new OA\Property(
                            property: 'detail',
                            type: 'string',
                            example: 'Rate limit exceeded.',
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_BAD_GATEWAY,
                description: 'Failed to parse register response.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'integer',
                            example: JsonResponse::HTTP_BAD_GATEWAY,
                        ),
                        new OA\Property(
                            property: 'detail',
                            type: 'string',
                            example: 'Failed to parse register response.',
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_SERVICE_UNAVAILABLE,
                description: 'Register service is unavailable.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'integer',
                            example: JsonResponse::HTTP_SERVICE_UNAVAILABLE,
                        ),
                        new OA\Property(
                            property: 'detail',
                            type: 'string',
                            example: 'Register service is unavailable.',
                        ),
                    ]
                )
            )
        ]
    )]
    #[OA\Parameter(
        name: 'businessId',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', example: '12345678')
    )]
    #[Route('/{businessId}/register-data', name: 'register_data', methods: ['GET'])]
    public function registerData(string $businessId): JsonResponse
    {
        return $this->json($this->companyService->getFromRegister($businessId));
    }
}
