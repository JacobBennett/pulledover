<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\VerifyPhoneNumberOwnership;
use App\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NumbersController extends Controller
{
    public function create()
    {
        return view('numbers.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'number' => 'required|digits:10|integer|unique_number|globally_unique_number|valid_phone'
        ]);

        $number = Auth::user()->phoneNumbers()->create([
            'number' => $request->get('number'),
        ]);

        $this->dispatch(new VerifyPhoneNumberOwnership($number));

        return redirect()->route('dashboard');
    }
}
