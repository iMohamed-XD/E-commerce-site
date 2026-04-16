<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class SupportController extends Controller
{
    public function index(): InertiaResponse
    {
        $paymentMethods = PaymentMethod::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function (PaymentMethod $pm) {
                return [
                    'id' => $pm->id,
                    'name' => $pm->name,
                    'account_id' => $pm->account_id,
                    'details' => $pm->details,
                    'link' => $pm->link,
                    'logo_url' => $pm->logo_path ? Storage::url($pm->logo_path) : null,
                    'qr_url' => $pm->qr_path ? Storage::url($pm->qr_path) : null,
                ];
            })
            ->values();

        $backUrl = url()->previous();
        if ($backUrl === url()->current()) {
            $backUrl = route('landing');
        }

        return Inertia::render('Support/Index', [
            'paymentMethods' => $paymentMethods,
            'backUrl' => $backUrl,
        ]);
    }
}
