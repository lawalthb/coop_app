<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function services()
    {
        return view('pages.services');
    }

    public function resources()
    {
        return view('pages.resources');
    }

    public function events()
    {
        return view('pages.events');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
