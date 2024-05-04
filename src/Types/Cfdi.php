<?php

namespace TiSinProblemas\FacturaCom\Types;

use stdClass;

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
     * Constructs a new instance of the class with the provided data.
     *
     * @param array $data_class The data object containing information for the instance.
     */
    public function __construct(array $data_class)
    {
        $this->total = $data_class["total"];
        $this->per_page = $data_class["per_page"];
        $this->current_page = $data_class["current_page"];
        $this->last_page = $data_class["last_page"];
        $this->from = $data_class["from"];
        $this->to = $data_class["to"];
        $this->data = [];
        foreach ($data_class["data"] as $value) {
            $this->data[] = new Cfdi(
                $value["RazonSocialReceptor"],
                $value["Folio"],
                $value["UID"],
                $value["UUID"],
                $value["Subtotal"],
                $value["Descuento"],
                $value["Total"],
                $value["ReferenceClient"],
                $value["NumOrder"],
                $value["Receptor"],
                $value["FechaTimbrado"],
                $value["Status"],
                $value["TipoDocumento"],
                $value["Version"],
                array_key_exists("XML", $value) ? $value["XML"] : null
            );
        }
    }
}

class Recipient
{
    public $address;
}
