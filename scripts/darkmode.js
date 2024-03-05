        const body = document.body;
        const darkModeButton = document.getElementById('darkModeButton');

        darkModeButton.addEventListener('click', () => {
            body.classList.toggle('bg-gray-800'); // Cambia el color de fondo del body
        });