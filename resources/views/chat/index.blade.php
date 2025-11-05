@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-[#0E1625] text-[#E6EDF7] p-6">

    <!-- Page Title -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold">💬 Chat Users</h2>
    </div>

    <!-- Flash Messages -->
    @foreach (['success' => 'green', 'error' => 'red', 'info' => 'yellow'] as $type => $color)
        @if(session($type))
            <div class="bg-{{ $color }}-500/10 border border-{{ $color }}-500 text-{{ $color }}-300 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <!-- Users List -->
    <div class="bg-[#121A2F] border border-[#1F2B4A] rounded-2xl shadow-lg divide-y divide-[#1F2B4A] overflow-hidden">
        @forelse($users as $user)
            @php
                $chatRequest = $chatRequests[$user->id] ?? null;
                $status = $chatRequest->status ?? null;
            @endphp

            <div class="flex items-center justify-between p-4 hover:bg-[#1A2543] transition">
                <div class="flex items-center gap-4">
                    <!-- User Avatar -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-gradient-to-br from-[#00C3FF]/40 to-[#005A9C]/40 text-white font-semibold text-lg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <!-- User Info -->
                    <div>
                        <p class="font-medium text-white">{{ $user->name }}</p>
                        <p class="text-sm text-gray-400">{{ ucfirst($user->role ?? 'user') }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    @if($status === 'accepted')
                        <a href="{{ route('chat.room', ['id' => $user->id]) }}" 
                           class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-500 transition duration-200 shadow-sm">
                            Open Chat
                        </a>

                    @elseif($status === 'declined')
                        <span class="text-red-400 font-medium">Declined</span>

                    @elseif($status === 'pending' && $chatRequest && $chatRequest->receiver_id == auth()->id())
                        <form action="{{ route('chat.accept', $chatRequest->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-4 py-2 bg-yellow-500 text-black font-medium rounded-lg hover:bg-yellow-400 transition duration-200 shadow-sm">
                                Accept
                            </button>
                        </form>
                        <form action="{{ route('chat.decline', $chatRequest->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-500 transition duration-200 shadow-sm">
                                Decline
                            </button>
                        </form>

                    @elseif($status === 'pending' && $chatRequest && $chatRequest->sender_id == auth()->id())
                        <span class="text-blue-400 font-medium">Request Sent ⏳</span>

                    @else
                        <form action="{{ route('chat.request', $user->id) }}" method="POST">
                            @csrf
                            <button class="px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-400 transition duration-200 shadow-sm">
                                Send Request
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-400">No users available for chat.</div>
        @endforelse
    </div>
</div>
@endsection
