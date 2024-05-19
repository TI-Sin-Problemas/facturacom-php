<?php

namespace TiSinProblemas\FacturaCom\Types;

use DateTime;
use TiSinProblemas\FacturaCom\Constants\TaxFactorType;
use TypeError;
use ValueError;

class Cfdi
{
    public $recipient_company_name;
    public $folio;
    public $uid;
    public $uuid;
    public $subtotal;
    public $discount;
    public $total;
    public $reference_client;
    public $num_order;
    public $recipient;
    public $stamp_date;
    public $status;
    public $document_type;
    public $version;
    public $xml;

    /**
     * Constructs a new instance of the Cfdi class.
     *
     * @param mixed $recipient_company_name The recipient company name.
     * @param mixed $folio The folio.
     * @param mixed $uid The uid.
     * @param mixed $uuid The uuid.
     * @param mixed $subtotal The subtotal.
     * @param mixed $descuento The descuento.
     * @param mixed $total The total.
     * @param mixed $reference_client The reference client.
     * @param mixed $num_order The number of order.
     * @param mixed $recipient The recipient.
     * @param mixed $stamp_date The stamp date.
     * @param mixed $status The status.
     * @param mixed $document_type The document type.
     * @param mixed $version The version.
     * @param mixed $xml The xml content.
     */
    public function __construct(
        $recipient_company_name,
        $folio,
        $uid,
        $uuid,
        $subtotal,
        $discount,
        $total,
        $reference_client,
        $num_order,
        $recipient,
        $stamp_date,
        $status,
        $document_type,
        $version,
        $xml = null
    ) {
        $this->recipient_company_name = $recipient_company_name;
        $this->folio = $folio;
        $this->uid = $uid;
        $this->uuid = $uuid;
        $this->subtotal = $subtotal;
        $this->discount = $discount;
        $this->total = $total;
        $this->reference_client = $reference_client;
        $this->num_order = $num_order;
        $this->recipient = $recipient;
        $this->stamp_date = $stamp_date;
        $this->status = $status;
        $this->document_type = $document_type;
        $this->version = $version;
        $this->xml = $xml;
    }
}


class CfdiList
{
    public $total;
    public $per_page;
    public $current_page;
    public $last_page;
    public $from;
    public $to;
    public $data;

    /**
     * Constructs a new instance of the CfdiList class with the provided data.
     *
     * @param int $total The total number of items in the list.
     * @param int $per_page The number of items per page.
     * @param int $current_page The current page number.
     * @param int $last_page The last page number.
     * @param int $from The starting index of the items in the list.
     * @param int $to The ending index of the items in the list.
     * @param Cfdi[] $data An array of Cfdi objects.
     */
    public function __construct(
        int $total,
        int $per_page,
        int $current_page,
        int $last_page,
        int $from,
        int $to,
        array $data
    ) {
        foreach ($data as $cfdi) {
            if (!($cfdi instanceof Cfdi)) {
                throw new ValueError("Invalid Cfdi object in data array");
            }
        }

        $this->total = $total;
        $this->per_page = $per_page;
        $this->current_page = $current_page;
        $this->last_page = $last_page;
        $this->from = $from;
        $this->to = $to;
        $this->data = $data;
    }
}

class ItemTax
{
    public $base;
    public $code;
    public $factor_type;
    public $rate_or_amount;
    public $amount;


