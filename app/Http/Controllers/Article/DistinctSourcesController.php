<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Services\ResponseService;
use OpenApi\Annotations as OA;

class DistinctSourcesController extends Controller
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ResponseService $responseService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/articles/sources",
     *     operationId="getDistinctSources",
     *     tags={"Articles"},
     *     security={{"bearerAuth":{}}},
     *     summary="Get distinct article sources",
     *     description="Returns a list of unique sources from the articles table",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="string", example="BBC News")
     *             ),
     *             @OA\Property(property="error", type="string", example=null)
     *         )
     *     ),
     *     @OA\Response(response=404, description="No article source found")
     * )
     */
    public function __invoke()
    {
        $sources = $this->articleRepository->getDistinctSources();
        if ($sources->isEmpty()) {
            return $this->responseService->error(404, "No article source found");
        }

        return $this->responseService->success(
            200,
            'Distinct sources fetched successfully',
            $sources
        );
    }
}
