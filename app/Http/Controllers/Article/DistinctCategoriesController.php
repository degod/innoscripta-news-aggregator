<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Services\ResponseService;
use OpenApi\Annotations as OA;

class DistinctCategoriesController extends Controller
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ResponseService $responseService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/articles/categories",
     *     operationId="getDistinctCategories",
     *     tags={"Articles"},
     *     security={{"bearerAuth":{}}},
     *     summary="Get distinct article categories",
     *     description="Returns a list of unique categories from the articles table",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="string", example="Technology")
     *             ),
     *             @OA\Property(property="error", type="string", example=null)
     *         )
     *     ),
     *     @OA\Response(response=404, description="No article category found")
     * )
     */
    public function __invoke()
    {
        $categories = $this->articleRepository->getDistinctCategories();
        if ($categories->isEmpty()) {
            return $this->responseService->error(404, "No article category found");
        }

        return $this->responseService->success(
            200,
            'Distinct categories fetched successfully',
            $categories
        );
    }
}
