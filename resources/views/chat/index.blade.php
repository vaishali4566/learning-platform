@php
    // 🔹 Detect current logged-in user and layout
    if(auth()->guard('trainer')->check()) {
        $authUser = auth()->guard('trainer')->user();
        $authType = 'trainer';
        $layout = 'layouts.trainer.index';
    } elseif(auth()->check() && auth()->user()->is_admin == 1) {
        $authUser = auth()->user();
        $authType = 'admin';
        $layout = 'layouts.admin.index';
    } elseif(auth()->check()) {
        $authUser = auth()->user();
        $authType = 'user';
        $layout = 'layouts.user.index';
    } else {
        $authUser = null;
        $authType = 'guest';
        $layout = 'layouts.user.index';
    }

    $authId = $authUser->id ?? null;
@endphp

@extends($layout)

@section('content')
<div class="min-h-screen bg-[#0C111D] text-[#E6EDF7] p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-semibold flex items-center gap-3">
            <i class="fa-solid fa-comments text-[#00C2FF]"></i>
            Chat Users
        </h2>
    </div>

    {{-- ✅ Flash messages --}}
    @foreach (['success' => 'green', 'error' => 'red', 'info' => 'yellow'] as $type => $color)
        @if(session($type))
            <div class="bg-{{ $color }}-500/10 border border-{{ $color }}-500 text-{{ $color }}-300 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    {{-- ✅ Users List --}}
    <div class="bg-[#121A2F] border border-[#1F2B4A] rounded-2xl shadow-lg overflow-hidden divide-y divide-[#1F2B4A]">

        @php
            $filteredUsers = $users->filter(function ($u) use ($authId, $authType) {
                return !($u && $u->id === $authId && $u->type === $authType);
            });
        @endphp

        @forelse($filteredUsers as $user)
            @php
                $key = $user->unique_key ?? ($user->type . '_' . $user->id);
                $chatRequest = $chatRequests[$key] ?? null;
                $status = $chatRequest->status ?? null;
            @endphp

            <div class="flex items-center justify-between px-6 py-4 hover:bg-[#1A2543] transition-all duration-300 group">
                <!-- User Info -->
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-gradient-to-br from-[#00C3FF]/30 to-[#004E92]/30 text-white font-semibold text-lg border border-[#1F2B4A] group-hover:border-[#00C2FF]/50 transition">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#121A2F] rounded-full"></span>
                    </div>
                    <div>
                        <p class="font-medium text-white text-base">{{ $user->name }}</p>
                        <p class="text-sm text-gray-400 capitalize">{{ $user->role ?? 'User' }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    @if($status === 'accepted')
                        <a href="{{ route('chat.room', ['id' => $user->id, 'type' => $user->type]) }}" 
                           class="px-4 py-2 flex items-center gap-2 bg-green-600/90 hover:bg-green-500 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-green-500/20 transition-all duration-300">
                            <i class="fa-solid fa-message"></i> Chat
                        </a>

                    @elseif($status === 'declined')
                        <span class="text-red-400 font-medium text-sm flex items-center gap-2">
                            <i class="fa-solid fa-ban"></i> Declined
                        </span>

                    @elseif($status === 'pending' && $chatRequest && $chatRequest->receiver_id == $authId)
                        <form action="{{ route('chat.accept', $chatRequest->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-3 py-2 flex items-center gap-2 bg-yellow-500 text-black font-medium rounded-lg hover:bg-yellow-400 transition text-sm">
                                <i class="fa-solid fa-check"></i> Accept
                            </button>
                        </form>
                        <form action="{{ route('chat.decline', $chatRequest->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-3 py-2 flex items-center gap-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-500 transition text-sm">
                                <i class="fa-solid fa-xmark"></i> Decline
                            </button>
                        </form>

                    @elseif($status === 'pending' && $chatRequest && $chatRequest->sender_id == $authId)
                        <span class="text-blue-400 font-medium text-sm flex items-center gap-2">
                            <i class="fa-regular fa-clock"></i> Request Sent
                        </span>

                    @else
                        <form action="{{ route('chat.request', ['id' => $user->id, 'type' => $user->type]) }}" method="POST">
                            @csrf
                            <button class="px-4 py-2 flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg text-sm shadow-md hover:shadow-blue-500/20 transition-all duration-300">
                                <i class="fa-solid fa-paper-plane"></i> Request
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-400">
                <i class="fa-solid fa-user-slash text-5xl text-gray-600 mb-4"></i>
                <p class="text-lg font-medium">No users available for chat</p>
                <p class="text-sm text-gray-500 mt-1">Start connecting when users appear online.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
