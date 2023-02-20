<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Repositories\ProductRepository;

class ProductsController extends Controller
{
    use ResponseTrait;
    public $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    /**
     * Display a listing of the resource.
     *
    //  * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get all products",
     *     description="Per Page Product Count",
     *     deprecated=false,
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Status values that needed to be considered for filter",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="10",
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by title",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="",
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="Order By Column Name",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="id",
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Order ASC or DESC",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="desc",
     *             type="string"
     *         )
     *     ),
     *     security={{ "bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            return $this->responseSuccess($this->productRepository->getAll(request()->all()));
        } catch (Exception $e) {
            return $this->responseError([], $e->getMessage());
        }
    }
    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Create product",
     *     description="Create product",
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="Create product",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="Product title",
     *                     type="string",
     *                     example="Oshxona mebeli"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     description="Product slug",
     *                     type="string",
     *                     example="product-title"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="Price",
     *                     type="integer",
     *                     example="682"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="Product image",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *                 required={"title","price"}
     *             )
     *         ),
     *     ),
     *     security={{ "bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(CreateProductRequest $request)
    {
        try {
            return $this->responseSuccess($this->productRepository->create($request->all()), "Product created successfully!");
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Get all products",
     *     description="Per Page Product Count",
     *     deprecated=false,
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     security={{ "bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Not Found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            return $this->responseSuccess($this->productRepository->getById($id), "Product showed successfully!");
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Update product",
     *     description="Update product",
     *     deprecated=false,
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="_method",
     *         in="query",
     *         description="method",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="PUT"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update product",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="Product title",
     *                     type="string",
     *                     example="Oshxona mebeli"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     description="Product slug",
     *                     type="string",
     *                     example="product-title"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="Price",
     *                     type="integer",
     *                     example="682"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="Product image",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *                 required={"title","price","slug"}
     *             )
     *         ),
     *     ),
     *     security={{ "bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            return $this->responseSuccess($this->productRepository->update($id, $request->all()), "Product updated successfully!");
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Delete product",
     *     description="Delete specific product",
     *     deprecated=false,
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     security={{ "bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Not Found"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        try {
            return $this->responseSuccess($this->productRepository->delete($id), "Product delated successfully!");
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
}
