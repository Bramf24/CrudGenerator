<?php namespace App\Http\Controllers\Crud;

use Illuminate\Http\Request;
use Bramf\CrudGenerator\Traits\RestActions;
use App\Http\Controllers\Controller;

class ParamController extends Controller{
    const MODEL = 'App\Models\Crud\ParamModel';

    use RestActions;

    /**
        * @OA\Get(
        *   path="ParamUrl",
        *   tags={"ParamModelLower"},
        *   description="Get all ParamModelSnakes",
        *   operationId="ParamModelLowerall",
        *   @OA\Response(
        *     response=200, 
        *     description="ParamModelSnake objects",
        *     @OA\JsonContent(
        *       type="array",
        *       @OA\Items(ref="#/components/schemas/ParamModel")
        *     )
        *   ),
        *   @OA\Response(response=401, description="Unauthorized")
        * )
    */
    private $all;

    /**
        * @OA\Get(
        *   path="ParamUrl/{ParamModelLowerId}",
        *   tags={"ParamModelLower"},
        *   description="Find ParamModelSnake by id",
        *   operationId="ParamModelLowerget",
        *   @OA\Response(
        *     response=200, 
        *     description="ParamModelSnake object",
        *     @OA\JsonContent(
        *       type="array",
        *       @OA\Items(ref="#/components/schemas/ParamModel")
        *     )
        *   ),
        *   @OA\Response(response=401, description="Unauthorized")
        * )
    */
    private $get;

    /**
        * @OA\Post(
        *   path="ParamUrl",
        *   tags={"ParamModelLower"},
        *   description="Create new ParamModelSnake",
        *   operationId="ParamModelLowercreate",
        *   #OARequest
        *   @OA\Response(
        *     response=200, 
        *     description="ParamModelSnake object",
        *     @OA\JsonContent(
        *       type="array",
        *       @OA\Items(ref="#/components/schemas/ParamModel")
        *     )
        *   ),
        *   @OA\Response(response=401, description="Unauthorized"),
        *   @OA\Response(response=422, description="Validation errors")
        * )
    */
    private $create;

    /**
        * @OA\Put(
        *   path="ParamUrl/ParamModelLowerId}",
        *   tags={"ParamModelLower"},
        *   description="Update ParamModelSnake with given Id",
        *   operationId="ParamModelLowerupdate",
        *   #OARequest
        *   @OA\Response(
        *     response=200, 
        *     description="ParamModelSnake object",
        *     @OA\JsonContent(
        *       type="array",
        *       @OA\Items(ref="#/components/schemas/ParamModel")
        *     )
        *   ),
        *   @OA\Response(response=401, description="Unauthorized"),
        *   @OA\Response(response=422, description="Validation errors")
        * )
    */
    private $update;

    /**
        * @OA\Delete(
        *   path="ParamUrl/{ParamModelLowerId}",
        *   tags={"ParamModelLower"},
        *   description="Delete ParamModelSnake with given Id",
        *   operationId="ParamModelLowerdelete",
        *   @OA\Response(
        *     response=200, 
        *     description="ParamModel model with given id removed"
        *   ),
        *   @OA\Response(response=401, description="Unauthorized")
        * )
    */
    private $delete;
}