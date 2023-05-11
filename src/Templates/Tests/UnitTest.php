<?php namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ParamModelTest extends TestCase
{
    /**
     * ParamPostUrl [POST]
     */
    public function test_create_error_log(){
        $this->post('api/error/log',[
            'status' => rand(0,9),
            'exception' => \Illuminate\Support\Str::random(20),
            'file' => \Illuminate\Support\Str::random(20),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ],[
            'Authorization' => 'Bearer '.test_token()
        ]);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'id','status','exception','file'
        ]);
    }

    /**
     * ParamGetAllUrl [GET]
     */
    public function test_get_all_error_logs(){
        $this->get('api/error/log',[
            'Authorization' => 'Bearer '.test_token()
        ]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            ['id','status','exception','file']
        ]);
    }

    /**
     * ParamGetUrl [GET]
     */
    public function test_get_single_error_log(){
        $errorLog = \App\Models\ErrorLog::factory()->create();
        $this->get('api/error/log/'.$errorLog->id,[
            'Authorization' => 'Bearer '.test_token()
        ]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'id','status','exception','file'
        ]);
    }

    /**
     * ParamUpdateUrl [PUT]
     */
    public function test_update_error_log(){
        $errorLog = \App\Models\ErrorLog::factory()->create();
        $this->put('api/error/log/'.$errorLog->id,[
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
     * ParamDeleteUrl [DELETE]
     */
    public function test_delete_error_log(){
        $errorLog = \App\Models\ErrorLog::factory()->create();
        $this->delete('api/error/log/'.$errorLog->id,[],[
            'Authorization' => 'Bearer '.test_token()
        ]);
        $this->seeStatusCode(200);
    }
}