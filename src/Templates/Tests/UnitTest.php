<?php namespace Tests\Crud;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class ParamModelTest extends TestCase
{
    use DatabaseTransactions;

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
    }

    /**
     * ParamUrl [GET]
     */
    public function test_get_all_ParamTableName(){
        $model = \App\Models\ParamModel::factory()->create();
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

    private function token(){
        $response = \Illuminate\Support\Facades\Http::post(env('JWT_AUTH_URL'),[
            'login' => env('JWT_AUTH_USER'),
            'password' => env('JWT_AUTH_PASSWORD')
        ]);
        return $response->json('access_token') ?? '';
    }
}