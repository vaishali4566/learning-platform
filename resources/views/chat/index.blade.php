@php
    // Detect current logged-in user and layout
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

    $authId = $authUser?->id;

    // Filter out self
    $filteredUsers = $users->filter(function ($u) use ($authId, $authType) {
        return !($u && $u->id === $authId && $u->type === $authType);
    });

    // Initialize sections
    $friends = collect();
    $pending = collect();
    $available = collect();

    foreach ($filteredUsers as $user) {
        $key = $user->type . '_' . $user->id;
        $chatRequest = $chatRequests[$key] ?? null;
        $status = $chatRequest?->status ?? null;

        $isSender = $chatRequest && $chatRequest->sender_id == $authId && $chatRequest->sender_type == $authType;
        $isReceiver = $chatRequest && $chatRequest->receiver_id == $authId && $chatRequest->receiver_type == $authType;

        if ($status === 'accepted') {
            $friends->push((object)[
                'user' => $user,
                'chatRequest' => $chatRequest,
                'isSender' => $isSender,
                'isReceiver' => $isReceiver,
                'status' => $status
            ]);
        } elseif ($status === 'pending') {
            $pending->push((object)[
                'user' => $user,
                'chatRequest' => $chatRequest,
                'isSender' => $isSender,
                'isReceiver' => $isReceiver,
                'status' => $status
            ]);
        } else {
            $available->push((object)[
                'user' => $user,
                'chatRequest' => null,
                'isSender' => false,
                'isReceiver' => false,
                'status' => null
            ]);
        }
    }
@endphp

@extends($layout)

@section('content')
<div class="min-h-screen bg-[#0C111D] text-[#E6EDF7] p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-semibold flex items-center gap-3">
            Chat
        </h2>
    </div>

    {{-- Flash messages --}}
    @foreach (['success' => 'green', 'error' => 'red', 'info' => 'yellow'] as $type => $color)
        @if(session($type))
            <div class="bg-{{ $color }}-500/10 border border-{{ $color }}-500 text-{{ $color }}-300 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <!-- 3 SECTIONS -->
    <div class="space-y-8">

        <!-- SECTION 1: FRIENDS (Accepted) -->
        @if($friends->count() > 0)
            <div class="bg-[#121A2F] border border-[#1F2B4A] rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-3 bg-gradient-to-r from-green-600/20 to-emerald-600/20 border-b border-[#1F2B4A]">
                    <h3 class="text-lg font-semibold text-green-400 flex items-center gap-2">
                        Friends ({{ $friends->count() }})
                    </h3>
                </div>
                @foreach($friends as $item)
                    @include('chat.partials.user-item', ['item' => $item])
                @endforeach
            </div>
        @endif

        <!-- SECTION 2: PENDING REQUESTS -->
        @if($pending->count() > 0)
            <div class="bg-[#121A2F] border border-[#1F2B4A] rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-3 bg-gradient-to-r from-yellow-600/20 to-amber-600/20 border-b border-[#1F2B4A]">
                    <h3 class="text-lg font-semibold text-yellow-400 flex items-center gap-2">
                        Pending Requests ({{ $pending->count() }})
                    </h3>
                </div>
                @foreach($pending as $item)
                    @include('chat.partials.user-item', ['item' => $item])
                @endforeach
            </div>
        @endif

        <!-- SECTION 3: AVAILABLE TO CONNECT -->
        @if($available->count() > 0)
            <div class="bg-[#121A2F] border border-[#1F2B4A] rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-3 bg-gradient-to-r from-blue-600/20 to-cyan-600/20 border-b border-[#1F2B4A]">
                    <h3 class="text-lg font-semibold text-blue-400 flex items-center gap-2">
                        Available to Connect ({{ $available->count() }})
                    </h3>
                </div>
                @foreach($available as $item)
                    @include('chat.partials.user-item', ['item' => $item])
                @endforeach
            </div>
        @endif

        @if($friends->count() == 0 && $pending->count() == 0 && $available->count() == 0)
            <div class="text-center py-16 text-gray-500">
                <p class="text-lg">No users available</p>
            </div>
        @endif

    </div>
</div>
@endsection