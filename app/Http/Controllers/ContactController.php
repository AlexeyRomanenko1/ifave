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
        $keywords='ifave, ifave contact';
        $meta_description = 'Explore a world of insights and opinions at ifave.com. Engage in vibrant question surveys spanning diverse categories, and cast your vote on answers that resonate with you. Discover thought-provoking blogs and articles on trending topics within unique locations. Join the conversation, express your views, and be part of a dynamic online community.';
        $page_title='iFave - Contact';
        return view('contact',compact('keywords','meta_description','page_title'));
    }
    public function contact_us(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'subject' => 'required'
        ]);
       //Mail::to('contact@ifave.com')->send(new ContactMail($validatedData['name'], $validatedData['email'], $validatedData['message'], $validatedData['subject']));
        Mail::to('contact@ifave.com')->send(new ContactMail($validatedData['name'], $validatedData['email'], $validatedData['message'], $validatedData['subject']));
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
