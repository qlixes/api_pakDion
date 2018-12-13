<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require COREPATH . 'Controller.php';

class Users extends Controller implements IController
{
    function index()
    {
        echo 'index';
    }

    function login()
    {
        echo 'sukses';
    }

    function logout()
    {}

    function checkin()
    {}
}
