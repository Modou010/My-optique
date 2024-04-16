
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    form.addEventListener("submit", function(event) {
        let valid = true;

        // Vérification du prénom
        const prenom = document.getElementById('prenom');
        if (prenom.value.trim() === '') {
            alert('Le prénom est requis.');
            valid = false;
        }

        // Vérification du nom
        const nom = document.getElementById('nom');
        if (nom.value.trim() === '') {
            alert('Le nom est requis.');
            valid = false;
        }

        // Vérification de l'email
        const email = document.getElementById('email');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value.trim())) {
            alert('L\'adresse email est invalide.');
            valid = false;
        }

        // Vérification de l'adresse
        const adresse = document.getElementById('adresse');
        if (adresse.value.trim() === '') {
            alert('L\'adresse est requise.');
            valid = false;
        }

        // Vérification du téléphone
        const tel = document.getElementById('tel');
        const telPattern = /^\d{10}$/;
        if (!telPattern.test(tel.value.trim())) {
            alert('Le numéro de téléphone doit contenir 10 chiffres.');
            valid = false;
        }

        if (!valid) {
            event.preventDefault(); 
        }
    });
});