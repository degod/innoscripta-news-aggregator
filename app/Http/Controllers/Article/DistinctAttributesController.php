<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class DistinctAttributesController extends Controller
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ResponseService $responseService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/articles/{attribute}",
     *     operationId="getDistinctArticleAttributes",
     *     tags={"Articles"},
     *     security={{"bearerAuth":{}}},
     *     summary="Get distinct article attributes",
     *     description="Returns a list of unique values for the given attribute (sources, categories, authors)",
     *     @OA\Parameter(name="attribute", in="path", required=true, description="Attribute name: sources|categories|authors", @OA\Schema(type="string", enum={"sources","categories","authors"})),
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
     *     @OA\Response(response=404, description="No attribute values found or invalid attribute")
     * )
     */
    public function __invoke(Request $request, string $attribute)
    {
        $map = [
            'sources' => fn() => $this->articleRepository->getDistinctSources(),
            'categories' => fn() => $this->articleRepository->getDistinctCategories(),
            'authors' => fn() => $this->articleRepository->getDistinctAuthors(),
        ];

        if (! isset($map[$attribute])) {
            return $this->responseService->error(404, 'Invalid attribute specified');
        }

        $values = $map[$attribute]();

        if ($values->isEmpty()) {
            return $this->responseService->error(404, 'No article ' . rtrim($attribute, 's') . ' found');
        }

        return $this->responseService->success(200, 'Distinct ' . $attribute . ' fetched successfully', $values);
    }
}
