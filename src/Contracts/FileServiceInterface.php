<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Contracts;

/**
 * Interface para servicios de manejo de archivos
 */
interface FileServiceInterface
{
    /**
     * Guarda contenido en un archivo
     */
    public function save(string $content, string $path, string $filename): string;

    /**
     * Verifica si existe un archivo
     */
    public function exists(string $filepath): bool;

    /**
     * Elimina un archivo
     */
    public function delete(string $filepath): bool;

    /**
     * Crea un directorio si no existe
     */
    public function createDirectory(string $path): bool;

    /**
     * Obtiene la ruta completa de un archivo
     */
    public function getFullPath(string $path, string $filename): string;
}
