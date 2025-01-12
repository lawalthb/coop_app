<?php

namespace App\Http\Controllers;

use App\Models\Faq;

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
        $faqs = Faq::where('status', 'active')
            ->orderBy('order')
            ->get();

        return view('pages.resources', compact('faqs'));
    }


    public function events()
    {
        return view('pages.events');
    }

    public function contact()
    {
        return view('pages.contact');
    }
    public function history()
    {
        return view('pages.history');
    }
}
