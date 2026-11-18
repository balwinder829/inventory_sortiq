<?php 
namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class AdminController extends Controller

{
    public function __construct()
    {
       // $this->middleware('auth');
       $this->middleware('permission:dashboard.view')->only('dashboard');
       // $this->middleware('permission:colleges.create')->only(['create','store']);
       // $this->middleware('permission:colleges.edit')->only(['edit','update']);
       // $this->middleware('permission:colleges.delete')->only('destroy');
       // $this->middleware('permission:colleges.import')->only('import');
    }
    public function dashboard() {
        return view('admin/dashboard');

    }



    public function login() {
        return view('admin/login');

    }

}

