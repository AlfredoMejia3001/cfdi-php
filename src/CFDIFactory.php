<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp;

use AlfredoMejia\CfdiPhp\Builders\CFDI40Builder;
use AlfredoMejia\CfdiPhp\Services\FinkokService;
use AlfredoMejia\CfdiPhp\Services\ValidationService;
use AlfredoMejia\CfdiPhp\Services\XMLGeneratorService;
use AlfredoMejia\CfdiPhp\Services\FileService;
use AlfredoMejia\CfdiPhp\Strategies\Pagos\PagosBuilder;
use AlfredoMejia\CfdiPhp\Strategies\Pagos\PagosStrategy;
use AlfredoMejia\CfdiPhp\Strategies\CartaPorte\CartaPorteBuilder;
use AlfredoMejia\CfdiPhp\Strategies\CartaPorte\CartaPorteStrategy;
use AlfredoMejia\CfdiPhp\Strategies\Nomina\NominaBuilder;
use AlfredoMejia\CfdiPhp\Strategies\Nomina\NominaStrategy;
use AlfredoMejia\CfdiPhp\Strategies\ComercioExterior\ComercioExteriorBuilder;
use AlfredoMejia\CfdiPhp\Strategies\ComercioExterior\ComercioExteriorStrategy;
use AlfredoMejia\CfdiPhp\Contracts\ComplementStrategyInterface;
use AlfredoMejia\CfdiPhp\Models\CFDIData;
use AlfredoMejia\CfdiPhp\Models\ValidationResult;
use AlfredoMejia\CfdiPhp\Exceptions\CFDIException;

/**
 * Factory principal para crear y procesar CFDIs
 * Punto de entrada principal de la librería
 */
class CFDIFactory
{
    private FinkokService $finkokService;
    private ValidationService $validationService;
    private XMLGeneratorService $xmlGeneratorService;
    private FileService $fileService;
    private array $complementStrategies = [];

    public function __construct()
    {
        $this->finkokService = new FinkokService();
        $this->validationService = new ValidationService($this->finkokService);
        $this->xmlGeneratorService = new XMLGeneratorService();
        $this->fileService = new FileService();
    }

    /**
     * Crea un builder para CFDI 4.0 básico
     */
    public function createCFDI40Builder(): CFDI40Builder
    {
        return new CFDI40Builder();
    }

    /**
     * Crea una factura básica de ingreso
     */
    public function createFactura(): CFDI40Builder
    {
        return CFDI40Builder::factura();
    }

    /**
     * Crea una nota de crédito
     */
    public function createNotaCredito(): CFDI40Builder
    {
        return CFDI40Builder::notaCredito();
    }

    /**
     * Crea un comprobante de traslado
     */
    public function createTraslado(): CFDI40Builder
    {
        return CFDI40Builder::createTraslado();
    }
    
    /**
     * Crea un builder para CFDI de Pagos (complemento)
     */
    public function createPagos(): PagosBuilder
    {
        $builder = new PagosBuilder($this->xmlGeneratorService, $this->validationService);
        
        // Configurar la estrategia de pagos en el XMLGenerator
        $pagosStrategy = new PagosStrategy();
        $this->addComplementStrategy($pagosStrategy);
        $this->xmlGeneratorService->setComplementStrategy($pagosStrategy);
        
        return $builder;
    }
    
    /**
     * Crea un builder para CFDI con Carta Porte (complemento)
     */
    public function createCartaPorte(): CartaPorteBuilder
    {
        $builder = new CartaPorteBuilder($this->xmlGeneratorService, $this->validationService);
        
        // Configurar la estrategia de Carta Porte en el XMLGenerator
        $cartaPorteStrategy = new CartaPorteStrategy();
        $this->addComplementStrategy($cartaPorteStrategy);
        $this->xmlGeneratorService->setComplementStrategy($cartaPorteStrategy);
        
        return $builder;
    }
    
    /**
     * Crea un builder para CFDI con Nómina (complemento)
     */
    public function createNomina(): NominaBuilder
    {
        $builder = new NominaBuilder($this->xmlGeneratorService, $this->validationService);
        
        // Configurar la estrategia de Nómina en el XMLGenerator
        $nominaStrategy = new NominaStrategy();
        $this->addComplementStrategy($nominaStrategy);
        $this->xmlGeneratorService->setComplementStrategy($nominaStrategy);
        
        return $builder;
    }
    
    /**
     * Crea un builder para CFDI con Comercio Exterior (complemento)
     */
    public function createComercioExterior(): ComercioExteriorBuilder
    {
        $builder = new ComercioExteriorBuilder($this->xmlGeneratorService, $this->validationService);
        
        // Configurar la estrategia de Comercio Exterior en el XMLGenerator
        $comercioExteriorStrategy = new ComercioExteriorStrategy();
        $this->addComplementStrategy($comercioExteriorStrategy);
        $this->xmlGeneratorService->setComplementStrategy($comercioExteriorStrategy);
        
        return $builder;
    }
    
