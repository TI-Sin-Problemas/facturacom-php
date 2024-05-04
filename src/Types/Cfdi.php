<?php

namespace TiSinProblemas\FacturaCom\Types;

use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;

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
                throw new FacturaComException("Invalid Cfdi object in data array");
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

class Recipient
{
    public $address;
}
