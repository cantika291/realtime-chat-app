@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col">

    <!-- HEADER -->
    <div class="bg-white shadow p-4">
        <h1 class="text-xl font-bold">💬 Real-Time Chat</h1>
    </div>

    <!-- CHAT BOX -->
    <div id="chat-box" class="flex-1 overflow-y-auto p-4 space-y-2">

        <!-- contoh pesan -->
        <div class="bg-white p-3 rounded shadow w-fit">
            Halo! ini chat pertama 👋
        </div>

    </div>

    <!-- INPUT AREA -->
    <div class="bg-white p-4 flex gap-2 border-t">

        <input 
            id="message"
            type="text"
            placeholder="Tulis pesan..."
            class="flex-1 border rounded p-2"
        >

        <button 
            id="send"
            class="bg-blue-500 text-white px-4 py-2 rounded"
        >
            Kirim
        </button>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const chatBox = document.getElementById('chat-box');
    const input = document.getElementById('message');
    const button = document.getElementById('send');

    console.log(window.Echo);
    

    window.Echo.channel('chat')
    .listen('MessageSent'), (e) => {

         console.log('REALTIME MASUK', e);

         const div = document.createElement('div');

         div.className =
             "bg-white p-3 rounded shadow w-fit";

         div.innerText = e.message.message;

         chatBox.appendChild(div);

         chatBox.scrollTop = chatBox.scrollHeight;

    });
    
      
    button.addEventListener('click', async function () {

        const message = input.value.trim();

        if (!message) return;

        
        const myMessage = document.createElement('div');

        myMessage.className =
            "bg-blue-500 text-white p-3 rounded w-fit ml-auto";

        myMessage.innerText = message;

        chatBox.appendChild(myMessage);

        
        await fetch('/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':
                    document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message: message
            })
        });

        input.value = "";

        chatBox.scrollTop = chatBox.scrollHeight;
    });


</script>
@endpush