<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - Naftal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .background {
            background: url('/image/R.jpg') no-repeat center center/cover;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            filter: blur(6px);
            
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

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
    <div class="background"></div>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        

    <div class="relative z-10 bg-white/70 backdrop-blur-lg shadow-2xl rounded-2xl px-6 py-4 w-full max-w-md mt-6 transition-all duration-500 transform hover:scale-105">
   

    <form method="POST" action="#" class="mt-4 space-y-2">
                <div>
                    <label class="block font-medium text-sm text-gray-700" for="nom">Nom</label>
                    <input class="border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm block mt-1 w-full px-4 py-2 transition duration-300 transform hover:scale-105"
                           id="nom"
                           type="text"
                           name="nom"
                           required>
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700" for="prenom">Prénom</label>
                    <input class="border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm block mt-1 w-full px-4 py-2 transition duration-300 transform hover:scale-105"
                           id="prenom"
                           type="text"
                           name="prenom"
                           required>
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700" for="email">Email</label>
                    <input class="border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm block mt-1 w-full px-4 py-2 transition duration-300 transform hover:scale-105"
                           id="email"
                           type="email"
                           name="email"
                           required>
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700" for="password">Mot de passe</label>
                    <input class="border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm block mt-1 w-full px-4 py-2 transition duration-300 transform hover:scale-105"
                           id="password"
                           type="password"
                           name="password"
                           required>
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700" for="role">Rôle</label>
                    <select id="role" name="role"
                            class="border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm block mt-1 w-full px-4 py-2 transition duration-300 transform hover:scale-105">
                        <option value="employé" selected>Employé</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

               
        <div class="flex justify-end mt-2">
            <button type="submit"
                    class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        Créer le compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
