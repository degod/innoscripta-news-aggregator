<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ListArticlesRequest;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Services\ResponseService;

/**
 * @OA\Get(
 *     path="/api/v1/articles",
 *     tags={"Articles"},
 *     security={{"bearerAuth":{}}},
 *     summary="List or search articles",
 *     description="Retrieve paginated list of articles with optional filters for query, category, source, author, and date.",
 *     @OA\Parameter(name="q", in="query", description="Search keyword", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="category", in="query", description="Filter by category", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="source", in="query", description="Filter by source", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="author", in="query", description="Filter by author", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="date", in="query", description="Filter by date (YYYY-MM-DD)", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="per_page", in="query", description="Items per page (1-100)", required=false, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="List of filtered articles")
 * )
 */
class ListArticlesController extends Controller
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ResponseService $responseService
    ) {}

    public function __invoke(ListArticlesRequest $request)
    {
        $filters = $request->filters();
        $perPage = $request->perPage();

        $paginator = $this->articleRepository->filter($filters, $perPage);

        return $this->responseService->successPaginated($paginator, 'Articles retrieved successfully', 200);
    }
}