    /**
     * Constructs a new instance of the Tax class.
     *
     * @param float $base The value on which the tax will be calculated. Example: 15000.00
     * @param string $code The key corresponding to the tax you want to add. Example: "002".
     *                    Retrieve the catalog of codes using the `FacturaCom()->catalog->taxes->all()` method.
     * @param float $rate_or_amount The rate or amount corresponding to the tax you want to add. Example: 0.16
     * @param float $amount The amount of the transferred tax that applies to each item. Negative values are not allowed. Example: 2400.00
     * @param string|TaxFactorType $factor_type The factor type corresponding to the tax you want to add. Example: "Tasa". Defaults to `TaxFactorType::TASA`.
     */
    public function __construct(
        float $base,
        string $code,
        float $rate_or_amount,
        float $amount,
        string $factor_type = TaxFactorType::TASA
    ) {
        $tax_factor_constants = array_values(TaxFactorType::values());
        if (!in_array($factor_type, $tax_factor_constants)) {
            throw new ValueError("Invalid factor type. Valid types are: " . implode(", ", $tax_factor_constants));
        }

        if ($amount < 0) {
            throw new ValueError("Amount cannot be negative");
        }

        $this->base = $base;
        $this->code = $code;
        $this->rate_or_amount = $rate_or_amount;
        $this->amount = $amount;
        $this->factor_type = $factor_type;
    }

    /**
     * Retrieves data in a format suitable for API consumption.
     *
     * @return array Data array with keys: "Base", "Impuesto", "TipoFactor", "TasaOCuota", "Importe"
     */
    public function get_data_for_api()
    {
        return [
            "Base" => $this->base,
            "Impuesto" => $this->code,
            "TipoFactor" => $this->factor_type,
            "TasaOCuota" => $this->rate_or_amount,
            "Importe" => $this->amount
        ];
    }
}

class LocalItemTax
{
    public $code;
    public $rate_or_amount;

    /**
     * Constructs a new instance of the LocalItemTax class.
     *
     * @param string $code The code of the tax. Valid codes are: 'CEDULAR', 'ISH'.
     * @param float $rate_or_amount The rate or amount of the tax.
     * @throws ValueError If the code is invalid.
     */
    public function __construct(string $code, float $rate_or_amount)
    {
        if (!in_array($code, ["CEDULAR", "ISH"])) {
            throw new ValueError("Invalid code. Valid codes are: 'CEDULAR', 'ISH'");
        }

        $this->code = $code;
        $this->rate_or_amount = $rate_or_amount;
    }

    /**
     * Retrieves the data for the API in the form of an associative array.
     *
     * @return array The data for the API, containing the following keys:
     *               - "Impuesto": The code of the tax.
     *               - "TasaOCuota": The rate or amount of the tax.
     */
    public function get_data_for_api(): array
    {
        return [
            "Impuesto" => $this->code,
            "TasaOCuota" => $this->rate_or_amount
        ];
    }
}

class ItemTaxes
{

    public $transferred;
    public $withheld;
    public $local;

    /**
     * Constructs a new instance of the ItemTaxes class.
     *
     * @param ItemTax[] $transferred The transferred taxes for the item.
     * @param ItemTax[] $withheld The withheld taxes for the item.
     * @param LocalItemTax[] $local The local taxes for the item.
     * @throws ValueError If any of the objects in the arrays are not of the expected type.
     */
    public function __construct(array $transferred, array $withheld, array $local)
    {
        foreach ($transferred as $tax) {
            if (!($tax instanceof ItemTax)) {
                throw new ValueError("Invalid Tax object in transferred_taxes array. Expected Types\ItemTax instance");
            }
        }
        foreach ($withheld as $tax) {
            if (!($tax instanceof ItemTax)) {
                throw new ValueError("Invalid Tax object in withheld_taxes array. Expected Types\ItemTax instance");
            }
        }
        foreach ($local as $tax) {
            if (!($tax instanceof LocalItemTax)) {
                throw new ValueError("Invalid LocalTax object in local_taxes array. Expected Types\LocalItemTax instance");
            }
        }

        $this->transferred = $transferred;
        $this->withheld = $withheld;
        $this->local = $local;
    }

    /**
     * Retrieves the data for the API.
     *
     * This function returns an array containing the data for the API. The data includes the "Traslados", "Retenidos", and "Locales" arrays.
     * Each array is generated by mapping the elements of the corresponding property of the class to the `get_data_for_api()` method of each element.
     *
     * @return array The data for the API.
     */
    public function get_data_for_api()
    {
        return [
            "Traslados" => array_map(fn ($tax) => $tax->get_data_for_api(), $this->transferred),
            "Retenidos" => array_map(fn ($tax) => $tax->get_data_for_api(), $this->withheld),
            "Locales" => array_map(fn ($tax) => $tax->get_data_for_api(), $this->local)
        ];
    }
}

