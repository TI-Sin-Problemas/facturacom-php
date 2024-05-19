<?php

namespace TiSinProblemas\FacturaCom\Resources;

use DateTime;
use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types;
use TiSinProblemas\FacturaCom\Constants;
use TypeError;

class Cfdi extends BaseCilent
{
    protected $ENDPOINT = "cfdi";
    protected $API_VERSION = "v4";


    private function set_alternate_endpoint()
    {
        $this->ENDPOINT = "cfdi40";
    }

    /**
     * Builds a Cfdi object based on the given data.
     *
     * @param array $data The data used to build the Cfdi object.
     *                   The data should have the following structure:
     *                   [
     *                       "RazonSocialReceptor" => string,
     *                       "Folio" => string,
     *                       "UID" => string,
     *                       "UUID" => string,
     *                       "Subtotal" => float,
     *                       "Descuento" => float,
     *                       "Total" => float,
     *                       "ReferenceClient" => string,
     *                       "NumOrder" => string,
     *                       "Receptor" => string,
     *                       "FechaTimbrado" => string,
     *                       "Status" => string,
     *                       "TipoDocumento" => string,
     *                       "Version" => string,
     *                       "XML" => string|null
     *                   ]
     * @return Types\Cfdi The built Cfdi object.
     */
    private function build_cfdi($data)
    {
        return new Types\Cfdi(
            recipient_company_name: array_key_exists("RazonSocialReceptor", $data) ? $data["RazonSocialReceptor"] : null,
            folio: array_key_exists("Folio", $data) ? $data["Folio"] : null,
            uid: array_key_exists("UID", $data) ? $data["UID"] : null,
            uuid: array_key_exists("UUID", $data) ? $data["UUID"] : null,
            subtotal: array_key_exists("Subtotal", $data) ? $data["Subtotal"] : null,
            discount: array_key_exists("Descuento", $data) ? $data["Descuento"] : null,
            total: array_key_exists("Total", $data) ? $data["Total"] : null,
            reference_client: array_key_exists("ReferenceClient", $data) ? $data["ReferenceClient"] : null,
            num_order: array_key_exists("NumOrder", $data) ? $data["NumOrder"] : null,
            recipient: array_key_exists("Receptor", $data) ? $data["Receptor"] : null,
            stamp_date: array_key_exists("FechaTimbrado", $data) ? $data["FechaTimbrado"] : null,
            status: array_key_exists("Status", $data) ? $data["Status"] : null,
            document_type: array_key_exists("TipoDocumento", $data) ? $data["TipoDocumento"] : null,
            version: array_key_exists("Version", $data) ? $data["Version"] : null,
            xml: array_key_exists("XML", $data) ? $data["XML"] : null
        );
    }

    private function execute_get_request(array $url_params, array $query_params = null)
    {
        $response = $this->get($url_params, $query_params);
        $data = json_decode($response->getBody(), true);
        if (array_key_exists("status", $data) && $data["status"] != "success") {
            throw new FacturaComException($data["message"]);
        }
        return $data;
    }

    private function execute_post_request(array $url_params, array $data = null)
    {
        $response = $this->post($url_params, $data);
        $data = json_decode($response->getBody(), true);

        if ($data["response"] != "success") {
            if (is_array($data["message"])) {
                throw new FacturaComException($data["message"]["message"]);
            }
            throw new FacturaComException($data["message"]);
        }
        return $data;
    }

    /**
     * Retrieves all the CFDI documents based on specified parameters.
     *
     * @param int $month The month to filter the documents. Jan = 1, Dec = 12. Default is null.
     * @param int $year The year to filter the documents. Must be 4 digits. Default is null.
     * @param string $rfc The RFC to filter the documents. Ex. XAXX010101000. Default is null.
     * @param string $type_document The type of document to filter. Value must be a code from the SAT catalog 'Tipos de CFDI'. Default is null.
     * @param int $page The page number of the results. Default is null.
     * @param int $per_page The number of items per page. Default is null.
     * @return Types\CfdiList The list of CFDI documents.
     */
    public function all(
        int $month = null,
        int $year = null,
        string $rfc = null,
        string $type_document = null,
        int $page = null,
        int $per_page = null
    ) {
        $response = $this->execute_get_request(["list"], [
            "month" => $month == null ? null : sprintf("%02d", $month),
            "year" => $year,
            "rfc" => $rfc,
            "type_document" => $type_document,
            "page" => $page,
            "per_page" => $per_page
        ]);

        return new Types\CfdiList(
            $response["total"],
            $response["per_page"],
            $response["current_page"],
            $response["last_page"],
            $response["from"],
            $response["to"],
            array_map([$this, 'build_cfdi'], $response["data"]),
        );
    }

