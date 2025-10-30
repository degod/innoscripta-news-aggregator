<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Services\ResponseService;
use OpenApi\Annotations as OA;

class DistinctAuthorsController extends Controller
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ResponseService $responseService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/articles/authors",
     *     operationId="getDistinctAuthors",
     *     tags={"Articles"},
     *     summary="Get distinct article authors",
     *     description="Returns a list of unique authors from the articles table",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="string", example="John Doe")
     *             ),
     *             @OA\Property(property="error", type="string", example=null)
     *         )
     *     ),
     *     @OA\Response(response=404, description="No article author found")
     * )
     */
    public function __invoke()
    {
        $authors = $this->articleRepository->getDistinctAuthors();
        if ($authors->isEmpty()) {
            return $this->responseService->error(404, "No article author found");
        }

        return $this->responseService->success(
            200,
            'Distinct authors fetched successfully',
            $authors
        );
    }
}
