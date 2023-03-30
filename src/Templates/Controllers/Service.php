<?php namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Services\ParamControllerService;
use App\Http\Controllers\Controller;

/**
 * Class ParamControllerController
 * 
 * @package ApiGateway\Controllers
 * 
 * @author Vlad <mun.vladislav.a@gmail.com>
 */
class ParamControllerController extends Controller{
    public function __construct(
        private $ParamControllerLowerService = new ParamControllerService()
    ){
        #JwtAuth
    }
    
    /**
     * @OA\Get(
     *   path="/api/service/ParamControllerLower",
     *   tags={"ParamControllerLowerPlural"},
     *   summary="Returns all ParamControllerLowerPlural",
     *   description="Returns all ParamControllerLowerPlural",
     *   operationId="getParamControllerPlural",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/ParamController")
     *   )
     * )
     */
    #[Route("api/service/ParamControllerLower", method: ["GET"])]
    public function all(){
        return $this->ParamControllerLowerService->all();
    }

    /**
     * @OA\Get(
     *   path="/api/service/ParamControllerLower/{ParamControllerLowerId}",
     *   tags={"ParamControllerLowerPlural"},
     *   summary="Find ParamControllerLower by id",
     *   description="Return single ParamControllerLower",
     *   operationId="getParamController",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/ParamController")
     *   )
     * )
     */
    #[Route("api/service/ParamControllerLower/{id}", method: ["GET"])]
    public function get(int $id){
        return $this->ParamControllerLowerService->get($id);
    }

    /**
     * @OA\Post(
     *   path="/api/service/ParamControllerLower",
     *   tags={"ParamControllerLowerPlural"},
     *   summary="Add new ParamControllerLower",
     *   description="Create and return new ParamControllerLower",
     *   operationId="createParamController",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/ParamController")
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation errors"
     *   )
     * )
     */
    #[Route("api/service/ParamControllerLower", method: ["POST"])]
    public function create(Request $request){
        return $this->ParamControllerLowerService->create($request->all());
    }

    /**
     * @OA\Put(
     *   path="/api/service/ParamControllerLower/{ParamControllerLowerId}",
     *   tags={"ParamControllerLowerPlural"},
     *   summary="Update ParamControllerLower",
     *   description="Update ParamControllerLower with given id and data",
     *   operationId="updateParamController",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/ParamController")
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation errors"
     *   )
     * )
     */
    #[Route("api/service/ParamControllerLower", method: ["PUT"])]
    public function update(int $id, Request $request){
        return $this->ParamControllerLowerService->update($request->all(),$id);
    }

    /**
     * @OA\Delete(
     *   path="/api/service/ParamControllerLower/{ParamControllerLowerId}",
     *   tags={"ParamControllerLowerPlural"},
     *   summary="Delete ParamControllerLower",
     *   description="Delete ParamControllerLower with given id",
     *   operationId="deleteParamController",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/ParamController")
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation errors"
     *   )
     * )
     */
    #[Route("api/service/ParamControllerLower", methods: ["DELETE"])]
    public function delete(int $id){
        return $this->ParamControllerLowerService->delete($id);
    }
}