<?php


namespace App\Controller;


use App\Entity\Product;
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
     */
    public function index(Request $request): JsonResponse
    {
        return parent::index($request);
    }
}