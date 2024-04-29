<?php

namespace TiSinProblemas\FacturaCom\Resources;

use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types;


class Cfdi extends BaseCilent
{
    protected $ENDPOINT = "cfdi";

    /**
     * Retrieves all the CFDI documents based on specified parameters.
     *
     * @param int $month The month to filter the documents. Jan = 1, Dec = 12. Default is null.
     * @param int $year The year to filter the documents. Must be 4 digits. Default is null.
     * @param string $rfc The RFC to filter the documents. Ex. XAXX010101000. Default is null.
     * @param string $type_document The type of document to filter. Value must be a code from the SAT catalog 'Tipos de CFDI'. Default is null.
     * @param int $page The page number of the results. Default is null.
     * @param int $per_page The number of items per page. Default is null.
     * @return CfdiList The list of CFDI documents.
     */
    public function all(
        int $month = null,
        int $year = null,
        string $rfc = null,
        string $type_document = null,
        int $page = null,
        int $per_page = null
    ) {
        $response = $this->get(["list"], [
            "month" => sprintf("%02d", $month),
            "year" => $year,
            "rfc" => $rfc,
            "type_document" => $type_document,
            "page" => $page,
            "per_page" => $per_page
        ])->getBody();
        return new Types\CfdiList(json_decode($response));
    }

    /**
     * Retrieves a Cfdi object by its ID and type.
     *
     * @param mixed $id The ID of the Cfdi object to retrieve.
     * @param mixed $idType The type of ID being used for retrieval. "uid", "uuid" or "folio".
     * @throws FacturaComException If an error occurs during retrieval.
     * @return Types\Cfdi The retrieved Cfdi object.
     */
    private function get_by_id(string $id, string $idType)
    {
        if (!in_array($idType, ["uid", "uuid", "folio"])) {
            throw new FacturaComException("Invalid ID type");
        }

        $response = $this->get([$idType, $id])->getBody();
        $dataObject = json_decode($response);

        if ($dataObject->status == "error") {
            throw new FacturaComException($dataObject->message);
        }

        return new Types\Cfdi(
            $dataObject->RazonSocialReceptor,
            $dataObject->Folio,
            $dataObject->UID,
            $dataObject->UUID,
            $dataObject->Subtotal,
            $dataObject->Descuento,
            $dataObject->Total,
            $dataObject->ReferenceClient,
            $dataObject->NumOrder,
            $dataObject->Receptor,
            $dataObject->FechaTimbrado,
            $dataObject->Status,
            $dataObject->TipoDocumento,
            $dataObject->Version,
            $dataObject->XML
        );
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
}
