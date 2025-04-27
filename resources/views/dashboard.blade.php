<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centres Naftal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        fadeIn: "fadeIn 1s ease-out",
                        pulse: "pulse 2s infinite",
                    },
                    keyframes: {
                        fadeIn: {
                            "0%": { opacity: "0", transform: "translateY(-10px)" },
                            "100%": { opacity: "1", transform: "translateY(0)" }
                        },
                        pulse: {
                            "0%, 100%": { transform: "scale(1)" },
                            "50%": { transform: "scale(1.05)" }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: url('/image/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .overlay {
            background: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body class="bg-white bg-opacity-20 backdrop-blur-md ">

    <div class="overlay min-h-screen flex flex-col">

        <!-- Header -->
        <header class="bg-white bg-opacity-20 backdrop-blur-md py-4 px-6 fixed top-0 left-0 w-full z-50 animate-fadeIn">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                
                <!-- Search Bar -->
                <div class="relative w-64">
                    <input type="text" placeholder="Rechercher..." 
                        class="w-full pl-12 pr-4 py-2 rounded-xl bg-gray-200 border border-gray-300 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 shadow-sm transition-all duration-300">
                    <div class="absolute left-4 top-2.5 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex space-x-4">
                    <button class="bg-gray-300 hover:bg-gray-400 text-gray-900 px-5 py-2 rounded-lg shadow-md transition-all duration-300 hover:scale-105">
                        Informations
                    </button>

                    
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex flex-col items-center justify-center flex-grow pt-24">
            
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <img src="/image/naftal-logo.jpg" alt="Logo Naftal" class="h-28 shadow-md rounded-lg hover:animate-pulse">
            </div>

            <!-- Centers Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                
                <!-- SIAGE -->
                <a href="#" class="transform transition-all duration-500 hover:scale-105 hover:shadow-2xl animate-fadeIn">
                    <div class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-full shadow-2xl flex items-center justify-center w-48 h-24 transition-all duration-300">
                        <h3 class="text-lg font-semibold tracking-wide text-center">SIEGE</h3>
                    </div>
                </a>

                <!-- AIN-OUSSARA -->
                <a href="#" class="transform transition-all duration-500 hover:scale-105 hover:shadow-2xl animate-fadeIn">
                    <div class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-full shadow-2xl flex items-center justify-center w-48 h-24 transition-all duration-300">
                        <h3 class="text-lg font-semibold tracking-wide text-center">AIN-OUSSARA</h3>
                    </div>
                </a>

                <!-- DJELFA -->
                <a href="#" class="transform transition-all duration-500 hover:scale-105 hover:shadow-2xl animate-fadeIn">
                    <div class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-full shadow-2xl flex items-center justify-center w-48 h-24 transition-all duration-300">
                        <h3 class="text-lg font-semibold tracking-wide text-center">DJELFA</h3>
                    </div>
                </a>

                <!-- CHIFFA -->
                <a href="#" class="transform transition-all duration-500 hover:scale-105 hover:shadow-2xl animate-fadeIn">
                    <div class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-full shadow-2xl flex items-center justify-center w-48 h-24 transition-all duration-300">
                        <h3 class="text-lg font-semibold tracking-wide text-center">CHIFFA</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>

</body>
</html>
