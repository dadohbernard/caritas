<?php

namespace App\Http\Controllers\USSD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\User;
use AfricasTalking\SDK\AfricasTalking;

class UssdController extends Controller
{
      // Initialize Africa's Talking API
    public function __construct()
    {
        $username = 'sandbox'; // Use 'sandbox' if testing
        $apiKey = 'atsk_9e5842b80ab30b02762da3dc98e286add4829cc0fa82d314a1ed0dd0edf8744413ab0f3b';
        $this->AT = new AfricasTalking($username, $apiKey);
    }

     public function handleUssd(Request $request)
{
     // Capture user input and USSD session data
    $sessionId = $request->input('sessionId');
    $serviceCode = $request->input('serviceCode');
    $phoneNumber = $request->input('phoneNumber');
    $userResponse = $request->input('text', ''); // Default to empty string
     // Retrieve the user's phone number and text from the request
    $phoneNumber = $request->input('phoneNumber');
    $text = $request->input('text', ''); // Default to empty string if no input

    // Find the user associated with the phone number
    $user = User::where("phone_number", $phoneNumber)->first();

    // Check if the user exists
    if (!$user) {
        return response("CON User not found please update profile with your number\nEND", 200)->header('Content-Type', 'text/plain');
    }

    // Split the input text to handle multiple steps
    $inputSteps = explode('*', $text);

    if (empty($text)) {
        // Main menu
        $response = "CON Welcome to the USSD service\n";
        $response .= "1. Register a new member\n";
        $response .= "2. View all members\n";
        return response($response, 200)->header('Content-Type', 'text/plain');
    } elseif ($inputSteps[0] == '1') {
        // Handle registration flow
        return $this->registerMember($inputSteps, $user);
    } elseif ($inputSteps[0] == '2') {
        // Handle viewing members flow
        return $this->viewMembers($inputSteps, $user);
    }

    // Fallback if invalid option is chosen
    return response("END Invalid option chosen.", 200)->header('Content-Type', 'text/plain');
}

// Handling the Next request
public function handleNext(Request $request)
{
    $page = request('page', 1); // Get current page
    $page++; // Increment the page for the next request

    return redirect()->route('ussd/next', ['page' => $page]); // Redirect with new page number
}
//view member
private function viewMembers($inputSteps, $user)
{
     $request = request();
    // $page = isset($inputSteps[1]) && $inputSteps[1] == 'N' ? $inputSteps[2] : 1;
    $page = 1; // Default page number
    if (isset($inputSteps[1]) && $inputSteps[1] == 'N' && isset($inputSteps[2])) {
        $page = (int)$inputSteps[2]; // Use page number from input
    }
    $membersPerPage = 2; // Number of members per page

    // Fetch members logic...
   // Fetch the user by phone number
    // $user = User::where("phone_number", $request->input('phone_number'))->first();

    // Fetch members with pagination
    // Initialize the page number from the request or default to 1
    $page = $request->input('page', 1);
    $membersPerPage = 2; // Number of members to show per page

    // Check for input 'N' to fetch the next page
    if ($request->input('text') === 'N') {
        $page++; // Increment the page for the next request
    }

    // Fetch members based on user's role and community
    $members = Member::join('categories', 'categories.id', 'members.cat_id')
        ->join('users', 'users.id', 'members.user_id')
        ->join('communities', 'communities.id', 'users.community_id')
        ->select('communities.community_name', 'members.id as memberId', 'users.role',
                 'users.first_name as user_first_name', 'users.last_name as user_last_name',
                 'categories.category_name', 'categories.description as cat_description',
                 'members.first_name', 'members.last_name', 'members.status',
                 'members.phone', 'members.school_name', 'members.hospital',
                 'members.other_support', 'members.sdms_code', 'members.created_at')
        ->where(function ($query) use ($user) {
            if ($user->role != 1 && $user->role != 5) {
                return $query->where('users.community_id', $user->community_id);
            } elseif ($user->role == 4) {
                return $query->where('users.centrale_id', $user->centrale_id);
            }
            return $query; // Just return the query without any additional condition
        })
        ->orderBy('members.updated_at', 'desc')
        ->skip(($page - 1) * $membersPerPage) // Pagination logic
        ->take($membersPerPage)
        ->get();

    // Prepare the USSD response
    $response = "CON Beneficiaries Details:\n"; // Start with CON

    foreach ($members as $m) {
        $response .= "Name: " . $m->user_first_name . " " . $m->user_last_name . "\n";
        $response .= "Community: " . $m->community_name . "\n";
        $response .= "Category: " . $m->category_name . "\n";
        // $response .= "Status: " . ($m->status ? "Active" : "Inactive") . "\n";
        $response .= "Phone: " . $m->phone . "\n";
        $response .= "School: " . ($m->school_name ?: 'N/A') . "\n"; // Provide default value if null
        $response .= "Hospital: " . ($m->hospital ?: 'N/A') . "\n"; // Provide default value if null
        // $response .= "Created At: " . $m->created_at->format('Y-m-d H:i:s') . "\n\n";
    }

    // Check if there are more members to display
    if (count($members) == $membersPerPage) {
        $response .= "Reply with 'N' for more beneficiaries.\n"; // Prompt for next
    } else {
        $response .= "END No more beneficiaries to display.\n";
    }

    return response($response, 200)->header('Content-Type', 'text/plain');
}
//register
private function registerMember($inputSteps, $user)
{
    // Step 1: Ask for the first name
    if (count($inputSteps) == 1) {
        return response("CON Enter the new beneficiary's first name:", 200)->header('Content-Type', 'text/plain');
    }

    // Step 2: Ask for the last name
    if (count($inputSteps) == 2) {
        return response("CON Enter the new beneficiary's last name:", 200)->header('Content-Type', 'text/plain');
    }

    // Step 3: Ask for the category (1: Patient, 2: Student, 3: Other)
    if (count($inputSteps) == 3) {
        return response("CON Enter the category (1: Patient, 2: Student, 3: Other):", 200)->header('Content-Type', 'text/plain');
    }

    // Step 4: Ask for the phone number
    if (count($inputSteps) == 4) {
        return response("CON Enter the new beneficiary's phone number:", 200)->header('Content-Type', 'text/plain');
    }

    // Step 5: Ask for the address
    if (count($inputSteps) == 5) {
        return response("CON Enter the new beneficiaryr's address:", 200)->header('Content-Type', 'text/plain');
    }

    // Step 6: Ask for the date of birth (DOB)
    if (count($inputSteps) == 6) {
        return response("CON Enter the new beneficiary's Date of Birth (YYYY-MM-DD):", 200)->header('Content-Type', 'text/plain');
    }

    // Step 7: Ask for the description
    if (count($inputSteps) == 7) {
        return response("CON Enter a brief description of the beneficiary:", 200)->header('Content-Type', 'text/plain');
    }

    // Step 8: Ask for hospital, school details, or other support based on the category
    $catId = isset($inputSteps[3]) ? $inputSteps[3] : null;

    if ($catId == 1 && count($inputSteps) == 8) {
        return response("CON Enter the hospital name:", 200)->header('Content-Type', 'text/plain');
    } elseif ($catId == 2 && count($inputSteps) == 8) {
        return response("CON Enter the school name:", 200)->header('Content-Type', 'text/plain');
    } elseif ($catId == 2 && count($inputSteps) == 9) {
        return response("CON Enter the SDMS code:", 200)->header('Content-Type', 'text/plain');
    } elseif ($catId == 3 && count($inputSteps) == 8) {
        return response("CON Enter the other support details:", 200)->header('Content-Type', 'text/plain');
    }

    // Step 9: Process the registration
    if (($catId == 1 && count($inputSteps) == 9) ||
        ($catId == 2 && count($inputSteps) == 10) ||
        ($catId == 3 && count($inputSteps) == 9)) {

        // Create a new member using the provided details
        $member = new Member();
        $member->first_name = $inputSteps[1];  // First name
        $member->last_name = $inputSteps[2];   // Last name
        $member->cat_id = $catId;              // Category ID
        $member->phone = $inputSteps[4];       // Phone number
        $member->address = $inputSteps[5];     // Address
        $member->bod = $inputSteps[6];         // Date of birth
        $member->description = $inputSteps[7]; // Description

        // Category-specific fields
        if ($catId == 1) {
            $member->hospital = $inputSteps[8]; // Hospital for 'Patient'
        } elseif ($catId == 2) {
            $member->school_name = $inputSteps[8]; // School name for 'Student'
            $member->sdms_code = $inputSteps[9];   // SDMS code for 'Student'
        } elseif ($catId == 3) {
            $member->other_support = $inputSteps[8]; // Other support for 'Other'
        }

        $member->user_id = $user->id;         // Authenticated user's ID
        $member->support_status = 0;
                 // Default support status

        // Save the new member to the database
        try {
            $member->save();
            return response("END New member added successfully.", 200)->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            Log::error('Member registration failed: ' . $e->getMessage());
            return response("END Something went wrong. Please try again.", 200)->header('Content-Type', 'text/plain');
        }
    }

    return response("END Error in registration flow.", 200)->header('Content-Type', 'text/plain');
}
}
