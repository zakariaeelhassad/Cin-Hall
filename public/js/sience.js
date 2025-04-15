const API_URL = "http://127.0.0.1:8000/api/seance";
const TOKEN = localStorage.getItem("token") || "";

async function fetchSeances() {
    const loading = document.getElementById("loadingIndicator");
    const error = document.getElementById("errorMessage");
    const errorText = document.getElementById("errorText");
    const container = document.getElementById("seancesContainer");

    loading.classList.remove("hidden");
    error.classList.add("hidden");
    container.innerHTML = "";

    try {
        const response = await fetch(API_URL, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${TOKEN}`,
                'Accept': 'application/json'
            }
        });

        const text = await response.text();
        loading.classList.add("hidden");

        let data;
        try {
            data = JSON.parse(text);
        } catch {
            throw new Error("Erreur lors de l'analyse de la réponse JSON.");
        }

        if (!response.ok) {
            throw new Error(data.message || "Erreur serveur.");
        }

        if (data.status === "success" && Array.isArray(data.data)) {
            displaySeances(data.data);
        } else {
            error.classList.remove("hidden");
            errorText.textContent = "Aucune séance trouvée.";
        }
    } catch (err) {
        console.error(err);
        error.classList.remove("hidden");
        errorText.textContent = err.message;
        loading.classList.add("hidden");
        
        // If no data, show demo data
        simulateSeances();
    }
}

function displaySeances(seances) {
    const container = document.getElementById("seancesContainer");
    container.innerHTML = "";

    // Sort seances by date and time
    seances.sort((a, b) => new Date(a.date_heure) - new Date(b.date_heure));

    seances.forEach(seance => {
        // Format date and time
        const dateTime = new Date(seance.date_heure);
        const formattedDate = dateTime.toLocaleDateString('fr-FR', { 
            weekday: 'long', 
            day: 'numeric', 
            month: 'long' 
        });
        const formattedTime = dateTime.toLocaleTimeString('fr-FR', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });

        // Generate random icons for seance type
        let typeIcon, typeBg;
        switch(seance.type.toLowerCase()) {
            case '3d':
                typeIcon = 'fas fa-cube';
                typeBg = 'bg-purple-100 text-purple-800';
                break;
            case 'vf':
                typeIcon = 'fas fa-language';
                typeBg = 'bg-green-100 text-green-800';
                break;
            case 'vo':
            case 'vost':
                typeIcon = 'fas fa-globe';
                typeBg = 'bg-blue-100 text-blue-800';
                break;
            case 'imax':
                typeIcon = 'fas fa-expand';
                typeBg = 'bg-red-100 text-red-800';
                break;
            default:
                typeIcon = 'fas fa-film';
                typeBg = 'bg-gray-100 text-gray-800';
        }

        // Generate random available seats
        const totalSeats = 120;
        const availableSeats = Math.floor(Math.random() * (totalSeats - 20)) + 20;
        const occupancyPercentage = Math.floor((availableSeats / totalSeats) * 100);
        
        let occupancyColor;
        if (occupancyPercentage > 70) {
            occupancyColor = 'bg-green-500';
        } else if (occupancyPercentage > 30) {
            occupancyColor = 'bg-yellow-500';
        } else {
            occupancyColor = 'bg-red-500';
        }

        const card = document.createElement("div");
        card.className = "seance-card bg-white shadow-md rounded-xl overflow-hidden";

        card.innerHTML = `
            <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                <div>
                    <span class="text-gray-500 text-sm">${formattedDate}</span>
                    <h2 class="text-xl font-bold text-dark">Film #${seance.film_id}</h2>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-lg font-semibold text-primary">${formattedTime}</span>
                    <span class="text-sm text-gray-500">Salle ${seance.salle_id}</span>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${typeBg}">
                            <i class="${typeIcon} mr-1"></i>
                            ${seance.type}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Séance #${seance.id}</span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Places disponibles</span>
                        <span class="text-sm font-medium text-gray-700">${availableSeats}/${totalSeats}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="${occupancyColor} h-2 rounded-full" style="width: ${occupancyPercentage}%"></div>
                    </div>
                </div>
                
                <button class="w-full bg-primary hover:bg-secondary text-white font-medium py-2 px-4 rounded-lg transition flex items-center justify-center">
                    <i class="fas fa-ticket-alt mr-2"></i>
                    Réserver
                </button>
            </div>
        `;

        container.appendChild(card);
    });
}

async function login() {
    const loading = document.getElementById("loadingIndicator");
    const error = document.getElementById("errorMessage");
    const errorText = document.getElementById("errorText");
    
    loading.classList.remove("hidden");
    error.classList.add("hidden");
    
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
            fetchSeances();
            document.getElementById('loginBtn').innerHTML = '<i class="fas fa-user-check mr-2"></i><span>Connecté</span>';
        } else {
            throw new Error('Échec de la connexion');
        }
    } catch (error) {
        console.error('Erreur de connexion:', error);
        errorMessage.classList.remove("hidden");
        errorText.textContent = 'Échec de la connexion. Veuillez réessayer.';
    } finally {
        loading.classList.add("hidden");
    }
}

document.getElementById('loginBtn').addEventListener('click', login);

// Function to simulate seances for preview purposes
function simulateSeances() {
    const today = new Date();
    const demoSeances = [];
    
    // Generate 9 demo seances
    for (let i = 1; i <= 9; i++) {
        // Random hour between 10 and 22
        const hour = Math.floor(Math.random() * 12) + 10;
        // Random minute (00, 15, 30, 45)
        const minute = [0, 15, 30, 45][Math.floor(Math.random() * 4)];
        
        // Random date offset (0-6 days from today)
        const dateOffset = Math.floor(Math.random() * 7);
        const seanceDate = new Date(today);
        seanceDate.setDate(today.getDate() + dateOffset);
        seanceDate.setHours(hour, minute, 0);
        
        // Random film ID between 1 and 10
        const filmId = Math.floor(Math.random() * 10) + 1;
        
        // Random salle ID between 1 and 5
        const salleId = Math.floor(Math.random() * 5) + 1;
        
        // Random type
        const types = ['3D', 'VF', 'VOST', 'IMAX', 'Standard'];
        const type = types[Math.floor(Math.random() * types.length)];
        
        demoSeances.push({
            id: i,
            film_id: filmId,
            salle_id: salleId,
            date_heure: seanceDate.toISOString(),
            type: type
        });
    }
    
    displaySeances(demoSeances);
}

window.onload = () => {
    const error = document.getElementById("errorMessage");
    const errorText = document.getElementById("errorText");
    
    if (TOKEN) {
        fetchSeances();
        document.getElementById('loginBtn').innerHTML = '<i class="fas fa-user-check mr-2"></i><span>Connecté</span>';
    } else {
        error.classList.remove("hidden");
        errorText.textContent = "Veuillez vous connecter pour voir les séances.";
        
        // Show demo seances for preview purposes
        simulateSeances();
    }
};
