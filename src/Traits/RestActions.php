<?php namespace Bramf\CrudGenerator\Traits;

use Illuminate\Http\Request;

/**
 * trait for all REST actions
 * controller must have constant with model's name
 * model must have public static $rules array to validate input data
 */
trait RestActions{    
    public function all(): mixed{
        return response()->json(self::MODEL::all(),200);
    }

    public function get(int $id): mixed{
        return response()->json(self::MODEL::find($id),200);
    }

    public function create(Request $request): mixed{
        $this->validate($request,self::MODEL::$rules);
        if(self::MODEL::where($request->all())->exists()) return false;
        return response()->json(self::MODEL::create($request->all()),201);
    }

    public function update(Request $request, int $id): mixed{
        $this->validate($request,self::MODEL::$rules);
        $model = self::MODEL::find($id);
        $model->update($request->all());
        return response()->json($model,200);
    }

    public function delete(int $id): mixed{
        self::MODEL::destroy($id);
        return response()->json(self::MODEL.' with id: '.$id.' removed',200);
    }
}