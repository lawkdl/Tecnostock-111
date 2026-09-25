/**
 * validaciones.js
 * Validación del lado del cliente, reutilizable en cualquier formulario
 * del proyecto: basta con agregarle class="needs-validation" y novalidate.
 *
 * IMPORTANTE: esto es solo comodidad para el usuario (feedback inmediato).
 * La validación que de verdad protege los datos siempre se repite en PHP
 * (ver guardar.php, actualizar.php, procesar_login.php).
 */
(function () {
    'use strict';

    const formularios = document.querySelectorAll('form.needs-validation');

    Array.prototype.forEach.call(formularios, function (formulario) {
        formulario.addEventListener('submit', function (evento) {
            if (!formulario.checkValidity()) {
                evento.preventDefault();
                evento.stopPropagation();
            }
            formulario.classList.add('was-validated');
        }, false);
    });
})();
