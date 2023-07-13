<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $transactions = Transaction::with('member')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('member', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->get();

        $members = Member::all();

        return view('transactions', compact('transactions', 'members'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'member_name' => 'required',
            'amount' => 'required|numeric',
            'transacted_at' => 'required|date',
            'type' => 'required',
        ]);

        $transaction = new Transaction();
        $transaction->member_id = $request->input('member_id');
        $transaction->amount = $request->input('amount');
        $transaction->transacted_at = $request->input('transacted_at');
        $transaction->type = $request->input('type');
        $transaction->save();

        // Redirect or perform any additional actions

        return redirect()->route('transactions.index');
    }

}
