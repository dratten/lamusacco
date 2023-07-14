<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Transaction;
use AfricasTalking\SDK\AfricasTalking;

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
            'member_id' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required|in:deposit,loan,withdrawal,repayment',
            'transacted_at' => 'required|date',
        ]);

        $transaction = new Transaction();
        $transaction->member_id = $request->input('member_id');
        $transaction->amount = $request->input('amount');
        $transaction->type = $request->input('type');
        $transaction->transacted_at = $request->input('transacted_at');

        $member = Member::find($transaction->member_id);
        // Retrieve the member and their telephone number
        $phoneNumber = $member->mobile_number;

        if ($transaction->type === 'deposit') {
            $member->savings += $transaction->amount;
        } elseif ($transaction->type === 'loan') {
            $member->arrears += $transaction->amount;
        } elseif ($transaction->type === 'withdrawal') {
            if ($member->savings < $transaction->amount) {
                return back()->withInput()->withErrors(['withdrawal' => 'Member account balance is insufficient.']);
            }
            $member->savings -= $transaction->amount;
        } elseif ($transaction->type === 'repayment') {
            if ($member->arrears < $transaction->amount) {
                return back()->withInput()->withErrors(['amount' => 'The repayment amount is greater than the arrears.']);
            }
            $member->arrears -= $transaction->amount;
        }

        // Send SMS notification
        $message = "Dear {$member->name}, your transaction of {$transaction->type} of {$transaction->amount} has been successfully processed.";
        $this->sendSMS($phoneNumber, $message);

        $transaction->save();
        $member->save();

        return redirect()->route('transactions.index')->with('success', 'Transaction saved successfully.');
    }

    public function sendReminders()
    {
        // Get members with arrears greater than zero
        $members = Member::where('arrears', '>', 0)->get();


        foreach ($members as $member) {
            $message = "Dear " .$member->name . ", this is a reminder of your loan dues of Ksh". $member->arrears . " 
            and monthly repayment amounts of Ksh".$member->monthly_payment.".";
        }

        // Send the SMS message to all members with arrears
        $this->sendBulkSMS($members, $message);

        return redirect()->route('transactions.index')->with('success', 'Loan due reminders sent successfully.');
    }


    /**
     * Send bulk SMS using Africa's Talking SMS API.
     *
     * @param Collection $members
     * @param string $message
     */
    private function sendBulkSMS($members, $message)
    {
        $username = 'sandbox'; // Replace with your Africa's Talking username
        $apiKey = '2e32e5bb891ae410df9d9891c243c6439acde9589fb2a1f643050e5fbf0c0e47'; // Replace with your Africa's Talking API key

        $AT = new AfricasTalking($username, $apiKey);
        $sms = $AT->sms();

        $recipients = [];

        foreach ($members as $member) {
            $recipients[] = $member->mobile_number;
        }

        $sms->send([
            'to' => $recipients,
            'message' => $message,
        ]);
    }

    private function sendSMS($phoneNumber, $message)
    {
        $username = 'sandbox'; // Replace with your Africa's Talking username
        $apiKey = '2e32e5bb891ae410df9d9891c243c6439acde9589fb2a1f643050e5fbf0c0e47'; // Replace with your Africa's Talking API key

        $AT = new AfricasTalking($username, $apiKey);
        $sms = $AT->sms();

        $sms->send([
            'to' => $phoneNumber,
            'message' => $message,
        ]);
    }

}
