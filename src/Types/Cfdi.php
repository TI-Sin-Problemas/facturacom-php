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
        $version
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

    public function __construct(stdClass $data_class)
    {
        $this->total = $data_class->total;
        $this->per_page = $data_class->per_page;
        $this->current_page = $data_class->current_page;
        $this->last_page = $data_class->last_page;
        $this->from = $data_class->from;
        $this->to = $data_class->to;
        $this->data = [];
        foreach ($data_class->data as $value) {
            $this->data[] = new Cfdi(
                $value->recipient_company_name,
                $value->folio,
                $value->uid,
                $value->uuid,
                $value->subtotal,
                $value->descuento,
                $value->total,
                $value->reference_client,
                $value->num_order,
                $value->recipient,
                $value->stamp_date,
                $value->status,
                $value->document_type,
                $value->version
            );
        }
    }
}
