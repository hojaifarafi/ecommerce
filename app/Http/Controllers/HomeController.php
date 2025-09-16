<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    
    public function index()
    {
        $slides = \App\Models\Slide::where('status', 1)->take(3)->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        $sproducts = Product::WhereNotNull('sale_price')->inRandomOrder()->take(8)->get();
        $fproducts = Product::where('featured', 1)->inRandomOrder()->take(8)->get();
        return view('index', compact('slides', 'categories', 'sproducts', 'fproducts'));
    }
    public function contact()
    {
        return view('contact');
    }
    public function contact_store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits_between:10,11',
            'comment' => 'required',
        ]);

        // Store the contact message logic here
        $contact = new \App\Models\Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->comment = $request->comment;
        $contact->save();
        // For example, you can save it to the database or send an email

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
    public function search($keyword)
    {
        $results = Product::where('name', 'LIKE', "%{$keyword}%")->get()->take(8);
        return response()->json($results);        
    }
}
