<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Services;

use AlfredoMejia\CfdiPhp\Contracts\FileServiceInterface;
use AlfredoMejia\CfdiPhp\Exceptions\CFDIException;

/**
 * Servicio para manejo de archivos
 */
class FileService implements FileServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(string $content, string $path, string $filename): string
    {
        $this->createDirectory($path);
        
        $fullPath = $this->getFullPath($path, $filename);
        
        // Eliminar archivo existente
        if ($this->exists($fullPath)) {
            $this->delete($fullPath);
        }
        
        $result = file_put_contents($fullPath, $content);
        
        if ($result === false) {
            throw new CFDIException("No se pudo guardar el archivo en: {$fullPath}");
        }
        
        return $fullPath;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $filepath): bool
    {
        return file_exists($filepath);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $filepath): bool
    {
        if (!$this->exists($filepath)) {
            return true; // Ya no existe
        }
        
        return unlink($filepath);
    }

    /**
     * {@inheritdoc}
     */
    public function createDirectory(string $path): bool
    {
        if (!is_dir($path)) {
            return mkdir($path, 0777, true);
        }
        
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullPath(string $path, string $filename): string
    {
        $path = rtrim($path, '/\\');
        $filename = ltrim($filename, '/\\');
        
        return $path . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Obtiene la ruta por defecto basada en el RFC del emisor
     */
    public function getDefaultPath(?string $rfc = null): string
    {
        $basePath = $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'] ?? '.';
        $basePath .= DIRECTORY_SEPARATOR . 'Documents';
        
        if ($rfc) {
            $basePath .= DIRECTORY_SEPARATOR . $rfc;
        }
        
        return $basePath . DIRECTORY_SEPARATOR;
    }

    /**
     * Limpia el nombre de archivo para que sea válido
     */
    public function sanitizeFilename(string $filename): string
    {
        // Remover extensión XML si existe
        $filename = str_replace('.xml', '', strtoupper($filename));
        
        // Remover caracteres inválidos
        $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '', $filename);
        
        // Si está vacío, usar un nombre por defecto
        if (empty($filename)) {
            $filename = 'CFDI_' . date('Y-m-d_H-i-s');
        }
        
        return $filename . '.xml';
    }
}
