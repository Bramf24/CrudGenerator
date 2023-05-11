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
            'status' => rand(0,9),
            'exception' => \Illuminate\Support\Str::random(20),
            'file' => \Illuminate\Support\Str::random(20),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
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
            'Authorization' => 'Bearer '.test_token()
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
        $errorLog = \App\Models\ErrorLog::factory()->create();
        $this->get('ParamUrl/'.$errorLog->id,[
            'Authorization' => 'Bearer '.test_token()
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
        $errorLog = \App\Models\ErrorLog::factory()->create();
        $this->put('ParamUrl/'.$errorLog->id,[
            'status' => rand(0,9),
            'exception' => \Illuminate\Support\Str::random(20),
            'file' => \Illuminate\Support\Str::random(20),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ],[
            'Authorization' => 'Bearer '.test_token()
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
        $errorLog = \App\Models\ErrorLog::factory()->create();
        $this->delete('ParamUrl/'.$errorLog->id,[],[
            'Authorization' => 'Bearer '.test_token()
        ]);
        $this->seeStatusCode(200);
    }
}