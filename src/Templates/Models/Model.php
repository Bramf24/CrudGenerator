<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Awobaz\Compoships\Compoships;

/**
    * Class ParamModel
    * 
    * @package App\Models
    * 
    * @author ParamAuthor
    * 
    * @OA\Schema(
    *   title="ParamModel model",
    *   description="ParamModel model"
    * )
*/
class ParamModel extends Model{
    use Compoships;

    protected $table = "ParamTable";

    /**
    * Validation rules
    */
    public static $rules = [
#RULES
    ];

#PROPERTIES

    /**
        * The attributes that are mass assignable.
        *
        * @var string[]
    */
    protected $fillable = [
#FILLABLE
    ];
}