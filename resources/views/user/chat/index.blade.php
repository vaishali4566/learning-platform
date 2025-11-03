@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-[#0E1625] text-[#E6EDF7] p-6">
    <h2 class="text-2xl font-bold mb-6">💬 Chat Users</h2>

    <!-- 🔔 Flash Messages -->
    @foreach (['success' => 'green', 'error' => 'red', 'info' => 'yellow'] as $type => $color)
        @if(session($type))
            <div class="bg-{{ $color }}-500 text-white p-3 rounded mb-4">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <!-- 👥 All Users -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($users as $user)
            <div class="bg-[#141C33] p-4 rounded-lg border border-[#26304D] hover:bg-[#1E2A4A] transition">
                <p class="font-semibold text-lg">{{ $user->name }}</p>
                <p class="text-sm text-gray-400">{{ ucfirst($user->role ?? 'user') }}</p>

                @php
                    $chatRequest = $chatRequests[$user->id] ?? null;
                    $status = $chatRequest->status ?? null;
                @endphp

                {{-- ✅ Chat Request Status Logic --}}
                @if($status === 'accepted')
                    <span class="mt-2 inline-block text-green-400 font-medium">✅ Accepted</span>

                @elseif($status === 'declined')
                    <span class="mt-2 inline-block text-red-400 font-medium">❌ Declined</span>

                @elseif($status === 'pending' && $chatRequest && $chatRequest->receiver_id == auth()->id())
                    <!-- 📥 Accept / Decline Buttons -->
                    <div class="flex space-x-2 mt-2">
                        <form action="{{ route('chat.accept', $chatRequest->id) }}" method="POST">
                            @csrf
                            <button class="px-3 py-1 bg-yellow-400 text-black font-medium rounded-md hover:bg-yellow-300 transition">
                                Accept
                            </button>
                        </form>

                        <form action="{{ route('chat.decline', $chatRequest->id) }}" method="POST">
                            @csrf
                            <button class="px-3 py-1 bg-red-500 text-white font-medium rounded-md hover:bg-red-400 transition">
                                Decline
                            </button>
                        </form>
                    </div>

                @elseif($status === 'pending' && $chatRequest && $chatRequest->sender_id == auth()->id())
                    <span class="mt-2 inline-block text-blue-400 font-medium">Request Sent ⏳</span>

                @else
                    <!-- 📤 Send Chat Request -->
                    <form action="{{ route('user.chat.request', $user->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <input type="hidden" name="receiver_type" value="{{ $user->role ?? 'user' }}">
                        <button class="mt-2 px-3 py-1 bg-[#00C3FF] text-black font-medium rounded-md hover:bg-[#00A6E0] transition">
                            Send Chat Request
                        </button>
                    </form>
                @endif

                {{-- ✅ Optionally show "Open Chat" for accepted users --}}
                @if($status === 'accepted')
                    <a href="{{ route('user.chat.room', ['id' => $user->id]) }}"
                       class="mt-2 inline-block px-3 py-1 bg-green-500 text-black font-medium rounded-md hover:bg-green-400 transition">
                        Open Chat
                    </a>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
