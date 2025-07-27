@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Order #{{ $order->id }}</h2>

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>School Name</label>
            <input type="text" name="school_name" class="form-control" value="{{ $order->school_name }}" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="designing" {{ $order->status == 'designing' ? 'selected' : '' }}>In Design</option>
                <option value="production" {{ $order->status == 'production' ? 'selected' : '' }}>In Production</option>
                <option value="complete" {{ $order->status == 'complete' ? 'selected' : '' }}>Complete</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control">{{ $order->remarks }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Order</button>
    </form>
</div>
@endsection
