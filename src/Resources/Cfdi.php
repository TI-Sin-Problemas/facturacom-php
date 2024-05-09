<?php

namespace TiSinProblemas\FacturaCom\Resources;

require_once __DIR__ . '/../Types/Cfdi.php';

use ReflectionClass;
use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types;
use TiSinProblemas\FacturaCom\Constants\DocumentType;
use TypeError;

class Cfdi extends BaseCilent
{
    protected $ENDPOINT = "cfdi";
    protected $API_VERSION = "v4";

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
            $data["RazonSocialReceptor"],
            $data["Folio"],
            $data["UID"],
            $data["UUID"],
            $data["Subtotal"],
            $data["Descuento"],
            $data["Total"],
            $data["ReferenceClient"],
            $data["NumOrder"],
            $data["Receptor"],
            $data["FechaTimbrado"],
            $data["Status"],
            $data["TipoDocumento"],
            $data["Version"],
            array_key_exists("XML", $data) ? $data["XML"] : null
        );
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
        $body = $this->get(["list"], [
            "month" => sprintf("%02d", $month),
            "year" => $year,
            "rfc" => $rfc,
            "type_document" => $type_document,
            "page" => $page,
            "per_page" => $per_page
        ])->getBody();
        $response = json_decode($body, true);

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


    public function create(
        string $customer_uid,
        string $document_type,
        array $items,
        string $cfdi_usage,
        int $series,
        string $tax_residence = "",
        bool $create_draft_on_error = false,
        bool $draft = false,
    ) {
        $document_type_reflection = new ReflectionClass(DocumentType::class);
        $valid_document_types = $document_type_reflection->getConstants();
        if (!in_array($document_type, $valid_document_types)) {
            throw new FacturaComException("Invalid document type. Valid types are: " . implode(", ", $valid_document_types));
        }

        foreach ($items as $item) {
            if (!$item instanceof Types\Item) {
                throw new TypeError("Invalid item type. Expected Types\Item instance");
            }
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
            "UsoCFDI" => $cfdi_usage,
            "Serie" => $series
        ];
    }
}
