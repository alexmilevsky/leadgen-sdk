<?php
namespace Leadgen;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Facades\Log;

class LeadgenClient {
    const API_URL = 'https://leadgen.lexdegor.com/api/';

    /**
     * @var
     */
    private $token;

    /**
     * @param string|array $token
     */
    public function setToken($token)
    {
        if (is_string($token)) {
            if ($json = json_decode($token, true)) {
                $token = $json;
            } else {
                // assume $token is just the token string
                $token = array(
                    'access_token' => $token,
                );
            }
        }
        if ($token == null) {
            throw new InvalidArgumentException('invalid json token');
        }
        if (!isset($token['access_token'])) {
            throw new InvalidArgumentException("Invalid token format");
        }
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getAccessToken()
    {
        return $this->token['access_token'];
    }

    public function isAccessTokenExpired()
    {
        if ( ! $this->token) {
            return true;
        }

        $created = 0;
        if (isset($this->token['created'])) {
            $created = $this->token['created'];
        }

        return ($created + ($this->token['expires_in'] - 30)) < time();
    }

    public function refreshToken($refreshToken) {
        $client = new Client();
        $headers = array('Authorization' => 'Bearer' . $refreshToken, 'Accept' => 'application/json');
        $options = array('headers' => $headers);
        $url = self::API_URL . 'auth/refresh';
        $response = $client->request('POST', $url, $options);
        $content = $response->getBody()->getContents();
        $content = json_decode($content, true);

        Log::debug($response->getBody());

        if (isset($content['access_token'])) {
            $token = $content;
            $token['created'] = time();
            $this->setToken($token);
        }
    }

    public function request($path, $method, $data)
    {
        if ($this->isAccessTokenExpired()) {
            $this->refreshToken($this->getAccessToken());
        }

        $client = new Client();
        $headers = array('Authorization' => 'Bearer' . $this->getAccessToken());

        if (mb_strtoupper($method) === 'GET') {
            $options = array('headers' => $headers, 'query' => $data);
        }
        else {
            $options = array('headers' => $headers, 'multipart' => $this->flatten($data));
        }

        $url = self::API_URL . $path;
        $response = $client->request($method, $url, $options);

        $content = $response->getBody()->getContents();
        return json_decode($content, true);
    }

    /**
     * Used for turning an array into a PHP friendly name for multipart request
     *
     * @param array $array
     * @param string $prefix
     * @param string $suffix
     * @return array
     */
    protected function flatten(array $array, string $prefix = '', string $suffix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->flatten($value, $prefix . $key . $suffix . '[', ']'));
            } else {
                if ($value instanceof UploadedFile) {
                    $result[] = [
                        'name' => $prefix . $key . $suffix,
                        'filename' => $value->getClientOriginalName(),
                        'Mime-Type' => $value->getClientMimeType(),
                        'contents' => file_get_contents($value->getPathname()),
                    ];
                } else {
                    $result[] = [
                        'name' => $prefix . $key . $suffix,
                        'contents' => $value,
                    ];
                }
            }
        }
        return $result;
    }
}
