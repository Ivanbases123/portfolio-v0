document.addEventListener('DOMContentLoaded', function () {
    // Elementos del formulario
    const form = document.getElementById('contactForm');
    const deseoSelect = document.getElementById('deseo');
    const deseoInformacionDiv = document.getElementById('deseo-informacion');
    const informacionSelect = document.getElementById('informacion');
    const politicaCheckbox = document.getElementById('politicaCheckbox');
    const recibirInfoRadios = document.querySelectorAll('input[name="recibir_info"]');
    const enviarButton = document.getElementById('enviarButton');

    // Mostrar/ocultar campo "Deseo información de"
    deseoSelect.addEventListener('change', function () {
        if (this.value === 'Información sobre un servicio') {
            deseoInformacionDiv.style.display = 'block';
            informacionSelect.setAttribute('required', 'required');
        } else {
            deseoInformacionDiv.style.display = 'none';
            informacionSelect.removeAttribute('required');
            informacionSelect.value = '';
        }
        checkFormValidity();
    });

    // Validar el formulario antes de enviar
    form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            alert('Por favor complete todos los campos obligatorios correctamente.');
        }
    });

    // Validar checkbox, radio buttons y campos obligatorios
    function checkFormValidity() {
        const isPolicyChecked = politicaCheckbox.checked;
        const isRadioSelected = [...recibirInfoRadios].some(r => r.checked);
        const isFormValid = form.checkValidity() && isPolicyChecked && isRadioSelected;

        enviarButton.disabled = !isFormValid;
    }

    // Detectar cambios en campos requeridos
    form.querySelectorAll('[required]').forEach(element => {
        element.addEventListener('input', checkFormValidity);
        element.addEventListener('change', checkFormValidity);
    });

    // Escuchar cambios en radios
    recibirInfoRadios.forEach(radio => {
        radio.addEventListener('change', checkFormValidity);
    });

    // Escuchar cambio en el checkbox
    politicaCheckbox.addEventListener('change', checkFormValidity);

    // Validar al cargar
    checkFormValidity();
});

 // Mostrar mensaje al cliente cuando envie el formulario y todo este correcto
window.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);

    if (params.get('success') === '1') {
        alert('Tu solicitud fue enviada correctamente.');
        // Limpiar la URL para que no repita el mensaje al recargar
        history.replaceState(null, '', window.location.pathname);
    }
});


