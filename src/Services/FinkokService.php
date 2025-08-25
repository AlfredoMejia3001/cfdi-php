<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Services;

use AlfredoMejia\CfdiPhp\Contracts\FinkokServiceInterface;
use AlfredoMejia\CfdiPhp\Exceptions\CFDIException;
use SoapClient;
use SoapFault;
use Exception;

/**
 * Servicio para comunicación con los servicios de Finkok
 * Centraliza toda la lógica de comunicación SOAP
 */
class FinkokService implements FinkokServiceInterface
{
    private const UTILITIES_WSDL = 'https://demo-facturacion.finkok.com/servicios/soap/utilities.wsdl';
    private const REGISTRATION_WSDL = 'https://demo-facturacion.finkok.com/servicios/soap/registration.wsdl';
    
    private array $soapOptions;

    public function __construct()
    {
        $this->soapOptions = [
            'soap_version' => SOAP_1_1,
            'trace' => 1,
            'exceptions' => true,
            'connection_timeout' => 10,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function validateCredentials(string $username, string $password): array
    {
        try {
            $zipcode = '61970'; // Código postal genérico para validación
            
            $params = [
                'username' => $username,
                'password' => $password,
                'zipcode' => $zipcode
            ];
            
            $response = $this->soapCall(self::UTILITIES_WSDL, 'datetime', $params);
            
            // Verificar si hay un error en la respuesta
            if (isset($response['error'])) {
                return [
                    'success' => false,
                    'message' => $response['error'],
                    'valid' => false,
                    'response_type' => 'error_response'
                ];
            }
            
            // Si llegamos aquí, las credenciales son válidas
            return [
                'success' => true,
                'message' => 'Credenciales válidas',
                'valid' => true,
                'response_type' => 'success',
                'server_datetime' => $response['datetime'] ?? null
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage(),
                'valid' => false,
                'error_type' => 'unexpected_error'
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRFCInfo(string $username, string $password, string $rfc): array
    {
        try {
            $params = [
                'reseller_username' => $username,
                'reseller_password' => $password,
                'taxpayer_id' => $rfc,
                'id_type' => 'on' // Para búsqueda exacta
            ];

            $response = $this->soapCall(self::REGISTRATION_WSDL, 'get', $params);

            // Verificar si la respuesta tiene la estructura esperada
            if (isset($response['users']['ResellerUser'])) {
                $userData = $response['users']['ResellerUser'];
                $status = (string)$userData['status'];
                $taxpayerId = (string)$userData['taxpayer_id'];
                
                return [
                    'success' => true,
                    'status' => $status,
                    'message' => 'Validación exitosa',
                    'user_status' => $status,
                    'taxpayer_id' => $taxpayerId,
                    'credit' => $userData['credit'] ?? null
                ];
            }
            
            // Si hay un error en la respuesta
            if (isset($response['message'])) {
                return [
                    'success' => false,
                    'message' => (string)$response['message'],
                    'status' => 'error'
                ];
            }
            
            // Respuesta inesperada
            return [
                'success' => false,
                'message' => 'No se pudo procesar la respuesta del servicio de registro',
                'status' => 'error',
                'response' => $response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error inesperado al validar el RFC: ' . $e->getMessage(),
                'status' => 'error',
                'error_type' => 'unexpected_error'
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function soapCall(string $wsdlUrl, string $method, array $params): array
    {
        try {
            // Crear cliente SOAP
            $client = new SoapClient($wsdlUrl, $this->soapOptions);
            
            // Realizar la llamada al servicio
            $response = $client->__soapCall($method, [$params]);
            
            return $this->parseResponse($response, $method);
            
        } catch (SoapFault $e) {
            throw new CFDIException(
                'Error en el servicio SOAP: ' . $e->getMessage(),
                [],
                $e->getCode(),
                $e
            );
        } catch (Exception $e) {
            throw new CFDIException(
                'Error inesperado en llamada SOAP: ' . $e->getMessage(),
                [],
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Parsea la respuesta del servicio SOAP a array
     */
    private function parseResponse(object $response, string $method): array
    {
        switch ($method) {
            case 'datetime':
                return $this->parseDatetimeResponse($response);
            case 'get':
                return $this->parseRegistrationResponse($response);
            default:
                return (array) $response;
        }
    }

    /**
     * Parsea la respuesta del servicio datetime
     */
    private function parseDatetimeResponse(object $response): array
    {
        $result = [];
        
        if (isset($response->datetimeResult->error)) {
            $result['error'] = (string)$response->datetimeResult->error;
        }
        
        if (isset($response->datetimeResult->datetime)) {
            $result['datetime'] = (string)$response->datetimeResult->datetime;
        }
        
        return $result;
    }

    /**
     * Parsea la respuesta del servicio de registro
     */
    private function parseRegistrationResponse(object $response): array
    {
        $result = [];
        
        // Debug: agregar respuesta completa para diagnóstico
        $result['debug_response'] = json_encode($response, JSON_PRETTY_PRINT);
        
        if (isset($response->getResult)) {
            if (isset($response->getResult->users)) {
                $result['users'] = (array)$response->getResult->users;
                
                // Verificar si ResellerUser es un array u objeto
                if (isset($response->getResult->users->ResellerUser)) {
                    $userData = $response->getResult->users->ResellerUser;
                    
                    // Si hay múltiples usuarios, tomar el primero
                    if (is_array($userData)) {
                        $userData = $userData[0];
                    }
                    
                    $result['users']['ResellerUser'] = (array)$userData;
                }
            }
            
            if (isset($response->getResult->message)) {
                $result['message'] = (string)$response->getResult->message;
            }
        }
        
        return $result;
    }
}
