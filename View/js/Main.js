
        /*
         * Mostrar / ocultar contraseña
         */

        const togglePassword =
            document.getElementById("togglePassword");

        const password =
            document.getElementById("password");


        if (togglePassword && password) {

            togglePassword.addEventListener(
                "click",
                function () {

                    const isPassword =
                        password.type === "password";


                    password.type =
                        isPassword
                            ? "text"
                            : "password";


                    togglePassword.textContent =
                        isPassword
                            ? "Ocultar"
                            : "Mostrar";

                }
            );

        }