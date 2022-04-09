<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Rules\ProductExists;
use App\Service\ProductService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel OpenApi Demo Documentation",
 *      description="L5 Swagger OpenApi description",
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )
 *
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints of Products"
 * )
 */
class ProductController extends Controller
{
    private ProductService $productService;

    /**
     * @OA\Get(
     *      path="/",
     *      tags={"Products"},
     *      summary="Return a Status: 200 and a Message 'PHP Challenge 20201117'",
     *      @OA\Response(
     *          response=200,
     *          description="PHP Challenge 20201117",
     *          ),
     *       )
     *     )
     */
    public function __construct(
        ProductService $productService
    )
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *      path="/products",
     *      tags={"Products"},
     *      summary="Get list of products",
     *      @OA\Response(
     *          response=200,
     *          description="An example collection resource",
     *          @OA\JsonContent(type="object",
     *             @OA\Property(property="code", type="string", example="AwxzVlE8YW7Yed8"),
     *             @OA\Property(property="title", type="string", example="Hazelnut in black ceramic bowllll"),
     *             @OA\Property(property="type", type="string", example="vegetable"),
     *             @OA\Property(property="description", type="string", example="Hazelnut in black ceramic bowl on old wooden background. forest wealth. rustic style. selective focus"),
     *             @OA\Property(property="filename", type="string", example="7.jpg"),
     *             @OA\Property(property="height", type="integer", example="450"),
     *             @OA\Property(property="width", type="integer", example="301"),
     *             @OA\Property(property="price", type="float", example="27.7"),
     *             @OA\Property(property="rating", type="integer", example="0"),
     *             @OA\Property(property="date", type="string", example="09/04/2022"),
     *          ),
     *       )
     *     )
     */
    public function index(): AnonymousResourceCollection
    {

        return  ProductResource::collection(Product::all());
    }

    /**
     * @OA\Post(
     *      path="/products",
     *      tags={"Products"},
     *      summary="The endpoint will process the products.json file that will be sent by the Web Project",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="products-file",
     *                      type="file",
     *                   ),
     *               ),
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Products saved successfully!",
     *       )
     *     )
     */
    public function store(): JsonResponse
    {
        $data = \request()->validate([
            'products-file' => 'required|max:1000|mimes:json',
        ]);

        // Salva os produtos
//        $this->productService->storeProducts($data);

        return response()->json(['messege' => 'Products saved successfully!'], Response::HTTP_OK);
    }


    /**
     * @OA\Get(
     *      path="/products/{$id}",
     *      tags={"Products"},
     *      summary="Obtain information from only one product from the database",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product Hash",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="An example resource",
     *          @OA\JsonContent(type="object",
     *             @OA\Property(property="code", type="string", example="AwxzVlE8YW7Yed8"),
     *             @OA\Property(property="title", type="string", example="Hazelnut in black ceramic bowllll"),
     *             @OA\Property(property="type", type="string", example="vegetable"),
     *             @OA\Property(property="description", type="string", example="Hazelnut in black ceramic bowl on old wooden background. forest wealth. rustic style. selective focus"),
     *             @OA\Property(property="filename", type="string", example="7.jpg"),
     *             @OA\Property(property="height", type="integer", example="450"),
     *             @OA\Property(property="width", type="integer", example="301"),
     *             @OA\Property(property="price", type="float", example="27.7"),
     *             @OA\Property(property="rating", type="integer", example="0"),
     *             @OA\Property(property="date", type="string", example="09/04/2022"),
     *          )
     *       )
     *     )
     */
    public function show($id): ProductResource
    {
        $product = current(Hashids::decode($id));

        return new ProductResource(Product::find($product));
    }

    /**
     * @OA\Put(
     *      path="/products/{$id}",
     *      tags={"Products"},
     *      summary="Will be responsible for receiving updates made to the Web Project",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product Hash",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Product successfully updated!",
     *       )
     *     )
     */
    public function update($id): JsonResponse
    {
        $product = current(Hashids::decode($id));
        $data = \request()->validate([
            'title' => 'required|max:255',
            'type' => 'required|max:255',
            'price' => 'required',
            'code' => [
                'required',
                New ProductExists
            ],
        ]);

        $data['price'] = round($data['price'], 2);
        Product::find($product)->update($data);

        return response()->json(['messege' => 'Product successfully updated!'], Response::HTTP_OK);
    }

    /**
     * @OA\Delete (
     *      path="/products/{$id}",
     *      tags={"Products"},
     *      summary="Get list of products",
     *      description="Remove product from database",
     *
     *      @OA\Response(
     *          response=200,
     *          description="Product successfully removed!",
     *       )
     *     )
     */
    public function destroy($id): JsonResponse
    {
        $product = current(Hashids::decode($id));
        Product::destroy($product);

        return response()->json(['Product successfully removed!'], Response::HTTP_OK);
    }
}
