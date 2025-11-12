<div class="flex items-center justify-between px-6 py-4 hover:bg-[#1A2543] transition-all duration-300 group">
    <!-- User Info -->
    <div class="flex items-center gap-4">
        <div class="relative">
            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-gradient-to-br from-[#00C3FF]/30 to-[#004E92]/30 text-white font-semibold text-lg border border-[#1F2B4A] group-hover:border-[#00C2FF]/50 transition">
                {{ strtoupper(substr($item->user->name, 0, 1)) }}
            </div>
            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#121A2F] rounded-full"></span>
        </div>
        <div>
            <p class="font-medium text-white text-base">{{ $item->user->name }}</p>
            <p class="text-sm text-gray-400 capitalize">{{ $item->user->role ?? $item->user->type }}</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center gap-2">
        @if($item->status === 'accepted')
            <a href="{{ route('chat.room', ['id' => $item->user->id, 'type' => $item->user->type]) }}" 
               class="px-4 py-2 flex items-center gap-2 bg-green-600/90 hover:bg-green-500 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-green-500/20 transition-all duration-300">
                Chat
            </a>

        @elseif($item->status === 'pending' && $item->isReceiver)
            <form action="{{ route('chat.accept', $item->chatRequest->id) }}" method="POST" class="inline">
                @csrf
                <button class="px-3 py-2 flex items-center gap-2 bg-yellow-500 text-black font-medium rounded-lg hover:bg-yellow-400 transition text-sm">
                    Accept
                </button>
            </form>
            <form action="{{ route('chat.decline', $item->chatRequest->id) }}" method="POST" class="inline">
                @csrf
                <button class="px-3 py-2 flex items-center gap-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-500 transition text-sm">
                    Decline
                </button>
            </form>

       @elseif($item->status === 'pending' && $item->isSender)
        <div class="flex items-center gap-2">
            <span class="text-blue-400 font-medium text-sm flex items-center gap-2">
                Request Sent
            </span>
            <form action="{{ route('chat.cancel', $item->chatRequest->id) }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-3 py-1 bg-red-500 hover:bg-red-400 text-white text-xs font-medium rounded-lg transition">
                    Cancel
                </button>
            </form>
        </div>


        @else
            <form action="{{ route('chat.request', ['id' => $item->user->id, 'type' => $item->user->type]) }}" method="POST">
                @csrf
                <button class="px-4 py-2 flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg text-sm shadow-md hover:shadow-blue-500/20 transition-all duration-300">
                    Request
                </button>
            </form>
        @endif
    </div>
</div>