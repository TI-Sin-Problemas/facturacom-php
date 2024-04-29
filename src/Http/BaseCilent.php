<?php

namespace TiSinProblemas\FacturaCom\Http;

use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BaseCilent
{
    protected $SANDBOX_BASE_URL = "https://sandbox.factura.com/api";
    protected $PROD_BASE_URL = "https://api.factura.com";
    protected $API_KEY = "";
    protected $SECRET_KEY = "";
    protected $SANDBOX_MODE = false;
    protected $API_VERSION;
    protected $ENDPOINT;
    protected $F_PLUGIN_HEADER = "9d4095c8f7ed5785cb14c0e3b033eeb8252416ed";

    /**
     * Constructs a new instance of the Cilent class.
     *
     * @param string $API_KEY The API Key to use for authentication.
     * @param string $SECRET_KEY The Secret Key to use for authentication.
     * @param bool $SANDBOX_MODE Whether to use the sandbox or production API. Defaults to false.
     * @param string $API_VERSION The version of the API to use. Defaults to "v4".
     */
    public function __construct($API_KEY, $SECRET_KEY, $SANDBOX_MODE = false, $API_VERSION = "v4")
    {
        $this->API_KEY = $API_KEY;
        $this->SECRET_KEY = $SECRET_KEY;
        $this->SANDBOX_MODE = $SANDBOX_MODE;
        $this->API_VERSION = $API_VERSION;
    }

    /**
     * Retrieves the endpoint URL.
     *
     * @throws FacturaComException if the endpoint is empty
     * @return string the endpoint URL
     */
    protected function get_endpoint()
    {
        if (empty($this->ENDPOINT)) {
            throw new FacturaComException("API_KEY is required");
        }
        return $this->ENDPOINT;
    }

    /**
     * Retrieves the API version.
     *
     * @throws FacturaComException if the API version is empty
     * @return string the API version
     */
    protected function get_api_version()
    {
        if (empty($this->API_VERSION)) {
            throw new FacturaComException("API_VERSION is required");
        }
        return $this->API_VERSION;
    }

    /**
     * Retrieves the base URL based on the sandbox mode setting.
     *
     * @return string The base URL.
     */
    protected function get_base_url()
    {
        if ($this->SANDBOX_MODE) {
            return $this->SANDBOX_BASE_URL;
        }
        return $this->PROD_BASE_URL;
    }

    /**
     * Retrieves the headers for the HTTP request.
     *
     * @return array The headers for the HTTP request.
     */
    protected function get_headers()
    {
        return [
            'Content-Type' => 'application/json',
            'F-PLUGIN' => $this->F_PLUGIN_HEADER,
            'F-Api-Key' => $this->API_KEY,
            'F-Secret-Key' => $this->SECRET_KEY
        ];
    }

    /**
     * Sends a GET request to the API with the given URL parameters and query parameters.
     *
     * @param array $url_params An array of URL parameters. Default is an empty array.
     * @param array $query_params An array of query parameters. Default is an empty array.
     * @throws FacturaComException If the request fails or if the response status code is not 200.
     * @return GuzzleHttp\Psr7\Response The response object from the API.
     */
    protected function get($url_params = [], $query_params = [])
    {
        $base_uri = $this->get_base_url() . '/' . $this->get_api_version() . '/' . $this->get_endpoint() . "/";
        $params = implode('/', $url_params);
        $client = new Client(['base_uri' => $base_uri]);

        try {
            $response = $client->request('GET', $params, [
                'headers' => $this->get_headers(),
                'query' => $query_params
            ]);
        } catch (GuzzleException $e) {
            throw new FacturaComException($e->getMessage());
        }

        if ($response->getStatusCode() != 200) {
            $message = json_decode($response->getBody())["message"];
            throw new FacturaComException($message);
        }
        return $response;
    }
}
