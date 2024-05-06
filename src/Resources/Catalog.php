<?php

namespace TiSinProblemas\FacturaCom\Resources;

use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types;

class Catalog extends BaseCilent
{
    protected $ENDPOINT = "catalogo";
    protected $API_VERSION = "v3";

    /**
     * Executes a GET request with the given URL parameters and optional query parameters.
     *
     * @param array $url_params The URL parameters for the GET request.
     * @param array|null $query_params Optional query parameters for the GET request.
     * @throws FacturaComException If the API response status is not "success".
     * @return array The decoded JSON response data.
     */
    private function execute_get_request(array $url_params, array $query_params = null)
    {
        $response = $this->get($url_params, $query_params);
        $data = json_decode($response->getBody(), true);
        if ($data["response"] != "success") {
            throw new FacturaComException($data["message"]);
        }
        return $data;
    }

    /**
     * Retrieves the product service catalog from the API and returns an array of ProductService objects.
     *
     * This endpoint retrieves the catalog of Clave Producto/Servicio, which contains information about the available
     * products and services that can be used in invoices. The response is an array of ProductService objects, each representing
     * a specific product or service.
     *
     * @throws FacturaComException If the API response status is not "success".
     * @return Types\ProductService[] An array of ProductService objects representing the product services.
     */
    public function products_services()
    {
        $data = $this->execute_get_request(["ClaveProductServ"]);

        return array_map(function ($item) {
            return new Types\ProductService($item["key"], $item["name"], $item["complement"]);
        }, $data["data"]);
    }
}
