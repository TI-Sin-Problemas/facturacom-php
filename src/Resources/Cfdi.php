<?php

namespace TiSinProblemas\FacturaCom\Resources;

use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types\Cfdi as CfdiType;
use TiSinProblemas\FacturaCom\Types\CfdiList;


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
        return new CfdiList(json_decode($response));
    }

    public function get_by_uid(string $uid)
    {
        $response = $this->get(["uid", $uid])->getBody();
        $dataObject = json_decode($response);
        if ($dataObject->status == "error") {
            throw new FacturaComException($dataObject->message);
        }
        return new CfdiType(
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
}
