document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById('createFilmModal');
    const openModalBtn = document.getElementById('openModalBtnfilm');
    const closeModalBtn = document.getElementById('closeModalBtnfilm');
    const cancelBtn = document.getElementById('cancelBtn');
    const createFilmForm = document.getElementById("createFilmForm");
    const imageInput = document.getElementById('image');
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

    // Image Preview
    imageInput.addEventListener('change', function () {
        const parent = this.parentElement;
        const existingPreview = parent.querySelector('.preview-image');
        if (existingPreview) existingPreview.remove();

        if (this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.createElement('div');
                preview.className = 'preview-image absolute inset-0 flex items-center justify-center';
                preview.style.backgroundColor = 'rgba(0,0,0,0.5)';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'h-full object-contain p-2';

                preview.appendChild(img);
                parent.style.position = 'relative';
                parent.appendChild(preview);
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Submit Form
    createFilmForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append("titre", document.getElementById("title").value);
        formData.append("genre", document.getElementById("genre").value);
        formData.append("durée", document.getElementById("duration").value);
        formData.append("age_min", document.getElementById("minimum_age").value);
        formData.append("langue", document.getElementById("language").value);
        formData.append("bande_annonce", document.getElementById("trailer_url").value);
        formData.append("description", document.getElementById("description").value);
        if (document.getElementById("image").files[0]) {
            formData.append("image", document.getElementById("image").files[0]);
        }

        try {
            const response = await fetch("http://localhost:8000/api/filme", {
                method: "POST",
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const result = await response.json();

            if (response.status === 201) {
                infoPara.textContent = "Film ajouté avec succès ✅";
                infoPara.classList.remove('text-red-600');
                infoPara.classList.add('text-green-600');
                createFilmForm.reset();
                closeModal();
                fetchFilms(); 
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