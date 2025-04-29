<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class add_bank_accountController extends Controller
{
    //
    public function bank_account(Request $request){
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|size:10',
            'bank_country' => 'required|string|max:10',
            'bank_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        $bankAccount = BankAccount::create([
            'user_id' => $user->id,
            'account_name' => $validated['account_name'],
            'account_number' => Crypt::encryptString($validated['account_number']),
            'bank_country' => $validated['bank_country'],
            'bank_name' => $validated['bank_name'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account added successfully.',
            'data' => [
                'id' => $bankAccount->id,
                'account_name' => $bankAccount->account_name,
                'account_number' => substr($validated['account_number'], -4),
                'bank_name' => $bankAccount->bank_name,
            ]
        ], 201);
    }
}
