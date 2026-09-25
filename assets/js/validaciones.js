/**
 * validaciones.js
 * Validación del lado del cliente para dar retroalimentación inmediata.
 * IMPORTANTE: esto es solo comodidad para el usuario. La validación que
 * de verdad protege los datos siempre se repite en PHP (ver procesar_login.php).
 */
(function () {
    'use strict';

    const formLogin = document.getElementById('formLogin');

    if (formLogin) {
        formLogin.addEventListener('submit', function (evento) {
            if (!formLogin.checkValidity()) {
                evento.preventDefault();
                evento.stopPropagation();
            }
            formLogin.classList.add('was-validated');
        });
    }
})();
