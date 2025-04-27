<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Naftal</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
<div class="relative">
    <!-- Background with blur effect -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('/image/background.jpg'); filter: blur(6px);"></div>

    <!-- Main content container -->
    <div class="relative z-10 min-h-screen flex flex-col items-center justify-center p-4">
        <!-- Logo with floating animation -->
        <div class="relative z-10 flex justify-center animate-float mb-8">
            <img src="/image/naftal-logo.jpg" class="w-24 h-auto">
        </div>
     
        <!-- Login Card -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-2xl p-6 md:p-8 max-w-md w-full transition-all duration-500 transform hover:scale-[1.005]">
           

            @if(session('status'))
                <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
                    <div class="font-bold">Erreurs :</div>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="email">Email </label>
                    <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                           id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           placeholder="votre@email.com">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm md:text-base font-bold text-gray-900 mb-2" for="password">Mot de passe </label>
                    <input class="block w-full px-4 py-3 border-2 border-blue-400 rounded-xl bg-white/90 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-300 font-medium text-gray-800"
                           id="password"
                           type="password"
                           name="password"
                           required
                           placeholder="••••••••">
                </div>

                <!-- Remember me -->
                <div class="flex items-center">
                    <input id="remember_me" 
                           type="checkbox" 
                           class="rounded border-2 border-blue-400 text-blue-600 focus:ring-blue-500 h-5 w-5"
                           name="remember">
                    <label for="remember_me" class="ml-2 text-sm text-gray-700">Se souvenir de moi</label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4">
                    
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition duration-300 transform hover:scale-105">
                        Connexion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>