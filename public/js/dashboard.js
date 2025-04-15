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
            data = JSON.parse(responseBody);
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
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="h-10 w-16 bg-gray-200 rounded flex-shrink-0 overflow-hidden">
                        <img src="${film.image ? '/storage/' + film.image : 'https://via.placeholder.com/80x100'}" alt="${film.titre}" class="h-full w-full object-cover" />
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">${film.titre}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700">${film.genre}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700">${film.durée} min</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700">${film.classification || 'N/A'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    ${film.status || 'Disponible'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="#" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></a>
                <a href="#" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></a>
            </td>
        `;
        filmsContainer.appendChild(tr);
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
};




