
/*
 * Mostrar / ocultar contraseñas
 */
function configurarPasswordToggle(
    passwordId,
    toggleId
) {
    const campoPassword =
        document.getElementById(passwordId);

    const botonToggle =
        document.getElementById(toggleId);

    if (!campoPassword || !botonToggle) {
        return;
    }

    botonToggle.addEventListener("click", function () {
        const estaOculta =
            campoPassword.type === "password";

        campoPassword.type = estaOculta
            ? "text"
            : "password";

        botonToggle.textContent = estaOculta
            ? "Ocultar"
            : "Mostrar";

        botonToggle.setAttribute(
            "aria-label",
            estaOculta
                ? "Ocultar contraseña"
                : "Mostrar contraseña"
        );
    });
}
