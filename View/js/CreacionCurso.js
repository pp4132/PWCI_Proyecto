
document.addEventListener("DOMContentLoaded", () => {

    const container = document.getElementById("modulesContainer");
    const addModuleButton = document.getElementById("addModule");

    if (!container || !addModuleButton) return;

    // =====================================
    // ACTUALIZAR NUMERACIÓN DE MÓDULOS
    // =====================================

    function actualizarModulos() {

        const modulos = container.querySelectorAll(".module");

        modulos.forEach((modulo, index) => {

            const numero = modulo.querySelector(".module__header span");

            if (numero) {
                numero.textContent = `MÓDULO ${index + 1}`;
            }

            const titulo = modulo.querySelector(
                'input[name$="[titulo]"]'
            );

            if (titulo) {
                titulo.name = `modulos[${index}][titulo]`;
            }

            const lecciones = modulo.querySelectorAll(
                ".module__lessons input"
            );

            lecciones.forEach(leccion => {
                leccion.name = `modulos[${index}][lecciones][]`;
            });

        });
    }

    //Para que le video se visualice

    const videoInput = document.getElementById("video");
    const videoPreview = document.getElementById("videoPreview");
    const videoContainer = document.getElementById("videoPreviewContainer");
    const videoName = document.getElementById("videoName");

    let videoTemporal = null;

    if (videoInput) {

        videoInput.addEventListener("change", () => {

            const archivo = videoInput.files[0];

            if (!archivo) {
                return;
            }

            const tiposPermitidos = [
                "video/mp4",
                "video/webm"
            ];

            if (!tiposPermitidos.includes(archivo.type)) {

                alert("Selecciona un video MP4 o WEBM.");

                videoInput.value = "";

                videoContainer.hidden = true;

                return;
            }

            if (archivo.size > 100 * 1024 * 1024) {

                alert("El video no puede superar los 100 MB.");

                videoInput.value = "";

                videoContainer.hidden = true;

                return;
            }

            if (videoTemporal) {
                URL.revokeObjectURL(videoTemporal);
            }

            videoTemporal = URL.createObjectURL(archivo);

            videoPreview.src = videoTemporal;

            videoName.textContent = archivo.name;

            videoContainer.hidden = false;

        });

    }

    // =====================================
    // CREAR MÓDULO
    // =====================================

    function agregarModulo() {

        const modulo = document.createElement("div");

        modulo.className = "module";

        modulo.innerHTML = `
            <div class="module__header">

                <div>

                    <span>MÓDULO</span>

                    <input
                        type="text"
                        placeholder="Nombre del módulo"
                        maxlength="200"
                        required
                    >

                </div>

                <button
                    type="button"
                    class="module__menu remove-module"
                >
                    ×
                </button>

            </div>

            <div class="module__lessons">

            </div>

            <button
                type="button"
                class="add-lesson"
            >
                ＋ Agregar lección
            </button>
        `;

        container.appendChild(modulo);

        actualizarModulos();

        agregarLeccion(
            modulo.querySelector(".module__lessons")
        );
    }

    // =====================================
    // CREAR LECCIÓN
    // =====================================

    function agregarLeccion(contenedor) {

        const leccion = document.createElement("div");

        leccion.className = "lesson";

        leccion.innerHTML = `
            <span class="lesson__icon">▶</span>

            <input
                type="text"
                placeholder="Nombre de la lección"
                maxlength="200"
                required
            >

            <button
                type="button"
                class="lesson__menu remove-lesson"
            >
                ×
            </button>
        `;

        contenedor.appendChild(leccion);

        actualizarModulos();

        leccion.querySelector("input").focus();
    }

    // =====================================
    // EVENTOS
    // =====================================

    addModuleButton.addEventListener("click", agregarModulo);

    container.addEventListener("click", event => {

        // Agregar lección

        if (event.target.closest(".add-lesson")) {

            const modulo = event.target.closest(".module");

            const lista = modulo.querySelector(".module__lessons");

            agregarLeccion(lista);

        }

        // Eliminar lección

        if (event.target.closest(".remove-lesson")) {

            const leccion = event.target.closest(".lesson");

            const modulo = event.target.closest(".module");

            if (modulo.querySelectorAll(".lesson").length <= 1) {
                alert("Cada módulo debe tener al menos una lección.");
                return;
            }

            leccion.remove();

            actualizarModulos();
        }

        // Eliminar módulo

        if (event.target.closest(".remove-module")) {

            if (container.querySelectorAll(".module").length <= 1) {
                alert("El curso debe tener al menos un módulo.");
                return;
            }

            event.target.closest(".module").remove();

            actualizarModulos();
        }

    });

    actualizarModulos();

});
