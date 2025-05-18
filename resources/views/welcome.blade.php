<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - Naftal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="relative w-full h-screen flex flex-col items-center justify-center bg-cover bg-center overflow-hidden" 
      style="background-image: url('{{ asset('/image/background.webp') }}');">
    
    <!-- Overlay (Blur Effect) -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md"></div>

    <!-- Logo with Soft Entrance & Floating Effect -->
    <img src="{{ asset('image/naftal-logo.jpg') }}" alt="Naftal Logo" 
         class="relative z-10 w-1/3 md:w-1/4 lg:w-1/5 drop-shadow-lg animate-fade-scale animate-float">

    <!-- Connect Button with Modern Hover Effect -->
    
    <a href="{{ route('login') }}"  
     class="relative z-10 mt-8 px-8 py-4 bg-yellow-500 text-blue-900 font-bold text-lg rounded-xl shadow-lg 
          hover:bg-yellow-600 hover:scale-110 transition-all duration-300 animate-pulse">
     Connectez-vous
    </a>


    <!-- CSS Animations -->
    <style>
        /* Fade-In & Scale-Up for Logo */
        @keyframes fadeScale {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-scale { animation: fadeScale 1.5s ease-out; }

        /* Smooth Floating Animation */
        @keyframes floating {
            0% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0); }
        }
        .animate-float { animation: floating 3s infinite ease-in-out; }
    </style>

</body>
</html>
