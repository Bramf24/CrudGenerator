<?php namespace Bramf\CrudGenerator\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * trait for all REST actions
 * controller must have constant with model's name
 * model must have public static $rules array to validate input data
 */
trait RestActions{
    public function __construct(
        private $model = self::MODEL // get the model name from controller's MODEL constant
    ){}
    
    public function all(): JsonResponse{
        return response()->json($this->model::all(),200);
    }

    public function get(int $id): JsonResponse{
        return response()->json($this->model::find($id),200);
    }

    public function create(Request $request): JsonResponse{
        $this->validate($request,$this->model::$rules);
        return response()->json($this->model::create($request->all()),201);
    }

    public function update(Request $request, int $id): JsonResponse{
        $this->validate($request,$this->model::$rules);
        $model = $this->model::find($id);
        $model->update($request->all());
        return response()->json($model,200);
    }

    public function delete(int $id): JsonResponse{
        $this->model::destroy($id);
        return response()->json($this->model.' with id: '.$id.' removed',200);
    }
}