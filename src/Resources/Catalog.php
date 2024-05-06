<?php

namespace TiSinProblemas\FacturaCom\Resources;

use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types;

class Catalog
{
    public $products_services;
    public $customs;
    public $units_of_measure;
    public $payment_methods;
    public $taxes;
    public $payment_options;
    public $currencies;

    public function __construct($API_KEY, $SECRET_KEY, $SANDBOX_MODE = false)
    {
        $this->products_services = new ProductServiceCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->customs = new CustomsCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->units_of_measure = new UnitOfMeasureCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->payment_methods = new PaymentMethodCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->taxes = new TaxCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->payment_options = new PaymentOptionsCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->currencies = new CurrenciesCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
    }
}

class BaseCatalogClient extends BaseCilent
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
    protected function execute_get_request(array $url_params, array $query_params = null)
    {
        $response = $this->get($url_params, $query_params);
        $data = json_decode($response->getBody(), true);
        if ($data["response"] != "success") {
            throw new FacturaComException($data["message"]);
        }
        return $data;
    }
}

class ProductServiceCatalog extends BaseCatalogClient
{
    /**
     * Retrieves the product service catalog from the API and returns an array of ProductService objects.
     *
     * Retrieves the SAT catalog of Clave Producto/Servicio, which contains information about the available
     * products and services that can be used in invoices. The response is an array of ProductService objects, each representing
     * a specific product or service.
     *
     * @return Types\ProductService[] An array of ProductService objects representing the product services.
     */
    public function all()
    {
        $data = $this->execute_get_request(["ClaveProductServ"])["data"];
        return array_map(function ($item) {
            return new Types\ProductService($item["key"], $item["name"], $item["complement"]);
        }, $data);
    }
}

class CustomsCatalog extends BaseCatalogClient
{
    /**
     * Retrieves the customs data from the API and returns an array of CustomsHouse objects.
     *
     * Retrieves the SAT catalog of Aduanas, which contains information about the available customs entry ports
     * that can be used. The response is an array of CustomsHouse objects, each representing a specific customs entry port.
     *
     * @return Types\CustomsHouse[] An array of CustomsHouse objects representing the customs houses.
     */
    public function all()
    {
        $data = $this->execute_get_request(["Aduana"])["data"];
        return array_map(function ($item) {
            return new Types\CustomsHouse($item["key"], $item["name"]);
        }, $data);
    }
}

class UnitOfMeasureCatalog extends BaseCatalogClient
{
    /**
     * Retrieves the unit of measure catalog from the API and returns an array of UnitOfMeasure objects.
     *
     * Retrieves the SAT catalog of Unidades de Medida, which contains information about the available
     * units of measure that can be used in invoices. The response is an array of UnitOfMeasure objects, each representing
     * a specific unit of measure.
     *
     * @return Types\UnitOfMeasure[] An array of UnitOfMeasure objects representing the units of measure.
     */
    public function all()
    {
        $data = $this->execute_get_request(["ClaveUnidad"])["data"];
        return array_map(function ($item) {
            return new Types\UnitOfMeasure($item["key"], $item["name"]);
        }, $data);
    }
}

class PaymentMethodCatalog extends BaseCatalogClient
{
    /**
     * Retrieves the payment method catalog from the API and returns an array of PaymentMethod objects.
     *
     * Retrieves the SAT catalog of Formas de Pago, which contains information about the available
     * payment methods that can be used in invoices. The response is an array of PaymentMethod objects, each representing
     * a specific payment method.
     *
     * @return Types\PaymentMethod[] An array of PaymentMethod objects representing the payment methods.
     */
    public function all()
    {
        $data = $this->execute_get_request(["FormaPago"])["data"];
        return array_map(function ($item) {
            return new Types\PaymentMethod($item["key"], $item["name"]);
        }, $data);
    }
}

class TaxCatalog extends BaseCatalogClient
{
    /**
     * Retrieves the tax catalog from the API and returns an array of Tax objects.
     *
     * Retrieves the SAT catalog of Impuestos, which contains information about the available
     * taxes that can be used in invoices. The response is an array of Tax objects, each representing
     * a specific tax.
     *
     * @return Types\Tax[] An array of Tax objects representing the taxes.
     */
    public function all()
    {
        $data = $this->execute_get_request(["Impuesto"])["data"];
        return array_map(function ($item) {
            return new Types\Tax($item["key"], $item["name"]);
        }, $data);
    }
}

class PaymentOptionsCatalog extends BaseCatalogClient
{
    /**
     * Retrieves the payment options catalog from the API and returns an array of PaymentOption objects.
     *
     * Retrieves the SAT catalog of Metodos de Pago, which contains information about the available
     * payment options that can be used in invoices. The response is an array of PaymentOption objects, each representing
     * a specific payment option.
     *
     * @return Types\PaymentOption[] An array of PaymentOption objects representing the payment options.
     */
    public function all()
    {
        $data = $this->execute_get_request(["MetodoPago"])["data"];
        return array_map(function ($item) {
            return new Types\PaymentOption($item["key"], $item["name"]);
        }, $data);
    }
}

class CurrenciesCatalog extends BaseCatalogClient
{
    /**
     * Retrieves the currencies catalog from the API and returns an array of Currency objects.
     *
     * Retrieves the SAT catalog of Monedas, which contains information about the available
     * currencies that can be used in invoices. The response is an array of Currency objects, each representing
     * a specific currency.
     *
     * @return Types\Currency[] An array of Currency objects representing the currencies.
     */
    public function all()
    {
        $data = $this->execute_get_request(["Moneda"])["data"];
        return array_map(function ($item) {
            return new Types\Currency($item["key"], $item["name"]);
        }, $data);
    }
}
