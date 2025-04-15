<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Hub - Films Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 bg-rose-900 text-white w-64 transform transition-transform duration-300" id="sidebar">
        <div class="p-6">
            <div class="flex items-center mb-8">
                <i class="fas fa-film text-2xl mr-3"></i>
                <h1 class="text-xl font-bold">Cinema Hub</h1>
            </div>
            
            <nav>
                <a href="#" class="flex items-center space-x-3 text-white bg-rose-800 rounded-lg px-4 py-3 mb-2">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="flex items-center space-x-3 text-gray-300 hover:bg-rose-800 rounded-lg px-4 py-3 mb-2">
                    <i class="fas fa-film"></i>
                    <span>Films</span>
                </a>
                <a href="#" class="flex items-center space-x-3 text-gray-300 hover:bg-rose-800 rounded-lg px-4 py-3 mb-2">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Tickets</span>
                </a>
                <a href="#" class="flex items-center space-x-3 text-gray-300 hover:bg-rose-800 rounded-lg px-4 py-3 mb-2">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
                <a href="#" class="flex items-center space-x-3 text-gray-300 hover:bg-rose-800 rounded-lg px-4 py-3 mb-2">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <button id="loginBtn" class="mb-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-sign-in-alt mr-2"></i><span>Se connecter</span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="ml-64">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex justify-between items-center px-6 py-4">
                <div class="flex items-center">
                    <button id="sidebarToggle" class="mr-4 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search..." class="px-4 py-2 rounded-full bg-gray-100 focus:outline-none focus:ring-2 focus:ring-rose-500 w-64">
                        <i class="fas fa-search absolute right-3 top-2.5 text-gray-400"></i>
                    </div>
                    <button class="p-2 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200">
                        <i class="fas fa-bell"></i>
                    </button>
                    <div class="flex items-center space-x-2">
                        <div class="h-8 w-8 rounded-full bg-rose-600 text-white flex items-center justify-center">
                            <span class="font-medium">JD</span>
                        </div>
                        <span class="font-medium text-gray-700">John Doe</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="p-6">
            
            <!-- Films Section -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Films</h3>
                <button id="openModalBtnfilm" class="px-4 py-2 bg-rose-600 text-white rounded-md hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Add New Film
                </button>
            </div>
            
            <p class="text-green-600 mb-4" id="infopara"></p>
            
            <!-- Films Table -->
            <!-- Loading & Error Messages -->
            <div id="loadingIndicator" class="hidden p-4 text-center text-gray-600">Chargement en cours...</div>
            <div id="errorMessage" class="hidden p-4 bg-red-100 border border-red-400 text-red-700 rounded mb-4">
                <span id="errorText">Une erreur est survenue.</span>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Film</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="filmsContainer">

                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-6">
            <!-- Films Section -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent salles</h3>
                <button id="openModalBtnsalle" class="px-4 py-2 bg-rose-600 text-white rounded-md hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Add New salle
                </button>
            </div>
        
            <p class="text-green-600 mb-4" id="infopara"></p>
        
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salle</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacité</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="sallsContainer">
                        <!-- Les salles seront affichées ici -->
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

    <!-- Create Film Modal -->
    <div id="createFilmModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl relative">
            <h2 class="text-xl font-bold mb-4">Ajouter un Film</h2>
            <form id="createFilmForm" enctype="multipart/form-data">
                <div class="grid grid-cols-2 gap-4">
                    <input id="title" name="titre" type="text" placeholder="Titre" required class="border p-2 rounded w-full">
                    <input id="genre" name="genre" type="text" placeholder="Genre" required class="border p-2 rounded w-full">
                    <input id="duration" name="duree" type="number" placeholder="Durée (min)" required class="border p-2 rounded w-full">
                    <input id="minimum_age" name="age_min" type="number" placeholder="Âge minimum" required class="border p-2 rounded w-full">
                    <input id="language" name="langue" type="text" placeholder="Langue" required class="border p-2 rounded w-full">
                    <input id="trailer_url" name="bande_annonce" type="text" placeholder="Lien Bande-Annonce" class="border p-2 rounded w-full">
                </div>
                <textarea id="description" name="description" placeholder="Description" class="border p-2 mt-4 w-full rounded"></textarea>
                <div class="mt-4">
                    <label class="block mb-1">Image</label>
                    <input id="image" type="file" accept="image/*" class="w-full">
                </div>
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" id="cancelBtn" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">Annuler</button>
                    <button type="submit" class="bg-rose-600 text-white px-4 py-2 rounded hover:bg-rose-700">Enregistrer</button>
                </div>
                <button type="button" id="closeModalBtnfilm" class="absolute top-2 right-3 text-gray-500 hover:text-black text-xl">&times;</button>
            </form>
        </div>
    </div>

    <div id="createSalleModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl relative">
            <h2 class="text-xl font-bold mb-4">Ajouter un Salle</h2>
            <form id="createsalleForm">
                <div class="grid grid-cols-2 gap-4">
                    <input id="nom" name="nom" type="text" placeholder="Nom de la Salle" required class="border p-2 rounded w-full">
                    <input id="capacite" name="capacite" type="number" placeholder="Capacité" required class="border p-2 rounded w-full">
                    <select id="type" name="type" required class="border p-2 rounded w-full">
                        <option value="">Sélectionner un type</option>
                        <option value="Normale">Normale</option>
                        <option value="VIP">VIP</option>
                    </select>
                </div>
            
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" id="cancelBtn" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">Annuler</button>
                    <button type="submit" class="bg-rose-600 text-white px-4 py-2 rounded hover:bg-rose-700">Enregistrer</button>
                </div>
            
                <button type="button" id="closeModalBtnsalle" class="absolute top-2 right-3 text-gray-500 hover:text-black text-xl">&times;</button>
            </form>            
        </div>
    </div>
    
    <script src="{{ asset('js/affichageFilmDashboard.js') }}"></script>
    <script src="{{ asset('js/ajouterSalle.js') }}"></script>
    <script src="{{ asset('js/ajouterFilm.js') }}"></script>
    <script src="{{ asset('js/affichageSalleDashboard.js') }}"></script>
</body>
</html>