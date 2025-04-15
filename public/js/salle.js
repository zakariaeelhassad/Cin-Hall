const API_URL = "http://127.0.0.1:8000/api/salle";
const TOKEN = localStorage.getItem("token") || "";

async function fetchSalles() {
    const loading = document.getElementById("loadingIndicator");
    const error = document.getElementById("errorMessage");
    const errorText = document.getElementById("errorText");
    const container = document.getElementById("sallesContainer");

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
            displaySalles(data.data);
        } else {
            error.classList.remove("hidden");
            errorText.textContent = "Aucune salle trouvée.";
        }
    } catch (err) {
        console.error(err);
        error.classList.remove("hidden");
        errorText.textContent = err.message;
        loading.classList.add("hidden");
        
        // If no data, show demo data
        simulateSalles();
    }
}

function displaySalles(salles) {
    const container = document.getElementById("sallesContainer");
    container.innerHTML = "";

    salles.forEach(salle => {
        // Get appropriate icon and color for the type
        let typeIcon, typeBadge;
        switch(salle.type?.toLowerCase()) {
            case 'imax':
                typeIcon = 'fas fa-expand';
                typeBadge = 'bg-purple-100 text-purple-800';
                break;
            case '3d':
                typeIcon = 'fas fa-cube';
                typeBadge = 'bg-blue-100 text-blue-800';
                break;
            case 'vip':
                typeIcon = 'fas fa-star';
                typeBadge = 'bg-yellow-100 text-yellow-800';
                break;
            case 'premium':
                typeIcon = 'fas fa-crown';
                typeBadge = 'bg-amber-100 text-amber-800';
                break;
            case 'standard':
            default:
                typeIcon = 'fas fa-chair';
                typeBadge = 'bg-green-100 text-green-800';
        }
        
        // Generate random features
        const features = [
            { name: "Son Dolby Atmos", icon: "fas fa-volume-up" },
            { name: "Écran 4K", icon: "fas fa-tv" },
            { name: "Sièges inclinables", icon: "fas fa-couch" },
            { name: "Accessibilité PMR", icon: "fas fa-wheelchair" }
        ];
        
        // Randomly select 2-3 features
        const numFeatures = Math.floor(Math.random() * 2) + 2;
        const selectedFeatures = [];
        while (selectedFeatures.length < numFeatures) {
            const feature = features[Math.floor(Math.random() * features.length)];
            if (!selectedFeatures.includes(feature)) {
                selectedFeatures.push(feature);
            }
        }
        
        const card = document.createElement("div");
        card.className = "salle-card bg-white shadow-md rounded-xl overflow-hidden";
        
        // Calculate occupancy percentage
        const maxCapacity = 200;
        const capacityPercentage = Math.min(100, Math.round((salle.capacite / maxCapacity) * 100));

        card.innerHTML = `
            <div class="h-40 bg-primary bg-opacity-10 flex items-center justify-center relative">
                <i class="fas fa-film text-primary text-5xl opacity-30"></i>
                <div class="absolute top-0 left-0 m-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${typeBadge}">
                        <i class="${typeIcon} mr-1"></i>
                        ${salle.type || 'Standard'}
                    </span>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-dark">${salle.nom || 'Salle ' + salle.id}</h2>
                    <span class="text-sm text-gray-500">#${salle.id}</span>
                </div>
                
                <div class="mb-4">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Capacité</span>
                        <span class="text-sm font-medium text-gray-700">${salle.capacite} places</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full" style="width: ${capacityPercentage}%"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-2 mb-4">
                    ${selectedFeatures.map(feature => `
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="${feature.icon} text-primary mr-2"></i>
                            <span>${feature.name}</span>
                        </div>
                    `).join('')}
                </div>
                
                <button class="w-full bg-primary hover:bg-secondary text-white font-medium py-2 px-4 rounded-lg transition flex items-center justify-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Voir les séances
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
            fetchSalles();
            document.getElementById('loginBtn').innerHTML = '<i class="fas fa-user-check mr-2"></i><span>Connecté</span>';
        } else {
            throw new Error('Échec de la connexion');
        }
    } catch (error) {
        console.error('Erreur de connexion:', error);
        error.classList.remove("hidden");
        errorText.textContent = 'Échec de la connexion. Veuillez réessayer.';
    } finally {
        loading.classList.add("hidden");
    }
}

document.getElementById('loginBtn').addEventListener('click', login);

// Function to simulate salles for preview purposes
function simulateSalles() {
    const demoSalles = [
        {
            id: 1,
            nom: "Salle Lumière",
            capacite: 180,
            type: "Standard"
        },
        {
            id: 2,
            nom: "Salle Galaxy",
            capacite: 120,
            type: "3D"
        },
        {
            id: 3,
            nom: "Salle Prestige",
            capacite: 60,
            type: "VIP"
        },
        {
            id: 4,
            nom: "Salle IMAX",
            capacite: 200,
            type: "IMAX"
        },
        {
            id: 5,
            nom: "Salle Méliès",
            capacite: 150,
            type: "Standard"
        },
        {
            id: 6,
            nom: "Salle Premium",
            capacite: 80,
            type: "Premium"
        }
    ];
    
    displaySalles(demoSalles);
}

// Filter buttons functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('bg-primary', 'text-white');
            btn.classList.add('bg-gray-200', 'hover:bg-gray-300');
        });
        
        // Add active class to clicked button
        this.classList.remove('bg-gray-200', 'hover:bg-gray-300');
        this.classList.add('bg-primary', 'text-white');
    });
});

window.onload = () => {
    const error = document.getElementById("errorMessage");
    const errorText = document.getElementById("errorText");
    
    if (TOKEN) {
        fetchSalles();
        document.getElementById('loginBtn').innerHTML = '<i class="fas fa-user-check mr-2"></i><span>Connecté</span>';
    } else {
        error.classList.remove("hidden");
        errorText.textContent = "Veuillez vous connecter pour voir les salles.";
        
        // Show demo salles for preview purposes
        simulateSalles();
    }
};