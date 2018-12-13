<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require COREPATH . 'Controller.php';

class Users extends Controller implements IController
{
    function index()
    {
        $this->getResponse(false, 'label_api_not_found');
    }

    function login()
    {
        
    }

    function logout()
    {}

    function checkin($token)
    {}
}
