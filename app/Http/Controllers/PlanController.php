<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Option;

class PlanController extends Controller
{
    public function index()
    {
        $page_data = get_option('manage-pages');
        
        // Ensure page_data has headings key
        if (!isset($page_data['headings']) || !is_array($page_data['headings'])) {
            $page_data['headings'] = [];
        }
        
        $general = Option::where('key','general')->first();
        $plans = Plan::where('status',1)->latest()->get();

        return view('web.plan.index',compact('page_data', 'general', 'plans'));
    }
}
