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
 *     summary="Retrieve articles based on user preferences",
 *     description="Accepts preferred sources, categories, or authors arrays to filter articles.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="sources", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="categories", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="authors", type="array", @OA\Items(type="string"))
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Filtered articles by preferences",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Articles retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(type="object")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=422, description="Invalid or missing preferences", @OA\JsonContent(type="object")),
 *     @OA\Response(response=404, description="No articles found matching preferences", @OA\JsonContent(type="object"))
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

        $articles = $this->articleRepository->filterByPreferences($preferences);

        if ($articles->isEmpty()) {
            return $this->responseService->error(404, 'No articles found matching preference(s)');
        }

        return $this->responseService->success(200, 'Articles retrieved successfully', $articles);
    }
}
