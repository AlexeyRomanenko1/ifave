<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('contact');
    }
    public function contact_us(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'subject'=>'required'
        ]);
        Mail::to('contact@ifave.com')->send(new ContactMail($validatedData['name'], $validatedData['email'], $validatedData['message'], $validatedData['subject']));

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
