
<?php

define('BASE_URL', '/Capa intermedia');

function url(string $ruta = ''): string
{
    return BASE_URL . '/' . ltrim($ruta, '/');
}
