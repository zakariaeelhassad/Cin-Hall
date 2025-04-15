document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById('createSalleModal');
    const openModalBtn = document.getElementById('openModalBtnsalle');
    const closeModalBtn = document.getElementById('closeModalBtnsalle');
    const cancelBtn = document.getElementById('cancelBtn');
    const createSalleForm = document.getElementById("createsalleForm");
    const infoPara = document.getElementById('infopara');

    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    // Ouvrir / Fermer modal
    openModalBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    };

    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // Submit Form
    createSalleForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const typeValue = document.getElementById("type").value.trim();
    const validTypes = ['Normale', 'VIP'];

    // Vérification si le type est valide
    if (!validTypes.includes(typeValue)) {
        infoPara.textContent = "Le type sélectionné est invalide. Veuillez choisir entre Normale ou VIP.";
        infoPara.classList.remove('text-green-600');
        infoPara.classList.add('text-red-600');
        return;
    }

    const formData = new FormData();
    formData.append("nom", document.getElementById("nom").value);
    formData.append("capacite", document.getElementById("capacite").value);
    formData.append("type", typeValue);

    try {
        const response = await fetch("http://localhost:8000/api/salle", {
            method: "POST",
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const result = await response.json();

        if (response.status === 201) {
            infoPara.textContent = "Salle ajoutée avec succès ✅";
            infoPara.classList.remove('text-red-600');
            infoPara.classList.add('text-green-600');
            createSalleForm.reset();
            closeModal();
            // fetchSalle(); // Call this if you want to refresh salle list
        } else {
            infoPara.textContent = result.message || "Erreur lors de l'ajout.";
            infoPara.classList.remove('text-green-600');
            infoPara.classList.add('text-red-600');
        }
    } catch (error) {
        console.error("Erreur :", error);
        infoPara.textContent = "Erreur réseau.";
    }
});


});