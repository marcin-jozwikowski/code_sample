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
     * @OA\Tag(name="Product")
     * @OA\Get(
     *     @OA\Parameter(name="page", required=false, description="Page number", in="query",
     *          @OA\Schema(type="integer", default="1"),
     *     ),
     *     @OA\Parameter(name="perPage", required=false, description="Items per page", in="query",
     *          @OA\Schema(type="integer", default="3"),
     *     ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="List of all products",
     *     @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Nelmio\Model(type=Product::class, groups={"index"}))
     *     ),
     * ),
     */
    public function indexAction(Request $request): JsonResponse
    {
        return parent::indexAction($request);
    }

    /**
     * @Route(path="/{id}", name="product_get", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @OA\Tag(name="Product")
     * @OA\Get(
     *     @OA\Parameter(name="id", required=true, description="Product ID", in="path",
     *          @OA\Schema(type="integer"),
     *     ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Product details",
     *     @OA\JsonContent(ref=@Nelmio\Model(type=Product::class, groups={"get"}))
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Product not found",
     * ),
     */
    public function getAction(int $id): JsonResponse
    {
        return parent::getAction($id);
    }

    /**
     * @Route(path="/", name="product_post", methods={"POST"})
     * @OA\Tag(name="Product")
     * @OA\Post()
     * @OA\RequestBody(
     *     @OA\JsonContent(ref=@Nelmio\Model(type=Product::class, groups={"post"}))
     * ),
     * @OA\Response(
     *     response=201,
     *     description="Product added",
     *     @OA\JsonContent(ref=@Nelmio\Model(type=Product::class, groups={"all"}))
     * ),
     * @OA\Response(response="400", description="Invalid data structure"),
     * @OA\Response(response="417", description="Validation failed",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="field_name", type="array",
     *              @OA\Items(type="string", default="Error content"),
     *          ),
     *     )
     * ),
     */
    public function postAction(Request $request): JsonResponse
    {
        return parent::postAction($request);
    }

    /**
     * @Route(path="/{id}", name="product_patch", methods={"PATCH"}, requirements={"id"="\d+"})
     * @OA\Tag(name="Product")
     * @OA\Patch (
     *     @OA\Parameter(name="id", required=true, description="Product ID", in="path",
     *          @OA\Schema(type="integer"),
     *     ),
     * )
     * @OA\RequestBody(
     *     @OA\JsonContent(ref=@Nelmio\Model(type=Product::class, groups={"post"}))
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Product updated",
     *     @OA\JsonContent(ref=@Nelmio\Model(type=Product::class, groups={"update"}))
     * ),
     * @OA\Response(response="400", description="Invalid data structure"),
     * @OA\Response(response="404", description="Product not found"),
     * @OA\Response(response="417", description="Validation failed",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="field_name", type="array",
     *              @OA\Items(type="string", default="Error content"),
     *          ),
     *     )
     * ),
     */
    public function patchAction(int $id, Request $request): JsonResponse
    {
        return parent::patchAction($id, $request);
    }

    /**
     * @Route(path="/{id}", name="product_put", methods={"PUT"}, requirements={"id"="\d+"})
     * @OA\Tag(name="Product")
     * @OA\Put (
     *     @OA\Parameter(name="id", required=true, description="Product ID", in="path",
     *          @OA\Schema(type="integer"),
     *     ),
     * )
     * @OA\RequestBody(
     *     @OA\JsonContent(ref=@Nelmio\Model(type=Product::class, groups={"post"}))
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Product updated",
     *     @OA\JsonContent(ref=@Nelmio\Model(type=Product::class, groups={"update"}))
     * ),
     * @OA\Response(response="400", description="Invalid data structure"),
     * @OA\Response(response="404", description="Product not found"),
     * @OA\Response(response="417", description="Validation failed",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="field_name", type="array",
     *              @OA\Items(type="string", default="Error content"),
     *          ),
     *     )
     * ),
     */
    public function putAction(int $id, Request $request): JsonResponse
    {
        return parent::putAction($id, $request);
    }

    /**
     * @Route(path="/{id}", name="product_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * @OA\Tag(name="Product")
     * @OA\Delete (
     *     @OA\Parameter(name="id", required=true, description="Product ID", in="path",
     *          @OA\Schema(type="integer"),
     *     ),
     * )
     * @OA\Response(
     *     response=200,
     *     description="Product delete")
     * ),
     * @OA\Response(response="404", description="Product not found"),
     */
    public function deleteAction(int $id): JsonResponse
    {
        return parent::deleteAction($id);
    }
}
