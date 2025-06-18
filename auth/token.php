<?php
require_once __DIR__ . '/../vendor/autoload.php';

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Exception\OAuthServerException;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;

// --- Mock Repositories (You can replace these with real DB classes) ---
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\CryptKey;

class ClientRepository implements ClientRepositoryInterface {
    public function getClientEntity($clientIdentifier) {
        if ($clientIdentifier === 'client_id_123') {
            return new \League\OAuth2\Server\Entities\ClientEntity();
        }
        return null;
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType) {
        return $clientIdentifier === 'client_id_123' && $clientSecret === 'client_secret_xyz';
    }
}

class AccessTokenRepository implements AccessTokenRepositoryInterface {
    public function getNewToken(\League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null) {
        return new \League\OAuth2\Server\Entities\AccessTokenEntity();
    }

    public function persistNewAccessToken(\League\OAuth2\Server\Entities\AccessTokenEntityInterface $accessTokenEntity) {}

    public function revokeAccessToken($tokenId) {}

    public function isAccessTokenRevoked($tokenId) {
        return false;
    }
}

class ScopeRepository implements ScopeRepositoryInterface {
    public function getScopeEntityByIdentifier($identifier) {
        $scope = new \League\OAuth2\Server\Entities\ScopeEntity();
        $scope->setIdentifier($identifier);
        return $scope;
    }

    public function finalizeScopes(array $scopes, $grantType, \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity, $userIdentifier = null) {
        return $scopes;
    }
}

// --- Configuration ---
$privateKeyPath = __DIR__ . '/../keys/private.key';
$encryptionKey = base64_encode(random_bytes(32)); // Store in secure config

$server = new AuthorizationServer(
    new ClientRepository(),
    new AccessTokenRepository(),
    new ScopeRepository(),
    new CryptKey($privateKeyPath, null, false),
    $encryptionKey
);

// --- Enable Grant Type ---
$server->enableGrantType(
    new ClientCredentialsGrant(),
    new DateInterval('PT1H') // 1 hour token lifetime
);

// --- Handle Request ---
$request = ServerRequestFactory::fromGlobals();
$response = new Response();

try {
    $response = $server->respondToAccessTokenRequest($request, $response);

    header('Content-Type: application/json');
    echo $response->getBody();
} catch (OAuthServerException $exception) {
    $exception->generateHttpResponse($response)->getBody()->rewind();
    echo $response->getBody();
} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error', 'message' => $exception->getMessage()]);
}
