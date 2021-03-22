<?php

namespace App\Http\Controllers;

use App\Models\Pizza;
use App\Models\Pizza\InvalidPropertyException;
use Illuminate\Http\Request;


class PizzaController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @OA\Get(
     *     path="/pizzas",
     *     summary="Returns all pizzas",
     *     operationId="index",
     *     @OA\Response(
     *         response=200,
     *         description="pizzas response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Pizza")
     *         )
     *     )
     * )
     */
    public function index() {
        return response()->json(Pizza::all());
    }

    /**
     * @OA\Get(
     *     path="/pizzas/{id}",
     *     summary="Returns a single pizza based on the id given",
     *     operationId="show",
     *     @OA\Parameter(
     *         description="ID of pizza to fetch",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Single pizza response",
     *         @OA\JsonContent(ref="#/components/schemas/Pizza")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pizza not found"
     *     )
     * )
     */
    public function show($id)
    {
        return response()->json(Pizza::findOrFail($id));
    }

    /**
     * @OA\Post(
     *     path="/pizzas",
     *     summary="Adds a new pizza",
     *     operationId="create",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="properties",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         default="",
     *                         enum={
     *                             "vegan",
     *                             "vegetarian",
     *                             "glutenfree",
     *                             "spicy",
     *                             "sweet"
     *                         }
     *                     )
     *                 ),
     *                 example={"name": "Pizza Hawai", "price": "7.99", "properties": "vegan"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/Pizza")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Bad Entity"
     *     )
     * )
     */
    public function create(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'price' => 'required'
        ]);

        if ($validated) {
            $pizza = Pizza::create($request->all());
            return response()->json($pizza, 201);
        } else {
            return response()->json($errors, 422);
        }
    }

    /**
     * @OA\PUT(
     *     path="/pizzas/{id}",
     *     summary="Updates a single pizza",
     *     operationId="update",
     *     @OA\Parameter(
     *         description="ID of pizza to update",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="properties",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         default="",
     *                         enum={
     *                             "vegan",
     *                             "vegetarian",
     *                             "glutenfree",
     *                             "spicy",
     *                             "sweet"
     *                         }
     *                     )
     *                 ),
     *                 example={"name": "Pizza Hawai", "price": "7.99", "properties": "vegan"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated",
     *         @OA\JsonContent(ref="#/components/schemas/Pizza")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Bad Entity"
     *     )
     * )
     */
    public function update($id, Request $request)
    {
        $pizza = Pizza::findOrFail($id);
        $validated = $this->validate($request, [
            'name' => 'required',
            'price' => 'required'
        ]);

        if ($validated) {
            $pizza->update($request->all());
            return response()->json($pizza, 200);
        } else {
            return response()->json($errors, 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/pizzas/{id}",
     *     summary="Deletes a single pizza based on the id given",
     *     @OA\Parameter(
     *         description="ID of pizza to delete",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Delete operation successful"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pizza not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        Pizza::findOrFail($id)->delete();
        return response('', 204);
    }

    /**
     * @OA\Post(
     *     path="/pizzas/{id}/{property}",
     *     summary="Adds a property to a pizza",
     *     operationId="addProperty",
     *     @OA\Parameter(
     *         description="ID of pizza to update",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Name of the property to add to the pizza",
     *         in="path",
     *         name="property",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully added property",
     *         @OA\JsonContent(ref="#/components/schemas/Pizza")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Nothing happened, property was already present",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pizza not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid property given"
     *     )
     * )
     */
    public function addProperty($id, $property)
    {
        $pizza = Pizza::findOrFail($id);

        try {
            if ($pizza->addProperty($property)) {
                return response()->json($pizza, 200);
            } else {
                return response()->json('', 204);
            }
        } catch(InvalidPropertyException $e) {
            return response()->json($e, 422);
        }
    }
}
