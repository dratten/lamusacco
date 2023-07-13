<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $members = Member::paginate($perPage);
        return view('members', compact('members'));
    }

    public function save(Request $request)
    {
        $data = $request->only(['name', 'mobile_number', 'savings', 'arrears', 'monthly_payment']);

        if (!empty($request->member_id)) {
            $member = Member::findOrFail($request->member_id);
            $member->update($data);
            $message = 'Member updated successfully.';
        } else {
            $member = new Member;
            $member->name = $request->input('name');
            $member->mobile_number = $request->input('mobile_number');
            $member->savings = $request->input('savings');
            $member->arrears = $request->input('arrears');
            $member->monthly_payment = $request->input('monthly_payment');
            $member->save();
            $message = 'Member added successfully.';
        }

        return redirect()->route('members.index')->with('success', $message);
    }

    public function search(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Retrieve the number of records per page from the request
        $search = $request->input('search');
        
        $members = Member::where('name', 'LIKE', "%$search%")
            ->paginate($perPage);

        return view('members', compact('members'));
    }
}