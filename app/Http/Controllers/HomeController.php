<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Agency;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the landing page
     */
    public function index()
    {
        // Get featured products for the shop section
        $featuredProducts = Product::where('status', 'active')
            ->take(4)
            ->get();
            
        // Get agencies for the map/locations section
        $agencies = Agency::where('is_active', true)
            ->get();
            
        return view('home', compact('featuredProducts', 'agencies'));
    }
    
    /**
     * Display the about us page
     */
    public function about()
    {
        return view('about');
    }
    
    /**
     * Display the contact page
     */
    public function contact()
    {
        $agencies = Agency::where('is_active', true)
            ->get();
            
        return view('contact', compact('agencies'));
    }
    
    /**
     * Process contact form submission
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Here you would typically send an email or store in database
        // For now, we'll just redirect with a success message
        
        return redirect()->route('contact')->with('success', 'Your message has been sent successfully!');
    }
}