class BaseItem
{
    public $product_service_code;
    public $sku;
    public $quantity;
    public $unit_of_measure_code;
    public $unit_price;
    public $description;

    /**
     * Constructs a new instance of the class.
     *
     * @param string $product_service_code The code of the product or service according to the SAT Catalog.
     *                                     Retrieve the catalog of codes using the `FacturaCom()->catalog->products_services->all()` method.
     * @param int $quantity The quantity of items.
     * @param string $unit_of_measure_code The code of the unit of measure according to the SAT Catalog.
     *                                       Retrieve the catalog of codes using the `FacturaCom()->catalog->units_of_measure->all()` method.
     * @param float $unit_price The price of the product or service per unit without taxes.
     * @param string $description The description of the product or service.
     * @param string|null $sku The SKU (Stock Keeping Unit) of the product or service.
     */
    public function __construct(
        string $product_service_code,
        int $quantity,
        string $unit_of_measure_code,
        float $unit_price,
        string $description,
        string $sku = null
    ) {
        $this->product_service_code = $product_service_code;
        $this->quantity = $quantity;
        $this->unit_of_measure_code = $unit_of_measure_code;
        $this->unit_price = $unit_price;
        $this->description = $description;
        $this->sku = $sku;
    }
}
class ItemPart extends BaseItem
{
    /**
     * Retrieves the data for the API in the form of an associative array.
     *
     * @return array The data for the API, containing the following keys:
     *               - "ClaveProdServ": The product service code.
     *               - "NoIdentificacion": The SKU (Stock Keeping Unit) of the item.
     *               - "Cantidad": The quantity of items.
     *               - "Unidad": The code of the unit of measure according to the SAT Catalog.
     *               - "ValorUnitario": The price of the item per unit without taxes.
     *               - "Descripcion": The description of the item.
     */
    public function get_data_for_api(): array
    {
        return [
            "ClaveProdServ" => $this->product_service_code,
            "NoIdentificacion" => $this->sku,
            "Cantidad" => $this->quantity,
            "Unidad" => $this->unit_of_measure_code,
            "ValorUnitario" => $this->unit_price,
            "Descripcion" => $this->description
        ];
    }
}

class Item extends BaseItem
{
    public $unit_of_measure_name;
    public $discount_amount;
    public $taxes;
    public $customs_declaration_number; // Número de pedimento
    public $property_tax_number; // Predial
    public $parts;

    /**
     * Constructs a new instance of the Item class.
     *
     * The Item class represents a concept of CFDI invoice.
     *
     * @param string $product_service_code The code of the product or service according to the SAT Catalog.
     *                                     Retrieve the catalog of codes using the `FacturaCom()->catalog->products_services->all()` method.
     * @param int $quantity The quantity of items.
     * @param string $unit_of_measure_code The code of the unit of measure according to the SAT Catalog.
     *                                     Retrieve the catalog of codes using the `FacturaCom()->catalog->units_of_measure->all()` method.
     * @param string $unit_of_measure_name The name of the unit of measure.
     * @param float $unit_price The price of the item per unit without taxes.
     * @param string $description The description of the item.
     * @param float $discount_amount The amount of the discount.
     * @param ItemTax[] $transferred_taxes The transferred taxes for the item.
     * @param string|null $sku The SKU (Stock Keeping Unit) of the item.
     * @param ItemTax[] $withheld_taxes The withheld taxes for the item. Default is []
     * @param LocalItemTax[] $local_taxes The local taxes for the item. Default is []
     * @param string|null $customs_declaration_number The number of the customs declaration.
     * @param string|null $property_tax_number The property tax number (Predial).
     * @param array $parts The parts or components of the item. Default is []
     */
    public function __construct(
        string $product_service_code,
        int $quantity,
        string $unit_of_measure_code,
        string $unit_of_measure_name,
        float $unit_price,
        string $description,
        float $discount_amount,
        array $transferred_taxes,
        string $sku = null,
        array $withheld_taxes = [],
        array $local_taxes = [],
        string $customs_declaration_number = null,
        string $property_tax_number = null,
        array $parts = [],
    ) {
        foreach ($parts as $value) {
            if (!($value instanceof ItemPart)) {
                throw new TypeError("Invalid part type. Expected Types\ItemPart instance");
            }
        }
        parent::__construct($product_service_code, $quantity, $unit_of_measure_code, $unit_price, $description, $sku);

        $this->unit_of_measure_name = $unit_of_measure_name;
        $this->discount_amount = $discount_amount;
        $this->taxes = new ItemTaxes($transferred_taxes, $withheld_taxes, $local_taxes);
        $this->customs_declaration_number = $customs_declaration_number;
        $this->property_tax_number = $property_tax_number;
        $this->parts = $parts;
    }

