@extends('layouts.trainer.index')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">My Purchased Courses</h2>

    @if($purchases->isEmpty())
        <p>You haven’t purchased any courses yet.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($purchases as $purchase)
                <div class="bg-white rounded shadow p-4">
                    <h3 class="font-bold text-lg">{{ $purchase->course->title }}</h3>
                    <p>Price: ₹{{ $purchase->amount }}</p>
                    <p>Purchased on: {{ $purchase->created_at->format('d M Y') }}</p>
                    <a href="{{ route('trainer.courses.explore', $purchase->course->id) }}" class="text-blue-600 hover:underline">
                        View Course
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
