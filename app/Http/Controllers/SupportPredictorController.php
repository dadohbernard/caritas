<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Member;

// class SupportPredictorController extends Controller
// {
//     /**
//      * Display the predictor page with the list of members needing support
//      */
//     public function index()
//     {
//         // Fetch members who need support
//         $membersNeedingSupport = $this->getMembersNeedingSupport();

//         // Categorize members based on cat_id
//         $patientSupport = $membersNeedingSupport->where('cat_id', 1); // Patient support
//         $studentSupport = $membersNeedingSupport->where('cat_id', 2); // Student support
//         $otherSupport = $membersNeedingSupport->where('cat_id', 3);   // Other support

//         // Return the view with the categorized data
//         return view('manage-predictor.predictor', compact('patientSupport', 'studentSupport', 'otherSupport'));
//     }

//     /**
//      * Fetch members needing support with additional fields
//      */
//     private function getMembersNeedingSupport()
//     {
//         // Fetch members needing support with the required fields
//         return Member::where('support_status', 2) // Assuming support_status = 2 means needing support
//                      ->select('first_name', 'last_name', 'cat_id', 'phone', 'income_per_month', 'disability', 'parent_status', 'house', 'disability_type') // Select additional columns
//                      ->get();
//     }
// }

namespace App\Http\Controllers;

use App\Models\Member;  // Assuming you have a Member model
use Illuminate\Http\Request;

class SupportPredictorController extends Controller
{
    public function index()
    {
        // Fetch real data from the database, without the 'category' column
        $members = Member::select('first_name', 'last_name', 'phone', 'income_per_month', 'disability', 'parent_status', 'house', 'disability_type')
            ->get();

        // Pass the members data to the view
        return view('manage-predictor.predictor', compact('members')); // Passing $members to the view
    }
}
