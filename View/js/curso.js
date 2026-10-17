
/* =========================================
   DATOS DE PRUEBA DEL CURSO
========================================= */

const courseModules = [
    {
        id: 1,
        title: "Módulo 1: Introducción",
        lessons: [
            {
                id: 1,
                title: "Bienvenido al curso",
                duration: "04:20",
                seconds: 260,
                summary: "Conoce el curso y sus objetivos.",
                description:
                    "En esta lección conocerás la estructura del curso, " +
                    "los objetivos de aprendizaje y las herramientas " +
                    "que utilizaremos."
            },
            {
                id: 2,
                title: "Preparando el entorno",
                duration: "08:15",
                seconds: 495,
                summary: "Prepara las herramientas de trabajo.",
                description:
                    "Aprende a preparar tu entorno de desarrollo " +
                    "para empezar a crear tus primeras páginas web."
            }
        ]
    },
    {
        id: 2,
        title: "Módulo 2: HTML y CSS",
        lessons: [
            {
                id: 3,
                title: "Introducción a HTML",
                duration: "12:30",
                seconds: 750,
                summary: "Descubre la estructura de una página web.",
                description:
                    "Conoce las etiquetas HTML, la estructura del " +
                    "documento y los elementos básicos de una página."
            },
            {
                id: 4,
                title: "Textos, enlaces e imágenes",
                duration: "10:45",
                seconds: 645,
                summary: "Aprende a organizar el contenido.",
                description:
                    "Trabaja con encabezados, párrafos, enlaces " +
                    "e imágenes para construir contenido web."
            },
            {
                id: 5,
                title: "Primeros pasos con CSS",
                duration: "15:10",
                seconds: 910,
                summary: "Dale estilo a tu página.",
                description:
                    "Aprende a seleccionar elementos y aplicar " +
                    "colores, márgenes, tamaños y tipografía."
            }
        ]
    },
    {
        id: 3,
        title: "Módulo 3: JavaScript",
        lessons: [
            {
                id: 6,
                title: "Introducción a JavaScript",
                duration: "11:20",
                seconds: 680,
                summary: "Añade interactividad a tu web.",
                description:
                    "Descubre cómo JavaScript permite responder " +
                    "a las acciones del usuario y modificar la página."
            },
            {
                id: 7,
                title: "Eventos y botones",
                duration: "14:05",
                seconds: 845,
                summary: "Responde a las acciones del usuario.",
                description:
                    "Aprende a escuchar eventos y ejecutar funciones " +
                    "cuando el usuario interactúa con la interfaz."
            },
            {
                id: 8,
                title: "Proyecto final",
                duration: "20:00",
                seconds: 1200,
                summary: "Combina todo lo que has aprendido.",
                description:
                    "Construye un pequeño proyecto web utilizando " +
                    "HTML, CSS y JavaScript."
            }
        ]
    }
];


/* =========================================
   CONFIGURACIÓN
========================================= */

// Vídeo de demostración.
// Sustitúyelo por la URL real de cada lección.
const DEMO_VIDEO =
    "https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.mp4";

const STORAGE_KEY = "miacademia-curso-web-progreso";


/* =========================================
   ELEMENTOS DEL DOM
========================================= */

const video = document.querySelector("#lesson-video");
const videoSource = document.querySelector("#lesson-source");

const lessonTitle = document.querySelector("#lesson-title");
const lessonModule = document.querySelector("#lesson-module");
const lessonDuration = document.querySelector("#lesson-duration");
const lessonSummary = document.querySelector("#lesson-summary");
const lessonDescription = document.querySelector("#lesson-description");

const curriculum = document.querySelector("#course-curriculum");

const progressText = document.querySelector("#course-progress-text");
const progressPercent = document.querySelector("#course-progress-percent");
const progressBar = document.querySelector("#course-progress-bar");
const progressElement = document.querySelector("#course-progress");

const lessonCount = document.querySelector("#course-lesson-count");

