// Función para validar el formulario
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });
    
    return isValid;
}

// Función para limpiar el formulario
function clearForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        document.getElementById('form-id').value = '';
    }
}

// Función para mostrar alertas
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Función para eliminar residuo
function deleteWaste(id) {
    if (confirm('¿Está seguro de que desea eliminar este registro?')) {
        fetch(`api/delete_waste.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Registro eliminado correctamente', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('Error al eliminar el registro', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al procesar la solicitud', 'danger');
        });
    }
}

// Función para editar residuo
function editWaste(id) {
    fetch(`api/get_waste.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const waste = data.data;
            document.getElementById('fecha').value = waste.fecha;
            document.getElementById('grado').value = waste.grado;
            document.getElementById('salon').value = waste.salon;
            document.getElementById('tipo_residuo').value = waste.tipo_residuo;
            document.getElementById('peso_kg').value = waste.peso_kg;
            document.getElementById('cantidad').value = waste.cantidad;
            document.getElementById('numero_estudiantes').value = waste.numero_estudiantes;
            document.getElementById('estado_residuo').value = waste.estado_residuo;
            document.getElementById('form-id').value = id;
            
            // Scroll to form
            document.getElementById('waste-form').scrollIntoView({ behavior: 'smooth' });
        }
    })
    .catch(error => console.error('Error:', error));
}

// Clasificación automática de residuos
function classifyWaste() {
    const tipoResiduo = document.getElementById('tipo_residuo').value;
    const estadoResiduo = document.getElementById('estado_residuo').value;
    const clasificacionInput = document.getElementById('clasificacion');
    
    let clasificacion = '';
    
    if (tipoResiduo === 'Papel') {
        clasificacion = estadoResiduo === 'Aprovechable' ? 'Papel limpio - Reciclable' : 'Papel sucio - No aprovechable';
    } else if (tipoResiduo === 'PET') {
        clasificacion = estadoResiduo === 'Aprovechable' ? 'PET limpio - Reciclable' : 'PET sucio - No aprovechable';
    } else if (tipoResiduo === 'Orgánico') {
        clasificacion = 'Orgánico - Compostaje';
    }
    
    if (clasificacionInput) {
        clasificacionInput.value = clasificacion;
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const tipoResiduoSelect = document.getElementById('tipo_residuo');
    const estadoResiduoSelect = document.getElementById('estado_residuo');
    
    if (tipoResiduoSelect) {
        tipoResiduoSelect.addEventListener('change', classifyWaste);
    }
    if (estadoResiduoSelect) {
        estadoResiduoSelect.addEventListener('change', classifyWaste);
    }
});
