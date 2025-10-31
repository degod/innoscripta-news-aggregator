<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\FilterArticlesByPreferencesRequest;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Services\ResponseService;

/**
 * @OA\Post(
 *     path="/api/v1/articles/preferences",
 *     tags={"Articles"},
 *     security={{"bearerAuth":{}}},
 *     summary="Retrieve articles based on user preferences",
 *     description="Accepts preferred sources, categories, or authors arrays to filter articles.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="sources", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="categories", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="authors", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="per_page", type="integer", example=10)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Filtered articles by preferences"
 *     ),
 *     @OA\Response(response=422, description="Invalid or missing preferences", @OA\JsonContent(type="object"))
 * )
 */
class FilterArticlesByPreferencesController extends Controller
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ResponseService $responseService
    ) {}

    public function __invoke(FilterArticlesByPreferencesRequest $request)
    {
        $preferences = $request->validated();
        $perPage = isset($preferences['per_page']) ? (int) $preferences['per_page'] : 10;

        $paginator = $this->articleRepository->filterByPreferences($preferences, $perPage);

        return $this->responseService->successPaginated($paginator, 'Articles retrieved successfully', 200);
    }
}
