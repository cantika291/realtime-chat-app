<x-app-layout>

    <div class="p-6">

        <h1 class="text-2xl font-bold mb-4">
            Realtime Chat
        </h1>

        @foreach($users as $user)

            <div class="border p-3 rounded mb-2">

                {{ $user->name }}

            </div>

        @endforeach

    </div>

</x-app-layout>