<?php

/**
 *  FILE HELPERS
 * ::CUSTOM:: FILE
 * VERSION 1.0.0
 * VERSION DE MOD: 1.0.0
 * CREATED: 26-09-2024
 */

/**
 * Genera una URL versionada para un archivo CSS o JS.
 * @param string $filePath Ruta relativa del archivo en el servidor (desde la carpeta public).
 * @return string URL del archivo con un query string de versión.
 */

function versioned_asset($filePath)
{
    $fullPath = public_path($filePath);

    if (file_exists($fullPath)) {
        // Obtener el hash MD5 del archivo
        $version = md5_file($fullPath);

        return asset($filePath) . '?v=' . $version;
    }
    return asset($filePath);
}