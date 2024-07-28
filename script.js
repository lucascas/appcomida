// Función para enviar los datos del formulario al servidor y enviar un correo electrónico.
function sendData() {
    const form = document.getElementById('mealPlanForm');
    const formData = new FormData(form);
    const data = {};
    const emailParams = {
        to_email: 'lucas.castillo@gmail.com, lucas.castillo@invera.com.ar'
    };

    // Recorre los campos del formulario y añade al objeto de datos y parámetros de correo.
    for (let [key, value] of formData.entries()) {
        data[key] = value;
        emailParams[key] = value || 'No especificado';
    }

    const now = new Date();
    const days = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado'];
    data.fecha_creacion = `${days[now.getDay()]} ${now.getDate().toString().padStart(2, '0')}/${(now.getMonth() + 1).toString().padStart(2, '0')}/${now.getFullYear()}`;
    emailParams.fecha_creacion = data.fecha_creacion;

    // Asegúrate de que comprar_super esté incluido en los datos
    data.comprar_super = document.getElementById('comprar_super').value || '';

    // Enviar los datos al servidor
    fetch('save_plan.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(result => {
        if (result.message === 'Plan saved successfully') {
            console.log('Plan guardado en la base de datos:', result);
            return emailjs.send('service_0isjz8r', 'template_oe1o3vo', emailParams, 'su7bu8tVLFRR-ssfd');
        } else {
            throw new Error(result.message);
        }
    })
    .then((response) => {
        console.log('Correo enviado con éxito:', response);
        alert('Planificación guardada y enviada con éxito');
    })
    .catch((error) => {
        console.error('Error:', error);
        logError('Error al guardar o enviar la planificación', error);
        alert('Error al guardar o enviar la planificación. Por favor, intente nuevamente.');
    });
}

// Evento DOMContentLoaded para inicializar la página una vez que se cargue el DOM
document.addEventListener('DOMContentLoaded', () => {
    const weekdays = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
    const weekdaysContainer = document.getElementById('weekdays');

    // Genera dinámicamente los campos de entrada para cada día de la semana
    weekdays.forEach(day => {
        const dayCard = `
            <div class="day-card">
                <h2 class="subtitle">${day}</h2>
                <div class="field">
                    <label class="label">Almuerzo:</label>
                    <div class="control">
                        <input class="input" type="text" name="${day.toLowerCase()}_almuerzo">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Cena:</label>
                    <div class="control">
                        <input class="input" type="text" name="${day.toLowerCase()}_cena">
                    </div>
                </div>
            </div>
        `;
        weekdaysContainer.innerHTML += dayCard;
    });

    const form = document.getElementById('mealPlanForm');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        sendData();
    });

    loadComidas();

    // Agregar evento de clic a los botones de categoría
    const categoryButtons = document.querySelectorAll('.category-button');
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            filterComidasByCategory(category);
        });
    });

    document.getElementById('saveCategoryButton').addEventListener('click', saveCategory);
});


// Función para renderizar una comida individual
function renderComida(comida) {
    if (!comida.nombre || !comida.categoria) {
        // Si el nombre o la categoría están indefinidos, no mostrar
        return;
    }

    const comidaElement = document.createElement('div');
    comidaElement.classList.add('column', 'is-one-third');
    comidaElement.innerHTML = `
        <div class="card">
            <div class="card-content">
                <p class="title is-4">${comida.nombre}</p>
                <p class="subtitle is-6">${comida.categoria}</p>
            </div>
            <footer class="card-footer">
                <div class="card-footer-item">
                    <button class="button is-primary add-to-plan">Agregar al plan</button>
                    <button class="button is-info edit-category" data-comida-id="${comida.id}">Editar categoría</button>
                </div>
            </footer>
        </div>
    `;

    // Agregar eventos de clic a los botones de "Agregar al plan" y "Editar categoría"
    comidaElement.querySelector('.add-to-plan').addEventListener('click', () => {
        showPopupForComida(comida.nombre);
    });

    comidaElement.querySelector('.edit-category').addEventListener('click', () => {
        showCategoryPopup(comida.id);
    });

    return comidaElement;
}



// Función para cargar las comidas desde el servidor
function loadComidas() {
    fetch('get_comidas.php')
        .then(response => response.json())
        .then(comidas => {
            window.comidas = comidas;
            renderComidas();
        })
        .catch(error => {
            console.error('Error:', error);
            const comidasList = document.getElementById('comidasList');
            comidasList.innerHTML = '<p>Error al cargar las comidas.</p>';
        });
}


// Función para renderizar todas las comidas
function renderComidas() {
    const comidasList = document.getElementById('comidasList');
    comidasList.innerHTML = '';

    const selectedCategory = document.querySelector('.category-button.is-selected')?.dataset.category;

    // Filtrar y mostrar las comidas según la categoría seleccionada
    window.comidas.forEach(comida => {
        if (!selectedCategory || selectedCategory === 'Ver Todos' || comida.categoria.includes(selectedCategory)) {
            const comidaElement = renderComida(comida);
            if (comidaElement) {
                comidasList.appendChild(comidaElement);
            }
        }
    });
}

// Función para mostrar el popup de agregar comida al plan
function showPopupForComida(comida) {
    const popup = document.getElementById('popupOverlay');
    popup.style.display = 'block';
    
    const form = document.getElementById('addToMenuForm');
    form.onsubmit = function(e) {
        e.preventDefault();
        const selectedSlot = document.querySelector('input[name="menuSlot"]:checked');
        if (selectedSlot) {
            addToPlan(selectedSlot.value, comida);
        }
        popup.style.display = 'none';
    };
}

// Función para agregar una comida al plan
function addToPlan(slot, comida) {
    const input = document.querySelector(`input[name="${slot}"]`);
    if (input) {
        input.value = comida;
    }
}

// Función para mostrar el popup de edición de categoría
function showCategoryPopup(comidaId) {
    const popup = document.getElementById('categoryPopup');
    popup.classList.add('is-active');
    document.getElementById('saveCategoryButton').dataset.comidaId = comidaId;
}

// Función para guardar la categoría de una comida
function saveCategory() {
    const popup = document.getElementById('categoryPopup');
    const checkboxes = document.querySelectorAll('.category-select:checked');
    const categories = Array.from(checkboxes).map(cb => cb.value);
    const comidaId = document.getElementById('saveCategoryButton').dataset.comidaId;

    fetch('update_comida_category.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ comidaId, categories }),
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message);
        loadComidas();
        popup.classList.remove('is-active');
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Agregar eventos de cierre de modal
document.querySelector('.modal-close').addEventListener('click', () => {
    document.getElementById('categoryPopup').classList.remove('is-active');
});
document.querySelector('.modal-background').addEventListener('click', () => {
    document.getElementById('categoryPopup').classList.remove('is-active');
});

// Función para filtrar las comidas por categoría
function filterComidasByCategory(category) {
    document.querySelectorAll('.category-button').forEach(btn => btn.classList.remove('is-selected'));
    const selectedButton = document.querySelector(`.category-button[data-category="${category}"]`);
    if (selectedButton) {
        selectedButton.classList.add('is-selected');
    }
    renderComidas();
}