    /**
     * Registra una nueva estrategia de complemento
     */
    public function addComplementStrategy(ComplementStrategyInterface $strategy): self
    {
        $this->complementStrategies[$strategy->getComplementName()] = $strategy;
        return $this;
    }
    
    /**
     * Obtiene una estrategia de complemento por nombre
     */
    public function getComplementStrategy(string $name): ?ComplementStrategyInterface
    {
        return $this->complementStrategies[$name] ?? null;
    }

    /**
     * Procesa un CFDI completo: validación, generación de XML y guardado
     */
    public function processCFDI(
        CFDIData $cfdiData,
        string $finkokUser,
        string $finkokPassword,
        ?string $filePath = null,
        ?string $fileName = null
    ): ProcessResult {
        try {
            // 1. Validar credenciales de Finkok
            $credentialsValidation = $this->validationService->validateCredentials($finkokUser, $finkokPassword);
            if (!$credentialsValidation->isValid()) {
                return new ProcessResult(false, $credentialsValidation->getErrors(), null, 'Credenciales inválidas');
            }

            // 2. Validar RFC del emisor
            $rfcValidation = $this->validationService->validateRFC(
                $finkokUser, 
                $finkokPassword, 
                $cfdiData->getEmisor()->getRfc()
            );
            if (!$rfcValidation->isValid()) {
                return new ProcessResult(false, $rfcValidation->getErrors(), null, 'RFC inválido');
            }

            // 3. Validar datos del CFDI
            $dataValidation = $this->validationService->validateCFDIData($cfdiData);
            if (!$dataValidation->isValid()) {
                return new ProcessResult(false, $dataValidation->getErrors(), null, 'Datos de CFDI inválidos');
            }

            // 4. Generar XML
            $xml = $this->xmlGeneratorService->generate($cfdiData);

            // 5. Guardar archivo si se proporciona ruta
            $savedPath = null;
            if ($filePath !== null || $fileName !== null) {
                $path = $filePath ?: $this->fileService->getDefaultPath($cfdiData->getEmisor()->getRfc());
                $name = $fileName ? $this->fileService->sanitizeFilename($fileName) : 'CFDI_' . date('Y-m-d_H-i-s') . '.xml';
                
                $savedPath = $this->fileService->save($xml, $path, $name);
            }

            return new ProcessResult(true, [], $xml, 'CFDI procesado exitosamente', $savedPath);

        } catch (\Exception $e) {
            return new ProcessResult(false, [$e->getMessage()], null, 'Error al procesar CFDI');
        }
    }

    /**
     * Valida credenciales de Finkok
     */
    public function validateCredentials(string $username, string $password): ValidationResult
    {
        return $this->validationService->validateCredentials($username, $password);
    }

    /**
     * Valida un RFC en Finkok
     */
    public function validateRFC(string $username, string $password, string $rfc): ValidationResult
    {
        return $this->validationService->validateRFC($username, $password, $rfc);
    }

    /**
     * Genera solo el XML sin validaciones ni guardado
     */
    public function generateXML(CFDIData $cfdiData): string
    {
        return $this->xmlGeneratorService->generate($cfdiData);
    }

    /**
     * Guarda contenido XML en un archivo
     */
    public function saveXML(string $xml, ?string $path = null, ?string $filename = null): string
    {
        $path = $path ?: $this->fileService->getDefaultPath();
        $filename = $filename ? $this->fileService->sanitizeFilename($filename) : 'CFDI_' . date('Y-m-d_H-i-s') . '.xml';
        
        return $this->fileService->save($xml, $path, $filename);
    }

    // Getters para acceso directo a servicios
    public function getFinkokService(): FinkokService
    {
        return $this->finkokService;
    }

    public function getValidationService(): ValidationService
    {
        return $this->validationService;
    }

    public function getXMLGeneratorService(): XMLGeneratorService
    {
        return $this->xmlGeneratorService;
    }

    public function getFileService(): FileService
    {
        return $this->fileService;
    }
}

/**
 * Clase para encapsular el resultado del procesamiento de un CFDI
 */
class ProcessResult
{
    private bool $success;
    private array $errors;
    private ?string $xml;
    private string $message;
    private ?string $filePath;

    public function __construct(
        bool $success, 
        array $errors = [], 
        ?string $xml = null, 
        string $message = '',
        ?string $filePath = null
    ) {
        $this->success = $success;
        $this->errors = $errors;
        $this->xml = $xml;
        $this->message = $message;
        $this->filePath = $filePath;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getXml(): ?string
    {
        return $this->xml;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
