<?php
// app/Http/Controllers/Admin/PaymentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function company()
    {
        $payments = Payment::whereHas('package', function($q) {
            $q->where('type', 'employer');
        })
        ->with(['user', 'package'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        $totalAmount = Payment::whereHas('package', function($q) {
            $q->where('type', 'employer');
        })->where('status', 'completed')->sum('amount');

        return view('admin.payments.company', compact('payments', 'totalAmount'));
    }

    public function seeker()
    {
        $payments = Payment::whereHas('package', function($q) {
            $q->where('type', 'seeker');
        })
        ->with(['user', 'package'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        $totalAmount = Payment::whereHas('package', function($q) {
            $q->where('type', 'seeker');
        })->where('status', 'completed')->sum('amount');

        return view('admin.payments.seeker', compact('payments', 'totalAmount'));
    }

    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }
}
