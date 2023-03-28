<?php namespace App\Services;

use App\Traits\ApiRequest;

/**
 * service for interacting with ParamServiceLowerPlural
 */
class ParamServiceService{
    use ApiRequest;
    
    public function __construct(){
        $this->baseUrl = config('services.bramf.ParamServiceLower.url'); // set ParamServiceLower service url
    }

    /**
     * get all ParamServiceLowerPlural list
     * @return mixed response - response with all ParamServiceLowerPlural
     */
    public function all() : mixed{
        $response = $this->getReq("api/ParamServiceLower");
        return response()->json($response['data'],$response['status']);
    }
    
    /**
     * get ParamServiceLower by id
     * @param integer id - id of ParamServiceLower
     * @return mixed response - response with ParamServiceLower
     */
    public function get(int $id) : mixed{
        $response = $this->getReq("api/ParamServiceLower/{$id}");
        return response()->json($response['data'],$response['status']);
    }

    /**
     * create new ParamServiceLower
     * @param array data - new ParamServiceLowerPlural data
     * @return mixed response - response with new ParamServiceLower
     */
    public function create(array $data) : mixed{
        $response = $this->postReq("/api/ParamServiceLower",$data);
        return response()->json($response['data'],$response['status']);
    }

    /**
     * update ParamServiceLower with given id
     * @param array data - ParamServiceLowerPlural data
     * @param integer id - id ParamServiceLower
     * @return mixed response - response with updated ParamServiceLower
     */
    public function update(array $data, int $id) : mixed{
        $response = $this->putReq("/api/ParamServiceLower/{$id}",$data);
        return response()->json($response['data'],$response['status']);
    }

    /**
     * delete ParamServiceLower with given id
     * @param integer id - id ParamServiceLower
     * @return mixed response - response with status
     */
    public function delete(int $id) : mixed{
        $response = $this->deleteReq("/api/ParamServiceLower/{$id}");
        return response()->json($response['data'],$response['status']);
    }
}