const API_BASE_URL = 'http://127.0.0.1:8000/api/filme';
    const TOKEN = localStorage.getItem('token') || '';

    async function fetchFilms() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const filmsContainer = document.getElementById('filmsContainer');

        filmsContainer.innerHTML = '';
        loadingIndicator.classList.remove('hidden');
        errorMessage.classList.add('hidden');

        try {
            const response = await fetch(API_BASE_URL, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${TOKEN}`,
                    'Accept': 'application/json'
                }
            });

            const responseBody = await response.text(); 
            loadingIndicator.classList.add('hidden');

            let data;
            try {
                data = JSON.parse(responseBody); // parser JSON manuellement
            } catch (jsonError) {
                throw new Error('Réponse invalide du serveur');
            }

            if (!response.ok) {
                throw new Error(data.message || 'Erreur lors de la récupération des films');
            }

            if (data.status === 'success' && Array.isArray(data.data) && data.data.length > 0) {
                displayFilms(data.data);
            } else {
                errorMessage.classList.remove('hidden');
                errorText.textContent = 'Aucun film trouvé.';
            }

        } catch (error) {
            console.error('Erreur lors de la récupération des films:', error);

            errorMessage.classList.remove('hidden');
            
            if (error.message.includes('401')) {
                errorText.textContent = 'Non authentifié. Veuillez vous connecter.';
            } else if (error.message.includes('403')) {
                errorText.textContent = 'Accès non autorisé.';
            } else {
                errorText.textContent = error.message || 'Une erreur est survenue. Veuillez réessayer.';
            }

            loadingIndicator.classList.add('hidden');
        }
    }

    function displayFilms(films) {
        const filmsContainer = document.getElementById('filmsContainer');
        filmsContainer.innerHTML = '';

        films.forEach(film => {
            const filmCard = document.createElement('div');
            filmCard.classList.add('film-card', 'bg-white', 'rounded-xl', 'shadow-md', 'overflow-hidden');

            // Generate random rating between 3.5 and 5.0
            const rating = (Math.random() * 1.5 + 3.5).toFixed(1);
            
            filmCard.innerHTML = `
                <div class="relative">
                    <img class="w-full h-64 object-cover" 
                        src="${film.image || 'https://via.placeholder.com/400x600'}" 
                        alt="${film.titre}">
                    <div class="absolute top-2 right-2 bg-yellow-400 text-dark font-bold px-2 py-1 rounded-lg flex items-center">
                        <i class="fas fa-star mr-1"></i> ${rating}
                    </div>
                </div>
                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2 text-dark">${film.titre}</h2>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">${film.description || 'Aucune description disponible.'}</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded">${film.genre}</span>
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded">${film.durée} mins</span>
                    </div>
                    <button class="w-full bg-primary hover:bg-secondary text-white font-medium py-2 px-4 rounded-lg transition flex items-center justify-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Plus d'infos
                    </button>
                </div>
            `;

            filmsContainer.appendChild(filmCard);
        });
    }
    

    async function login() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        
        loadingIndicator.classList.remove('hidden');
        errorMessage.classList.add('hidden');
        
        try {
            const response = await fetch('http://127.0.0.1:8000/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: 'votre_email@exemple.com',
                    password: 'votre_mot_de_passe'
                })
            });

            const data = await response.json();
            
            if (data.access_token) {
                localStorage.setItem('token', data.access_token);
                fetchFilms();
                document.getElementById('loginBtn').innerHTML = '<i class="fas fa-user-check mr-2"></i><span>Connecté</span>';
            } else {
                throw new Error('Échec de la connexion');
            }
        } catch (error) {
            console.error('Erreur de connexion:', error);
            errorMessage.classList.remove('hidden');
            errorText.textContent = 'Échec de la connexion. Veuillez réessayer.';
        } finally {
            loadingIndicator.classList.add('hidden');
        }
    }

    document.getElementById('loginBtn').addEventListener('click', login);

    window.onload = () => {
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        
        if (TOKEN) {
            fetchFilms();
            document.getElementById('loginBtn').innerHTML = '<i class="fas fa-user-check mr-2"></i><span>Connecté</span>';
        } else {
            errorMessage.classList.remove('hidden');
            errorText.textContent = 'Veuillez vous connecter pour voir les films.';
        }

        // Simulate films for preview purposes if no token
        if (!TOKEN) {
            simulateFilms();
        }
    };
