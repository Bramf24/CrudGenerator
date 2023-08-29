<?php namespace Tests\Crud;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

class ParamModelTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    /**
     * ParamUrl [POST]
     */
    public function test_create_ParamTableNameSingle(){
        Event::fake();
        $this->post('ParamUrl',[
#ParamRequest
        ]);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            ParamResponseFields
        ]);
    }

    /**
     * ParamUrl [GET]
     */
    public function test_get_all_ParamTableName(){
        Event::fake();
        $model = \App\Models\ParamModel::factory()->create();
        $this->get('ParamUrl?limit=10');
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            [ParamResponseFields]
        ]);
    }

    /**
     * ParamUrlId [GET]
     */
    public function test_get_single_ParamTableNameSingle(){
        Event::fake();
        $model = \App\Models\ParamModel::factory()->create();
        $this->get('ParamUrl/'.$model->id);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            ParamResponseFields
        ]);
    }

    /**
     * ParamUrlId [PUT]
     */
    public function test_update_ParamTableNameSingle(){
        Event::fake();
        $model = \App\Models\ParamModel::factory()->create();
        $this->put('ParamUrl/'.$model->id,[
#ParamRequest
        ]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            ParamResponseFields
        ]);
    }

    /**
     * ParamUrlId [DELETE]
     */
    public function test_delete_ParamTableNameSingle(){
        Event::fake();
        $model = \App\Models\ParamModel::factory()->create();
        $this->delete('ParamUrl/'.$model->id);
        $this->seeStatusCode(200);
    }
}