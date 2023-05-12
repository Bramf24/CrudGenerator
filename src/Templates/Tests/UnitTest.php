<?php namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ParamModelTest extends TestCase
{
    /**
     * ParamUrl [POST]
     */
    public function test_create_ParamTableNameSingle(){
        $response = $this->post('ParamUrl',[
#ParamRequest
        ],[
            ParamAuthHeader
        ]);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            ParamResponseFields
        ]);
        \App\Models\ParamModel::find($response->id)->delete();
    }

    /**
     * ParamUrl [GET]
     */
    public function test_get_all_ParamTableName(){
        $this->get('ParamUrl',[
            ParamAuthHeader
        ]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            [ParamResponseFields]
        ]);
    }

    /**
     * ParamUrlId [GET]
     */
    public function test_get_single_ParamTableNameSingle(){
        $model = \App\Models\ParamModel::factory()->create();
        $this->get('ParamUrl/'.$model->id,[
            ParamAuthHeader
        ]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            ParamResponseFields
        ]);
        \App\Models\ParamModel::find($model->id)->delete();
    }

    /**
     * ParamUrlId [PUT]
     */
    public function test_update_ParamTableNameSingle(){
        $model = \App\Models\ParamModel::factory()->create();
        $this->put('ParamUrl/'.$model->id,[
#ParamRequest
        ],[
            ParamAuthHeader
        ]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            ParamResponseFields
        ]);
        \App\Models\ParamModel::find($model->id)->delete();
    }

    /**
     * ParamUrlId [DELETE]
     */
    public function test_delete_ParamTableNameSingle(){
        $model = \App\Models\ParamModel::factory()->create();
        $this->delete('ParamUrl/'.$model->id,[],[
            ParamAuthHeader
        ]);
        $this->seeStatusCode(200);
    }
}