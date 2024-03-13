<?php

namespace App\Http\Controllers;

use Dcblogdev\MsGraph\Facades\MsGraph;

class AuthController extends Controller
{
    
    public function connect()
    {
        return MsGraph::connect();
    }

    public function logout365()
    {
        return MsGraph::disconnect('/');
    }

}
