<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserMeta;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Validator; // Corrected namespace
use Illuminate\Support\Facades\Session;

class UserDetailsController extends Controller
{
    /**
     * Display the first part of the user details form.
     *
     * @return \Illuminate\View\View
     */
    public function showPart1()
    {
        // Pass any necessary data to the view if needed
        return view('user-details.part1');
    }

    /**
     * Store the first part of the user details form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse | \Illuminate\View\View
     */
    public function storePart1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'marital_status' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'emp_code' => 'required|string|max:255|unique:user_metas,emp_code,' . Auth::id() . ',user_id',
            'position' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Store data in session or temporary storage until part 2 is submitted
        // Or directly update/create UserMeta if preferred
        $validatedData = $validator->validated();
        $validatedData['user_id'] = Auth::id();

        // Option 1: Store in session
        Session::put('user_details_part1', $validatedData);

        // Option 2: Update/Create UserMeta directly (ensure UserMeta model has fillable properties set)
        // UserMeta::updateOrCreate(['user_id' => Auth::id()], $validatedData);

        // Load the second part of the form via AJAX
        return view('user-details.part2'); // Return the view partial for part 2
    }

    /**
     * Store the second part of the user details form (address).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePart2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_type' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            // Handle validation failure, perhaps redirect back with errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Retrieve part 1 data from session (if using session storage)
        $part1Data = Session::get('user_details_part1');

        if (!$part1Data) {
            // Handle error: Part 1 data not found in session
            return redirect()->route('user-details.part1.show')->with('error', 'Please complete the first step first.');
        }

        // Combine data from both parts
        $part2Data = $validator->validated();
        $part2Data['user_id'] = Auth::id();

        // Save Part 1 data (UserMeta)
        UserMeta::updateOrCreate(['user_id' => Auth::id()], $part1Data);

        // Save Part 2 data (UserAddress)
        UserAddress::updateOrCreate(['user_id' => Auth::id()], $part2Data); // Uncommented and activated

        // Clear session data
        Session::forget('user_details_part1');

        // Redirect to a final destination, e.g., home page
        return redirect('/')->with('success', 'User details updated successfully!'); // Changed redirect to home
    }
}