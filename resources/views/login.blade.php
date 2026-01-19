<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cert System Login</title>
    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<a href="{{ route('user.dashboard') }}"
    class="fixed top-6 left-6 z-50 inline-flex items-center gap-2
          bg-white/90 backdrop-blur
          text-gray-700 hover:text-blue-600
            rounded-xl font-semibold transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    กลับหน้าหลัก
</a>

<body class="bg-gray-100 font-sans antialiased">

    <div class="h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-sm">
            <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">เข้าสู่ระบบ</h1>

            <!-- Form to handle login submission -->
            <!-- The 'action' attribute points to the login route, and '@csrf' adds a security token -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email"
                        id="email"
                        name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                        required
                        autofocus>
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password"
                        id="password"
                        name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                        required>
                </div>

                <!-- Submit Button -->

                <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out">
                    Login
                </button>
            </form>
        </div>
    </div>

</body>

</html>