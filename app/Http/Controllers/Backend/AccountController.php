<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::select('user_id', 'balance')->get();
        return view('accounts.index', ['accounts' => $accounts]);
    }
}
