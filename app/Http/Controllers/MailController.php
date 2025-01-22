<?php

namespace App\Http\Controllers;

use App\Mail\Consolidated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail() {

        $name = "Javier";
        Mail::to('fake@email.com')->send(new Consolidated($name));

    }
}