const previousButton = document.querySelector("#previous-lesson");
const nextButton = document.querySelector("#next-lesson");
const completeButton = document.querySelector("#complete-lesson");
const toggleCurriculumButton =
    document.querySelector("#toggle-curriculum");


/* =========================================
   ESTADO
========================================= */

const allLessons = courseModules.flatMap(module =>
    module.lessons.map(lesson => ({
        ...lesson,
        moduleId: module.id,
        moduleTitle: module.title
    }))
);

let currentLessonIndex = 0;
let completedLessons = new Set();
let collapsedModules = new Set();
let curriculumCollapsed = false;


/* =========================================
   PERSISTENCIA LOCAL
========================================= */

function loadProgress() {
    try {
        const saved = localStorage.getItem(STORAGE_KEY);

        if (!saved) return;

        const data = JSON.parse(saved);

        if (Array.isArray(data.completedLessons)) {
            completedLessons = new Set(
                data.completedLessons.filter(id =>
                    allLessons.some(lesson => lesson.id === id)
                )
            );
        }

        const savedIndex = Number(data.currentLessonIndex);

        if (
            Number.isInteger(savedIndex) &&
            savedIndex >= 0 &&
            savedIndex < allLessons.length
        ) {
            currentLessonIndex = savedIndex;
        }
    } catch (error) {
        console.warn("No se pudo cargar el progreso del curso.", error);
    }
}

function saveProgress() {
    try {
        localStorage.setItem(
            STORAGE_KEY,
            JSON.stringify({
                completedLessons: [...completedLessons],
                currentLessonIndex
            })
        );
    } catch (error) {
        console.warn("No se pudo guardar el progreso del curso.", error);
    }
}


/* =========================================
   RENDERIZAR TEMARIO
========================================= */

function renderCurriculum() {
    curriculum.innerHTML = courseModules.map(module => {
        const moduleCompleted = module.lessons.filter(lesson =>
            completedLessons.has(lesson.id)
        ).length;

        const lessonsHTML = module.lessons.map(lesson => {
            const index = allLessons.findIndex(
                item => item.id === lesson.id
            );

            const isActive = index === currentLessonIndex;
            const isCompleted = completedLessons.has(lesson.id);

            let icon = "▷";

            if (isCompleted) {
                icon = "✓";
            } else if (isActive) {
                icon = "▶";
            }

            return `
                <button
                    type="button"
                    class="course-lesson
                        ${isActive ? "is-active" : ""}
                        ${isCompleted ? "is-completed" : ""}"
                    data-lesson-id="${lesson.id}"
                    ${isActive ? 'aria-current="step"' : ""}
                    aria-label="${lesson.title}${isCompleted
                        ? ", completada"
                        : ""}">

                    <span class="course-lesson__icon"
                          aria-hidden="true">${icon}</span>

                    <span class="course-lesson__details">
                        <span class="course-lesson__name">
                            ${lesson.title}
                        </span>

                        <span class="course-lesson__duration">
                            ${lesson.duration}
                        </span>
                    </span>
                </button>
            `;
        }).join("");

        const isCollapsed = collapsedModules.has(module.id);

        return `
            <section class="course-module">

                <div class="course-module__heading">
                    <h3>${module.title}</h3>

                    <span class="course-module__count">
                        ${moduleCompleted}/${module.lessons.length}
                    </span>

                    <button
                        type="button"
                        class="icon-button"
                        data-module-id="${module.id}"
                        aria-expanded="${!isCollapsed}"
                        aria-label="${isCollapsed
                            ? "Expandir"
                            : "Contraer"} ${module.title}">
                        ${isCollapsed ? "+" : "−"}
                    </button>
                </div>

                <div class="course-module__lessons"
                     ${isCollapsed ? "hidden" : ""}>
                    ${lessonsHTML}
                </div>

            </section>
        `;
    }).join("");
}


/* =========================================
   ACTUALIZAR PROGRESO
========================================= */

