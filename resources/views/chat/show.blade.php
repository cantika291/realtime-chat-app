<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Chat App</title>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen overflow-hidden bg-gray-200">

<div class="flex h-screen">

    <div class="w-1/4 bg-white border-r flex flex-col">
        <div class="p-5 border-b">
            <h1 class="text-3xl font-bold">Obrolan</h1>
        </div>

        <div class="flex-1 overflow-y-auto">
            @foreach($allUsers as $chatUser)
                <a
                    href="/chat/{{ $chatUser->id }}"
                    class="flex items-center gap-3 p-4 border-b hover:bg-gray-100 {{ $chatUser->id == $user->id ? 'bg-gray-100' : '' }}"
                >
                    <div class="w-12 h-12 rounded-full bg-blue-500 text-white flex items-center justify-center text-xl font-bold">
                        {{ strtoupper(substr($chatUser->name,0,1)) }}
                    </div>
                    <div>
                        <h2 class="font-semibold">{{ $chatUser->name }}</h2>
                        <p class="text-sm text-green-500">online</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="flex-1 flex flex-col">
        <div class="bg-white p-4 border-b flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-blue-500 text-white flex items-center justify-center text-xl font-bold">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div>
                <h1 class="font-bold text-lg">{{ $user->name }}</h1>
                <p class="text-sm text-green-500">online</p>
            </div>
        </div>

        <div id="chat-box" class="flex-1 overflow-y-auto p-6">
            <div id="messages-container" class="flex flex-col justify-end min-h-full space-y-4">
                @foreach($messages as $message)
                    @if($message->sender_id == auth()->id())
                        <div class="flex justify-end">
                            <div class="bg-blue-500 text-white px-5 py-3 rounded-2xl rounded-br-sm max-w-lg shadow">
                                {{ $message->message }}
                            </div>
                        </div>
                    @else
                        <div class="flex justify-start">
                            <div class="bg-white text-black px-5 py-3 rounded-2xl rounded-bl-sm max-w-lg shadow">
                                {{ $message->message }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="bg-white p-4 border-t">
            <form id="chat-form" action="{{ route('chat.send', $user->id) }}" method="POST">
                @csrf
                <div class="flex gap-3">
                    <input
                        id="message-input"
                        type="text"
                        name="message"
                        placeholder="Tulis pesan..."
                        class="flex-1 border rounded-full px-5 py-3 focus:outline-none"
                        required
                        autocomplete="off"
                    >
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full">
                        Kirim
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    const chatBox = document.getElementById('chat-box');
    const messagesContainer = document.getElementById('messages-container');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    
    const currentUserId = {{ auth()->id() }};
    const activeChatUserId = {{ $user->id }};

    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
    window.onload = scrollToBottom;

    // PROSES KIRIM PESAN
    chatForm.addEventListener('submit', function (e) {
        e.preventDefault(); 
        
        const messageText = messageInput.value.trim();
        if (!messageText) return;

        // PERBAIKAN 2: Menggunakan FormData agar terbaca sempurna oleh ChatController standar Laravel kamu
        const formData = new FormData(chatForm);

        // Munculkan gelembung chat pengirim secara instan
        const myMessageHtml = `
            <div class="flex justify-end">
                <div class="bg-blue-500 text-white px-5 py-3 rounded-2xl rounded-br-sm max-w-lg shadow">
                    ${messageText}
                </div>
            </div>
        `;
        messagesContainer.insertAdjacentHTML('beforeend', myMessageHtml);
        scrollToBottom();
        messageInput.value = ''; 

        // Kirim ke server di latar belakang menggunakan FormData
        fetch(chatForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
    });

    // MENDENGARKAN REAL-TIME DARI REVERB
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.private(`chat.user.${currentUserId}`)
                .subscribed(() =>{
                    console.log('SUBSCRIBED KE CHANNEL');
                })
                .listen('.message.sent', (e) => {

                    console.log('EVENT MASUK', e);
                    
                    if (e.message.sender_id == activeChatUserId) {
                        const opponentMessageHtml = `
                            <div class="flex justify-start">
                                <div class="bg-white text-black px-5 py-3 rounded-2xl rounded-bl-sm max-w-lg shadow">
                                    ${e.message.message}
                                </div>
                            </div>
                        `;
                        messagesContainer.insertAdjacentHTML('beforeend', opponentMessageHtml);
                        scrollToBottom();
                    }
                });
        }
    });
</script>

</body>
</html>