<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $page_data = get_option('manage-pages');
        
        // Ensure page_data has headings key
        if (!isset($page_data['headings']) || !is_array($page_data['headings'])) {
            $page_data['headings'] = [];
        }
        
        $general = Option::where('key','general')->first();
        return view('web.contact.index',compact('page_data','general'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'company_name' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Message::create($request->all());

        return response()->json([
            'message'   => __('Your Message Submitted successfully'),
            'redirect'  => route('contact.index')
        ]);
    }
}
