<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bug;
// mail
use Illuminate\Support\Facades\Mail;
use App\Mail\BugFeedback;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','isUser'],['except' => [
            'bug'
        ]]);
    }

    public function bug(Request $request)
    {
        if ($request->feedback != "") {
            $data = [
                'app' => env('APP_NAME'),
                'feedback' => $request->feedback,
            ];
            $bugInfo = Bug::create($data);

            Mail::to(env('FEEDBACK_EMAIL'))->send(new BugFeedback($data));
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