    /**
     * Retrieves a Cfdi object by its ID and type.
     *
     * @param string $id The ID of the Cfdi object to retrieve.
     * @param string $idType The type of ID being used for retrieval. "uid", "uuid" or "folio".
     * @throws FacturaComException If an error occurs during retrieval.
     * @return Types\Cfdi The retrieved Cfdi object.
     */
    private function get_by_id(string $id, string $idType)
    {
        if (!in_array($idType, ["uid", "uuid", "folio"])) {
            throw new FacturaComException("Invalid ID type");
        }

        $response = $this->get([$idType, $id])->getBody();
        $data = json_decode($response, true);

        if ($data["status"] == "error") {
            throw new FacturaComException($data->message);
        }

        return $this->build_cfdi($data);
    }

    /**
     * Retrieves a Cfdi object by its UID.
     *
     * @param string $uid The UID of the Cfdi object to retrieve.
     * @throws FacturaComException If an error occurs during retrieval.
     * @return Types\Cfdi The retrieved Cfdi object.
     */
    public function get_by_uid(string $uid)
    {
        return $this->get_by_id($uid, "uid");
    }

    /**
     * Retrieves a Cfdi object by its UUID.
     *
     * @param string $uuid The UUID of the Cfdi object to retrieve.
     * @throws FacturaComException If an error occurs during retrieval.
     * @return Types\Cfdi The retrieved Cfdi object.
     */
    public function get_by_uuid(string $uuid)
    {
        return $this->get_by_id($uuid, "uuid");
    }

    /**
     * Retrieves a Cfdi object by its folio.
     *
     * @param string $folio The folio of the Cfdi object to retrieve.
     * @return Types\Cfdi The retrieved Cfdi object.
     */
    public function get_by_folio(string $folio)
    {
        return $this->get_by_id($folio, "folio");
    }



