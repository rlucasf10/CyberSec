<?php
namespace CyberSec\Auth;

use Google\Client as GoogleClient;
use Google_Service_Oauth2;
use Exception;

class GoogleAuth
{
    private GoogleClient $client;

    public function __construct()
    {
        try {
            $this->client = new GoogleClient();
            $this->client->setApplicationName('CyberSec');
            $this->client->setClientId(GOOGLE_CLIENT_ID);
            $this->client->setClientSecret(GOOGLE_CLIENT_SECRET);
            $this->client->setRedirectUri(BASE_URL . 'controllers/auth_controller.php?action=google_callback');
            $this->client->addScope('email');
            $this->client->addScope('profile');
            $this->client->setAccessType('offline');
            $this->client->setPrompt('select_account consent');

            // Configurar la ruta al archivo de certificados CA
            $this->client->setHttpClient(
                new \GuzzleHttp\Client([
                    'verify' => __DIR__ . '/../../vendor/guzzlehttp/guzzle/src/cacert.pem',
                    'timeout' => 60,
                    'connect_timeout' => 60
                ])
            );
        } catch (Exception $e) {
            error_log('Error en la inicialización de Google Client: ' . $e->getMessage());
            throw new Exception('Error al configurar la autenticación con Google');
        }
    }

    public function getAuthUrl(): string
    {
        try {
            return $this->client->createAuthUrl();
        } catch (Exception $e) {
            error_log('Error al crear URL de autenticación: ' . $e->getMessage());
            throw new Exception('Error al generar la URL de autenticación');
        }
    }

    public function handleCallback(string $code): array
    {
        try {
            // Obtener el token de acceso
            $token = $this->client->fetchAccessTokenWithAuthCode($code);

            if (!isset($token['access_token'])) {
                throw new Exception('Error al obtener el token de acceso: ' . json_encode($token));
            }

            $this->client->setAccessToken($token);

            // Obtener el servicio OAuth2
            $oauth2 = new Google_Service_Oauth2($this->client);

            // Obtener información del usuario
            $userInfo = $oauth2->userinfo->get();

            if (!$userInfo->getEmail()) {
                throw new Exception('No se pudo obtener el email del usuario');
            }

            return [
                'email' => $userInfo->getEmail(),
                'nombre' => $userInfo->getGivenName() ?? '',
                'apellidos' => $userInfo->getFamilyName() ?? '',
                'picture' => $userInfo->getPicture() ?? '',
                'verified_email' => $userInfo->getVerifiedEmail() ?? false
            ];
        } catch (Exception $e) {
            error_log('Error en la autenticación con Google: ' . $e->getMessage());
            throw new Exception('Error en la autenticación con Google: ' . $e->getMessage());
        }
    }
}