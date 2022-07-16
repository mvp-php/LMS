<?php

namespace CyberEd\App\Controllers\Common;

use Illuminate\Routing\Controller;

class BaseController extends  Controller
{
    public $successStatus = 200;
    public $validationStatus = 422;
    public $errorStatus = 500;
    public $unauthorizedStatus = 401;
    public $notFoundStatus = 404;
    public $alreadyExistStatus = 409;

}