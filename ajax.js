document.getElementById('describe-form').addEventListener('submit', async function (e) {
    e.preventDefault(); // Empêche le rechargement
    const formData = new FormData(this);

    try {
        const response = await fetch('discribe.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('describe-receive').innerHTML = result.content;
        } else {
            document.getElementById('describe-receive').innerHTML = "Erreur lors de l'enregistrement.";
        }
    } catch (error) {
        console.error("Erreur AJAX :", error);
        document.getElementById('describe-receive').innerHTML = "Une erreur est survenue.";
    }
});
