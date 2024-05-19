<?php

namespace TiSinProblemas\FacturaCom\Resources;

use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types;

class Draft extends BaseCilent
{
    protected $ENDPOINT = "drafts";
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

    /**
     * Retrieves all drafts with optional pagination.
     *
     * @param int|null $items_per_page The number of items per page. Default is null.
     * @param int|null $page The page number. Default is null.
     * @return Types\DraftList The list of drafts.
     * @throws FacturaComException If the API request fails.
     */
    public function all(int $items_per_page = null, int $page = null): Types\DraftList
    {
        $response = $this->execute_get_request([], [
            "perPage" => $items_per_page,
            "page" => $page
        ]);
        $data = array_map(function ($item) {
            return new Types\Draft(
                uuid: $item["UUID"],
                series: $item["Serie"],
                folio: $item["Folio"],
                version: $item["Version"],
                cfdi_data: $this->build_cfdi($item["draft"])
            );
        }, $response["data"]);

        return new Types\DraftList(
            total: $response["total"],
            per_page: $response["perPage"],
            current_page: $response["currentPage"],
            last_page: $response["lastPage"],
            data: $data
        );
    }
}
