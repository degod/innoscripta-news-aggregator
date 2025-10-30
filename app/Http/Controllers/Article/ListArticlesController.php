<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Services\ResponseService;

/**
 * @OA\Get(
 *     path="/api/v1/articles",
 *     tags={"Articles"},
 *     summary="List or search articles",
 *     description="Retrieve paginated list of articles with optional filters for query, category, source, author, and date range.",
 *     @OA\Parameter(name="q", in="query", description="Search keyword", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="category", in="query", description="Filter by category", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="source", in="query", description="Filter by source", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="author", in="query", description="Filter by author", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="date", in="query", description="Filter by date (YYYY-MM-DD)", required=false, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="List of filtered articles"),
 *     @OA\Response(response=404, description="No articles found matching the criteria")
 * )
 */
class ListArticlesController extends Controller
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ResponseService $responseService
    ) {}

    public function __invoke(Request $request)
    {
        $filters = [
            'q' => $request->query('q'),
            'category' => $request->query('category'),
            'source' => $request->query('source'),
            'author' => $request->query('author'),
            'date' => $request->query('date'),
        ];

        $articles = $this->articleRepository->filter($filters);
        if ($articles->isEmpty()) {
            return $this->responseService->error(404, 'No articles found matching the criteria');
        }

        return $this->responseService->success(200, 'Articles retrieved successfully', $articles);
    }
}
