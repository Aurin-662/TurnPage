<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderBy('voucher_id', 'desc')->get();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_code'     => 'required|string|max:50',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'valid_from'       => 'required|date',
            'valid_to'         => 'required|date|after:valid_from',
            'minimum_amount'   => 'required|numeric|min:0',
            'usage_limit'      => 'required|integer|min:1',
        ]);

        // Check duplicate code
        if (Voucher::where('voucher_code', strtoupper($request->voucher_code))->exists()) {
            return back()->withErrors(['voucher_code' => 'This voucher code already exists.']);
        }

        $newId = (Voucher::max('voucher_id') ?? 0) + 1;

        Voucher::create([
            'voucher_id'       => $newId,
            'voucher_code'     => strtoupper($request->voucher_code),
            'discount_percent' => $request->discount_percent,
            'valid_from'       => $request->valid_from,
            'valid_to'         => $request->valid_to,
            'minimum_amount'   => $request->minimum_amount,
            'usage_limit'      => $request->usage_limit,
            'is_active'        => 1,
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher created successfully!');
    }

    public function toggleStatus($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->is_active = $voucher->is_active ? 0 : 1;
        $voucher->save();

        $status = $voucher->is_active ? 'activated' : 'deactivated';
        return back()->with('success', 'Voucher ' . $status . ' successfully.');
    }

    public function destroy($id)
    {
        Voucher::where('voucher_id', $id)->delete();
        return back()->with('success', 'Voucher deleted.');
    }
}