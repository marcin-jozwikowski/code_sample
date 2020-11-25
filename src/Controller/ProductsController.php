<?php


namespace App\Controller;


use App\Entity\Product;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/product")
 */
class ProductsController extends AbstractApiController
{
    protected function getApiEntityClassName(): string
    {
        return Product::class;
    }

    /**
     * @Route(path="/", name="products_index", methods={"GET"})
     *
     * @OA\Get(
     *     @OA\Parameter(name="page", required=false, description="Page number", in="path",
     *          @OA\Schema(type="integer", default="1"),
     *     ),
     *     @OA\Parameter(name="perPage", required=false, description="Items per page", in="path",
     *          @OA\Schema(type="integer", default="3"),
     *     ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="List all products",
     *     @OA\JsonContent(ref=@Nelmio\Model(type=Product::class, groups={"index"}))
     * ),
     */
    public function index(Request $request): JsonResponse
    {
        return parent::index($request);
    }
}