<?php

$controller_template = 
"<?php
          
namespace *Namespace*;

use Controller;

class *ClassName* extends Controller {

}";

$model_template = 
"<?php
            
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Name extends Model {

    protected \$table = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected \$fillable = [];

}";