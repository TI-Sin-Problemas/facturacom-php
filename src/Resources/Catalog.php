<?php

namespace TiSinProblemas\FacturaCom\Resources;

require_once __DIR__ . '/../Types/Catalog.php';

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
    public $countries;
    public $tax_regimes;
    public $relation_types;
    public $cfdi_uses;
    public $withholding_types;

    public function __construct($API_KEY, $SECRET_KEY, $SANDBOX_MODE = false)
    {
        $this->products_services = new ProductServiceCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->customs = new CustomsCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->units_of_measure = new UnitOfMeasureCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->payment_methods = new PaymentMethodCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->taxes = new TaxCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->payment_options = new PaymentOptionsCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->currencies = new CurrenciesCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->countries = new CountriesCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->tax_regimes = new TaxRegimesCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->relation_types = new RelationTypesCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->cfdi_uses = new CfdiUsesCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->withholding_types = new WithholdingTypesCatalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
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

class CountriesCatalog extends BaseCatalogClient
{
    /**
     * Retrieves the countries catalog from the API and returns an array of Country objects.
     *
     * Retrieves the SAT catalog of Paises, which contains information about the available
     * countries that can be used. The response is an array of Country objects, each representing
     * a specific country.
     *
     * @return Types\Country[] An array of Country objects representing the countries.
     */
    public function all()
    {
        $data = $this->execute_get_request(["Pais"])["data"];
        return array_map(function ($item) {
            return new Types\Country($item["key"], $item["name"]);
        }, $data);
    }
}

class TaxRegimesCatalog extends BaseCatalogClient
{
    /**
     * Retrieves the tax regime catalog from the API and returns an array of TaxRegime objects.
     *
     * Retrieves the SAT catalog of Regimen Fiscal, which contains information about the available
     * tax regimes that can be used in invoices. The response is an array of TaxRegime objects, each representing
     * a specific tax regime.
     *
     * @return Types\TaxRegime[] An array of TaxRegime objects representing the tax regimes.
     */
    public function all()
    {
        $data = $this->execute_get_request(["RegimenFiscal"])["data"];
        return array_map(function ($item) {
            return new Types\TaxRegime($item["key"], $item["name"]);
        }, $data);
    }
}

class RelationTypesCatalog extends BaseCatalogClient
{
    /**
     * Retrieves the relation type catalog from the API and returns an array of RelationType objects.
     *
     * Retrieves the SAT catalog of Tipos de Relacion, which contains information about the available
     * relation types that can be used in CFDI objects. The response is an array of RelationType objects, each representing
     * a specific relation type.
     *
     * @return Types\RelationType[] An array of RelationType objects representing the relation types.
     */
    public function all()
    {
        $data = $this->execute_get_request(["Relacion"])["data"];
        return array_map(function ($item) {
            return new Types\RelationType($item["key"], $item["name"]);
        }, $data);
    }
}

class CfdiUsesCatalog extends BaseCatalogClient
{
    /**
     * Retrieves the CFDI use catalog from the API and returns an array of CfdiUse objects.
     *
     * Retrieves the SAT catalog of Uso de CFDI, which contains information about the available
     * CFDI uses that can be used in CFDI objects. The response is an array of CfdiUse objects, each representing
     * a specific CFDI use.
     *
     * @return Types\CfdiUse[] An array of CfdiUse objects representing the CFDI uses.
     */
    public function all()
    {
        $data = $this->execute_get_request(["UsoCfdi"])["data"];
        return array_map(function ($item) {
            return new Types\CfdiUse($item["key"], $item["name"], $item["use"]);
        }, $data);
    }
}

class WithholdingTypesCatalog extends BaseCilent
{
    protected $ENDPOINT = "catalogos";
    protected $API_VERSION = "v4";

    /**
     * Executes a GET request with the given URL parameters and optional query parameters.
     *
     * @param array $url_params The URL parameters for the GET request.
     * @param array|null $query_params Optional query parameters for the GET request.
     * @return array The decoded JSON response data.
     */
    protected function execute_get_request(array $url_params, array $query_params = null)
    {
        $response = $this->get($url_params, $query_params);
        return json_decode($response->getBody(), true);
    }

    /**
     * Retrieves the withholding type catalog from the API and returns an array of WithholdingType objects.
     *
     * Retrieves the SAT catalog of Tipos de Retenciones, which contains information about the available
     * withholding types that can be used in CFDI objects. The response is an array of WithholdingType objects, each representing
     * a specific withholding type.
     *
     * @return Types\WithholdingType[] An array of WithholdingType objects representing the withholding types.
     */
    public function all()
    {
        $data = $this->execute_get_request(["retenciones", "claveRetencion"]);
        return array_map(function ($item) {
            return new Types\WithholdingType($item["key"], $item["name"]);
        }, $data);
    }
}
