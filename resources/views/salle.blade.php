<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Salles de Cinéma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e40af',
                        dark: '#0f172a'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .salle-card {
            transition: all 0.3s ease;
        }
        
        .salle-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .tab-button {
            transition: all 0.3s ease;
        }
        
        .loader {
            border-top-color: #3b82f6;
            animation: spinner 1.5s linear infinite;
        }
        
        @keyframes spinner {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-film text-primary text-2xl"></i>
                <span class="font-bold text-xl text-dark">CinémaThèque</span>
            </div>
            <div class="hidden md:flex items-center space-x-6">
                <a href="#" class="text-gray-700 hover:text-primary transition">Accueil</a>
                <a href="#" class="text-gray-700 hover:text-primary transition">Films</a>
                <a href="#" class="text-gray-700 hover:text-primary transition">Séances</a>
                <a href="#" class="text-primary font-medium">Salles</a>
                <a href="#" class="text-gray-700 hover:text-primary transition">Contact</a>
            </div>
            <div class="flex items-center space-x-4">
                <button id="searchBtn" class="p-2 rounded-full hover:bg-gray-100 transition">
                    <i class="fas fa-search text-gray-600"></i>
                </button>
                <button id="loginBtn" class="bg-primary hover:bg-secondary text-white py-2 px-4 rounded-lg transition flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    <span>Connexion</span>
                </button>
            </div>
        </div>
    </nav>

    <div class="bg-dark text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Nos Salles de Cinéma</h1>
            <p class="text-lg text-gray-300 max-w-2xl mx-auto">Découvrez nos salles modernes équipées des meilleures technologies pour une expérience cinématographique exceptionnelle.</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pb-12">
        <!-- Loading and Error Messages -->
        <div id="loadingIndicator" class="flex justify-center items-center py-12 hidden">
            <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12"></div>
            <span class="ml-4 text-xl font-medium text-gray-600">Chargement des salles...</span>
        </div>
        
        <div id="errorMessage" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 hidden">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span id="errorText">Veuillez vous connecter pour voir les salles.</span>
            </div>
        </div>

        <!-- Salles Grid -->
        <div id="sallesContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Salles will be inserted here dynamically -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-gray-300 py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-film text-primary text-2xl"></i>
                        <span class="font-bold text-xl text-white">CinémaThèque</span>
                    </div>
                    <p class="mt-2 text-sm">© 2025 CinémaThèque. Tous droits réservés.</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/salle.js') }}"></script>
</body>
</html>