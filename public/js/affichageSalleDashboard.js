const API_URL = "http://127.0.0.1:8000/api/salle";
const TOKEN = localStorage.getItem("token") || "";

async function fetchSalles() {
    const loading = document.getElementById("loadingIndicator");
    const error = document.getElementById("errorMessage");
    const container = document.getElementById("sallsContainer");

    loading.style.display = "block";
    error.textContent = "";
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
        loading.style.display = "none";

        let data;
        try {
            data = JSON.parse(text);
        } catch {
            throw new Error("Erreur lors de l’analyse de la réponse JSON.");
        }

        if (!response.ok) {
            throw new Error(data.message || "Erreur serveur.");
        }

        if (data.status === "success" && Array.isArray(data.data)) {
            displaySalles(data.data);
        } else {
            error.textContent = "Aucune salle trouvée.";
        }
    } catch (err) {
        console.error(err);
        error.textContent = err.message;
        loading.style.display = "none";
    }
}

function displaySalles(salles) {
    const container = document.getElementById("sallsContainer");
    container.innerHTML = ""; // Vider le tableau avant affichage

    salles.forEach(salle => {
        const row = document.createElement("tr");

        const cellNom = document.createElement("td");
        cellNom.className = "px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900";
        cellNom.textContent = salle.nom;

        const cellCapacite = document.createElement("td");
        cellCapacite.className = "px-6 py-4 whitespace-nowrap text-sm text-gray-500";
        cellCapacite.textContent = salle.capacite;

        const cellType = document.createElement("td");
        cellType.className = "px-6 py-4 whitespace-nowrap text-sm text-gray-500";
        cellType.textContent = salle.type;

        row.appendChild(cellNom);
        row.appendChild(cellCapacite);
        row.appendChild(cellType);

        container.appendChild(row);
    });
}

window.onload = () => {
    if (TOKEN) {
        fetchSalles();
    } else {
        document.getElementById("errorMessage").textContent = "Veuillez vous connecter.";
    }
};
