<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Facades\Log;

class PagesController extends Controller
{
    public function index()
    {
        Log::info('Pagina principală accesată.');
       return view('pages.home');
    }

    public function about()
    {
        Log::info('Pagina despre noi accesată.');
        return view('pages.about');
    }

    public function login()
    {
        Log::info('Pagina de login accesată.');
        return view('pages.login');
    }

    public function register()
    {
        Log::info('Pagina de înregistrare accesată.');
        return view('pages.register');
    }

}
