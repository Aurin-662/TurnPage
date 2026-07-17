@extends('layouts.admin')

@section('title', 'Manage Vouchers — Admin')

@section('styles')
<style>
    .table-card { background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
</style>
@endsection

@section('content')
<div class="container mt-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-weight:700;">Manage Vouchers</h2>
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-dark">+ Create Voucher</a>
    </div>

    <div class="table-card">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Min. Amount</th>
                    <th>Valid From</th>
                    <th>Valid To</th>
                    <th>Usage Limit</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vouchers as $v)
                <tr>
                    <td><strong>{{ $v->voucher_code }}</strong></td>
                    <td>{{ $v->discount_percent }}%</td>
                    <td>Tk. {{ number_format($v->minimum_amount, 0) }}</td>
                    <td>{{ $v->valid_from ? \Carbon\Carbon::parse($v->valid_from)->format('d M Y') : '-' }}</td>
                    <td>{{ $v->valid_to ? \Carbon\Carbon::parse($v->valid_to)->format('d M Y') : '-' }}</td>
                    <td>{{ $v->usage_limit }}</td>
                    <td>
                        @if($v->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.vouchers.toggle', $v->voucher_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm {{ $v->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                {{ $v->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.vouchers.destroy', $v->voucher_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection