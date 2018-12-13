<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require COREPATH . 'Controller.php';

class Errors extends Controller implements IController
{
    function index()
    {
        echo 'failed';
    }
}
