<?php namespace Bramf\CrudGenerator\Builders;

use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class Controller{
    public function __construct(
        private array $params
    ){
        $this->buildParams['ParamController'] = $this->params['controller_name'];
        $this->buildParams['ParamModelLower'] = Str::lower($this->params['model_name']);
        $this->buildParams['ParamModelSnake'] = Str::snake($this->params['model_name'],' ');
        $this->buildParams['ParamModel'] = $this->params['model_name'];
        $this->buildParams['ParamUrl'] = $this->params['crud_url'];
        $this->buildParams['#JwtAuth'] = (env('JWT_SECRET') ? '$this->middleware("auth:api");' : '');
        $this->output = new ConsoleOutput();
        $this->controllerCrudDir = base_path().'/app/Http/Controllers/Crud';
        $this->modelBuilder = new Model($this->params);
        $this->controllerRequestOA = [];
        $this->controllerResponseOA = [];
    }

    /**
     * Build open api annotations for method request
     * @param array $fields - model fields
     * @return string $template - generated open api annotations for request
     */
    private function buildOARequest($fields){
        $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Controllers/OARequest.temp');
        $buildParams = [
            'ParamName' => $fields['name'],
            'ParamIn' => 'query',
            'ParamNameUcfirst' => ucfirst($fields['name']),
            'ParamRequired' => (!empty($fields['rules']['required']) ? true : false),
            'ParamType' => $fields['type'],
            'ParamMaxLength' => (!empty($fields['rules']['max']) ? ',maxLength='.str_replace('max:','',$fields['rules']['max']) : '')
        ];
        foreach($buildParams as $param => $value){
            $template = str_replace($param,$value,$template);
        }
        return $template;
    }

    /**
     * Generate request and response open api annotations
     */
    private function oaReqResp(){
        $modelFields = $this->modelBuilder->getFields();
        foreach($modelFields as $column => $fields){
            $this->controllerRequestOA[] = $this->buildOARequest($fields);
        }
    }

    /**
     * Build controller file
     */
    public function build(){
        $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Controllers/Crud.php');
        foreach($this->buildParams as $param => $value){
            $template = str_replace($param,$value,$template);
        }
        $this->oaReqResp();
        $template = str_replace('#OARequest',join("\n",$this->controllerRequestOA),$template);
        if(!file_exists($this->controllerCrudDir) && !is_dir($this->controllerCrudDir)){
            mkdir($this->controllerCrudDir);
        }
        file_put_contents(base_path().'/app/Http/Controllers/Crud/'.$this->buildParams['ParamController'].'.php',$template);
        if(!file_exists(base_path().'/app/Http/Controllers/'.$this->buildParams['ParamController'].'.php')){
            $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Controllers/Base.php');
            foreach($this->buildParams as $param => $value){
                $template = str_replace($param,$value,$template);
            }
            file_put_contents(base_path().'/app/Http/Controllers/'.$this->buildParams['ParamController'].'.php',$template);
        }
        $this->output->writeln('<info>Controller '.$this->buildParams['ParamController'].' created successfully</info>');
    }
}