function updateProgress() {
    const total = allLessons.length;
    const completed = completedLessons.size;

    const percentage = total > 0
        ? Math.round((completed / total) * 100)
        : 0;

    progressText.textContent =
        `${completed} de ${total} lecciones completadas`;

    progressPercent.textContent =
        `${percentage} % completado`;

    progressBar.style.width = `${percentage}%`;

    progressElement.setAttribute(
        "aria-valuenow",
        String(percentage)
    );

    lessonCount.textContent = `${total} lecciones`;

    if (completedLessons.has(
        allLessons[currentLessonIndex].id
    )) {
        completeButton.textContent = "✓ Lección completada";
        completeButton.setAttribute("aria-pressed", "true");
    } else {
        completeButton.textContent = "Marcar como completada";
        completeButton.setAttribute("aria-pressed", "false");
    }

    completeButton.setAttribute("aria-pressed",
        String(completedLessons.has(
            allLessons[currentLessonIndex].id
        ))
    );

    if (completed === total) {
        progressText.textContent =
            "¡Enhorabuena! Has completado el curso.";
    }
}


/* =========================================
   ABRIR UNA LECCIÓN
========================================= */

function openLesson(index) {
    if (index < 0 || index >= allLessons.length) {
        return;
    }

    currentLessonIndex = index;

    const lesson = allLessons[index];

    lessonTitle.textContent = lesson.title;
    lessonModule.textContent =
        `MÓDULO ${lesson.moduleId}`;

    lessonDuration.textContent = lesson.duration;
    lessonSummary.textContent = lesson.summary;
    lessonDescription.textContent = lesson.description;

    // Reinicia el reproductor al cambiar de lección.
    // Cambia DEMO_VIDEO por la URL de vídeo correspondiente
    // cuando conectes los datos reales del curso.
    video.pause();
    videoSource.src = lesson.videoUrl || DEMO_VIDEO;
    video.load();

    previousButton.disabled = index === 0;
    nextButton.disabled = index === allLessons.length - 1;

    renderCurriculum();
    updateProgress();
    saveProgress();
}


/* =========================================
   EVENTOS DEL TEMARIO
========================================= */

curriculum.addEventListener("click", event => {
    const lessonButton = event.target.closest("[data-lesson-id]");
    const moduleButton = event.target.closest("[data-module-id]");

    if (lessonButton) {
        const id = Number(lessonButton.dataset.lessonId);

        const index = allLessons.findIndex(
            lesson => lesson.id === id
        );

        openLesson(index);
        return;
    }

    if (moduleButton) {
        const id = Number(moduleButton.dataset.moduleId);

        if (collapsedModules.has(id)) {
            collapsedModules.delete(id);
        } else {
            collapsedModules.add(id);
        }

        renderCurriculum();
    }
});


/* =========================================
   NAVEGACIÓN ENTRE LECCIONES
========================================= */

previousButton.addEventListener("click", () => {
    openLesson(currentLessonIndex - 1);
});

nextButton.addEventListener("click", () => {
    openLesson(currentLessonIndex + 1);
});


/* =========================================
   MARCAR COMO COMPLETADA
========================================= */

completeButton.addEventListener("click", () => {
    const lesson = allLessons[currentLessonIndex];

    if (completedLessons.has(lesson.id)) {
        completedLessons.delete(lesson.id);
    } else {
        completedLessons.add(lesson.id);
    }

    renderCurriculum();
    updateProgress();
    saveProgress();
});


/* =========================================
   CONTRAER EL TEMARIO
========================================= */

toggleCurriculumButton.addEventListener("click", () => {
    curriculumCollapsed = !curriculumCollapsed;

    curriculum.hidden = curriculumCollapsed;

    toggleCurriculumButton.textContent =
        curriculumCollapsed ? "+" : "−";

    toggleCurriculumButton.setAttribute(
        "aria-expanded",
        String(!curriculumCollapsed)
    );

    toggleCurriculumButton.setAttribute(
        "aria-label",
        curriculumCollapsed
            ? "Expandir temario"
            : "Contraer temario"
    );
});


/* =========================================
   INICIALIZACIÓN
========================================= */

loadProgress();
openLesson(currentLessonIndex);