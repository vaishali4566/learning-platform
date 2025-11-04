@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-[#0E1625] text-[#E6EDF7] p-6">
    <h2 class="text-2xl font-bold mb-6">💬 Chat Users</h2>

    {{-- 🔔 Flash Messages --}}
    @foreach (['success' => 'green', 'error' => 'red', 'info' => 'yellow'] as $type => $color)
        @if(session($type))
            <div class="bg-{{ $color }}-500/20 border border-{{ $color }}-500 text-{{ $color }}-300 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    {{-- 👥 User List --}}
    <div class="bg-[#121A2F] rounded-xl shadow-lg overflow-hidden border border-[#1F2B4A] divide-y divide-[#1F2B4A]">
        @forelse($users as $user)
            @php
                $chatRequest = $chatRequests[$user->id] ?? null;
                $status = $chatRequest->status ?? null;
            @endphp

            <div class="flex items-center justify-between p-4 hover:bg-[#1A2543] transition group">
                <div class="flex items-center gap-4">
                    {{-- 🧑 User Avatar (Initials if no image) --}}
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-[#26304D] text-lg font-semibold group-hover:bg-[#00C3FF]/20 transition">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <div>
                        <p class="font-semibold text-lg">{{ $user->name }}</p>
                        <p class="text-sm text-gray-400">{{ ucfirst($user->role ?? 'user') }}</p>
                    </div>
                </div>

                {{-- 💬 Right Side Actions --}}
                <div class="flex items-center space-x-2">
                    {{-- ✅ Accepted --}}
                    @if($status === 'accepted')
                        <a href="{{ route('chat.room', ['id' => $user->id]) }}"
                           class="px-4 py-1.5 bg-green-500 text-black font-medium rounded-md hover:bg-green-400 transition">
                            Open Chat
                        </a>

                    {{-- ❌ Declined --}}
                    @elseif($status === 'declined')
                        <span class="text-red-400 font-medium">Declined</span>

                    {{-- ⏳ Pending (Receiver) --}}
                    @elseif($status === 'pending' && $chatRequest && $chatRequest->receiver_id == auth()->id())
                        <form action="{{ route('chat.accept', $chatRequest->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-3 py-1.5 bg-yellow-400 text-black font-medium rounded-md hover:bg-yellow-300 transition">
                                Accept
                            </button>
                        </form>
                        <form action="{{ route('chat.decline', $chatRequest->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-3 py-1.5 bg-red-500 text-white font-medium rounded-md hover:bg-red-400 transition">
                                Decline
                            </button>
                        </form>

                    {{-- ⏳ Pending (Sender) --}}
                    @elseif($status === 'pending' && $chatRequest && $chatRequest->sender_id == auth()->id())
                        <span class="text-blue-400 font-medium">Request Sent ⏳</span>

                    {{-- 📤 Send Chat Request --}}
                    @else
                        <form action="{{ route('chat.request', $user->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                            <input type="hidden" name="receiver_type" value="{{ $user->role ?? 'user' }}">
                            <button class="px-4 py-1.5 bg-[#00C3FF] text-black font-medium rounded-md hover:bg-[#00A6E0] transition">
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