    /**
     * Creates a new CFDI with the given data.
     *
     * @param string $customer_uid The UID of the recipient previously created.
     * @param string $document_type The type of CFDI or document.
     *                  See \FacturaCom\Constants\DocumentType::values()
     * @param Types\Item[] $items The items to include in the CFDI.
     * @param string $cfdi_use The use of the CFDI according to the SAT Catalog.
     *                  See FacturaCom()->catalog->cfdi_uses->all()
     * @param int $series_uid The UID of the series previously created.
     * @param string $payment_method The payment method (forma de pago) code according to the SAT Catalog.
     *                  See FacturaCom()->catalog->payment_methods->all()
     * @param string $payment_option The payment option (metodo de pago) code according to the SAT Catalog.
     *                  See FacturaCom()->catalog->payment_options->all()
     * @param string $tax_residence The tax residence of the customer if the recipient recides
     *                  outside of Mexico (default: "").
     * @param bool $create_draft_on_error Whether to create a draft if an error occurs (default: false).
     * @param bool $draft Whether to create a CFDI as a draft (default: false).
     * @param string|null $payment_terms The payment terms (default: null).
     * @param array $related_cfdi The related CFDI. If the CFDI is related to other CFDI,
     *                  this value must be an array of UUID of all the related CFDI (default: []).
     * @param string $currency The currency of the CFDI according to the SAT Catalog (default: "MXN").
     *                  See FacturaCom()->catalog->currencies->all()
     * @param float|null $exchange_rate The exchange rate (default: null). This argument is required
     *                      if the currency is not MXN.
     * @param string|null $order_number The order number, for internal control only (default: null).
     * @param DateTime|null $date The date of the CFDI, it is possible to create a CFDI 72 hours in
     *                              the past (default: null).
     * @param string|null $comments The comments for the CFDI (default: null).
     * @param string|null $account The last 4 digits of the bank card or account of the customer (default: null).
     * @param bool $send_email Whether to send the CFDI by email to the customer (default: true).
     * @param string|null $expedition_place The zip code of the expedition place (default: null).
     * @param string|null $global_periodicity In case of a global CFDI, this argumment indicates
     *                                          the periodicity. See \FacturaCom\Constants\GlobalPeriodicity::values()
     *                                          (default: null).
     * @param string|null $global_month In case of a global CFDI, this argument indicates the month for wich to create
     *                                      the CFDI. See \FacturaCom\Constants\GlobalMonth::values() (default: null).
     * @param string|null $global_year In case of a global CFDI, this argument indicates the year for wich to create
     *                                      the CFDI in the format YYYY (default: null).
     * @throws FacturaComException If an error occurs during CFDI creation.
     * @return Types\CreatedCfdiResponse The created CFDI response.
     */
    public function create(
        string $customer_uid,
        string $document_type,
        array $items,
        string $cfdi_use,
        int $series_uid,
        string $payment_method,
        string $payment_option,
        string $tax_residence = "",
        bool $create_draft_on_error = false,
        bool $draft = false,
        string $payment_terms = null,
        array $related_cfdi = [],
        string $currency = "MXN",
        float $exchange_rate = null,
        string $order_number = null,
        DateTime $date = null,
        string $comments = null,
        string $account = null,
        bool $send_email = true,
        string $expedition_place = null,
        string $global_periodicity = null,
        string $global_month = null,
        string $global_year = null
    ) {
        $valid_document_types = array_values(Constants\DocumentType::values());
        if (!in_array($document_type, $valid_document_types)) {
            throw new FacturaComException("Invalid document type. Valid types are: " . implode(", ", $valid_document_types));
        }

        foreach ($items as $item) {
            if (!$item instanceof Types\Item) {
                throw new TypeError("Invalid item type. Expected Types\Item instance");
            }
        }

        $related = [];
        foreach ($related_cfdi as $cfdi) {
            if (!$cfdi instanceof Types\RelatedCfdi) {
                throw new TypeError("Invalid related CFDI type. Expected Types\RelatedCfdi instance");
            }
            $related[] = $cfdi->get_data_for_api();
        }

        if ($currency != "MXN" && !$exchange_rate) {
            throw new FacturaComException("Exchange rate is required for non-MXN currencies");
        }

        $recipient = [
            "UID" => $customer_uid,
            "ResidenciaFiscal" => $tax_residence
        ];

        $data = [
            "Receptor" => $recipient,
            "TipoDocumento" => $document_type,
            "BorradorSiFalla" => intval($create_draft_on_error),
            "Draft" => intval($draft),
            "Conceptos" => array_map(fn ($item) => $item->get_data_for_api(), $items),
            "UsoCFDI" => $cfdi_use,
            "Serie" => $series_uid,
            "FormaPago" => $payment_method,
            "MetodoPago" => $payment_option,
            "Moneda" => $currency,
            "EnviarCorreo" => $send_email,
        ];

        if ($payment_terms) {
            $data["CondicionesDePago"] = $payment_terms;
        }

        if (!empty($related)) {
            $data["CfdiRelacionados"] = $related;
        }

        if ($exchange_rate) {
            $data["TipoCambio"] = $exchange_rate;
        }

        if ($order_number) {
            $data["NumOrder"] = $order_number;
        }

        if ($date) {
            $data["Fecha"] = $date->format("Y-m-d\TH:m:s");
        }

        if ($comments) {
            $data["Comentarios"] = $comments;
        }

        if ($account) {
            $data["Cuenta"] = $account;
        }

        if ($expedition_place) {
            $data["LugarExpedicion"] = $expedition_place;
        }

        $global_args = [$global_periodicity, $global_month, $global_year];
        $is_global = count(array_filter($global_args, fn ($arg) => !is_null($arg))) > 0;
        if ($is_global) {
            // Validate global values
            $valid_periodicitys = array_values(Constants\GlobalCfdiPeriodicity::values());
            if (!in_array($global_periodicity, $valid_periodicitys)) {
                throw new FacturaComException("Invalid global periodicity. Valid values are: " . implode(", ", $valid_periodicitys));
            }

            $valid_months = array_values(Constants\GlobalCfdiMonth::values());
            if (!in_array($global_month, $valid_months)) {
                throw new FacturaComException("Invalid global month. Valid values are: " . implode(", ", $valid_months));
            }

            if (!$global_year) {
                throw new FacturaComException("Global year is required");
            }

            // Add global information to data
            $data["InformacionGlobal"] = [
                "Periodicidad" => $global_periodicity,
                "Meses" => $global_month,
                "Año" => $global_year
            ];
        }

        $this->set_alternate_endpoint();
        $response = $this->execute_post_request(["create"], $data);
        return new Types\CreatedCfdiResponse($response);
    }
}
