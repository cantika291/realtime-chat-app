<!DOCTYPE html>
<html>
<head>

    <title>Chat</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

    <div class="max-w-3xl mx-auto mt-10">

        <div class="bg-white p-5 rounded shadow">

            <h1 class="text-2xl font-bold mb-5">
                Daftar User
            </h1>

            @foreach($users as $user)

                <div class="border p-3 rounded mb-3">

                    <a
                        href="/chat/{{ $user->id }}"
                        class="text-blue-500"
                    >
                        <span id="status-{{ $user->id }}">
                            🔴
                        </span>

                        {{ $user->name }}
                    </a>

                </div>

            @endforeach

        </div>

    </div>

@vite(['resources/js/app.js'])

</body>
</html>