    /**
     * Retrieves the data for the API in the form of an associative array.
     * 
     * @return array The data for the API, containing the following keys:
     *               - "ClaveProdServ": The code of the product or service.
     *               - "NoIdentificacion": The SKU of the item.
     *               - "Cantidad": The quantity of items.
     *               - "ClaveUnidad": The code of the unit of measure.
     *               - "Unidad": The name of the unit of measure.
     *               - "ValorUnitario": The price of the item per unit without taxes.
     *               - "Descripcion": The description of the item.
     *               - "Descuento": The amount of the discount.
     *               - "Impuestos": The taxes information.
     *               - "NumeroPedimento": The customs declaration number.
     *               - "Predial": The property tax number.
     *               - "Partes": The parts or components of the item.
     */
    public function get_data_for_api(): array
    {
        return [
            "ClaveProdServ" => $this->product_service_code,
            "NoIdentificacion" => $this->sku,
            "Cantidad" => $this->quantity,
            "ClaveUnidad" => $this->unit_of_measure_code,
            "Unidad" => $this->unit_of_measure_name,
            "ValorUnitario" => $this->unit_price,
            "Descripcion" => $this->description,
            "Descuento" => $this->discount_amount,
            "Impuestos" => $this->taxes->get_data_for_api(),
            "NumeroPedimento" => $this->customs_declaration_number,
            "Predial" => $this->property_tax_number,
            "Partes" => array_map(fn ($part) => $part->get_data_for_api(), $this->parts)
        ];
    }
}

class RelatedCfdi
{
    public $uuid;
    public $relation_type;

    /**
     * Constructs a new instance of the class.
     *
     * @param string $uuid The UUID of the related CFDI.
     * @param array $relation_type The type of relation between the CFDI and the series.
     *                              You can retrieve the list of valid relation types using the
     *                              FacturaCom()->catalog->relation_types->all() method.
     */
    public function __construct(string $uuid, array $relation_type)
    {
        $this->uuid = $uuid;
        $this->relation_type = $relation_type;
    }

    public function get_data_for_api(): array
    {
        return ["UUID" => $this->uuid, "TipoRelacion" => $this->relation_type];
    }
}

class CreatedCfdiResponse
{
    public $message;
    public $uuid;
    public $uid;
    public $date;
    public $sat_certificate_number;
    public $version;
    public $sat_stamp;
    public $cfd_stamp;
    public $series;
    public $folio;

    public function __construct(array $data)
    {
        $this->message = $data["message"];
        $this->uuid = $data["UUID"];
        $this->uid = $data["uid"];

        $sat = $data["SAT"];
        $this->date = new DateTime($sat["FechaTimbrado"]);
        $this->sat_certificate_number = $sat["NoCertificadoSAT"];
        $this->version = $sat["Version"];
        $this->sat_stamp = $sat["SelloSAT"];
        $this->cfd_stamp = $sat["SelloCFD"];

        $inv = $data["INV"];
        $this->series = $inv["Serie"];
        $this->folio = $inv["Folio"];
    }
}
