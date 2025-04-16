<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Séances de Cinéma</title>
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
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Navigation -->
    @include('layouts.navbar')

    <!-- Hero Section -->
    <div class="bg-dark text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Horaires des Séances</h1>
            <p class="text-lg text-gray-300 max-w-2xl mx-auto">Consultez les horaires de diffusion de tous nos films et réservez vos places dès maintenant.</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pb-12">
        <!-- Loading and Error Messages -->
        <div id="loadingIndicator" class="flex justify-center items-center py-12 hidden">
            <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12"></div>
            <span class="ml-4 text-xl font-medium text-gray-600">Chargement des séances...</span>
        </div>
        
        <div id="errorMessage" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 hidden">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span id="errorText">Veuillez vous connecter pour voir les séances.</span>
            </div>
        </div>

        <!-- Seances Grid -->
        <div id="seancesContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Seances will be inserted here dynamically -->
        </div>
    </div>

    <script src="{{ asset('js/sience.js') }}"></script>
</body>
</html>