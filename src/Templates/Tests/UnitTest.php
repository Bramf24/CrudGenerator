<?php namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ParamModelTest extends TestCase
{
    /**
     * ParamUrl [POST]
     */
    public function test_create_ParamTableNameSingle(){
        $this->post('ParamUrl',[
#ParamRequest
        ],[
            ParamAuthHeader
        ]);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'id','status','exception','file'
        ]);
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
            ['id','status','exception','file']
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
            'id','status','exception','file'
        ]);
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
            'id','status','exception','file'
        ]);
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