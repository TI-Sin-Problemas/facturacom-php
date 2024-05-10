<?php

namespace TiSinProblemas\FacturaCom\Resources;

require_once __DIR__ . '/../Types/Series.php';

use ReflectionClass;
use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types;
use TiSinProblemas\FacturaCom\Constants;

class Series extends BaseCilent
{
    protected $ENDPOINT = "series";
    protected $API_VERSION = "v4";

    private function execute_get_request(array $url_params, array $query_params = null)
    {
        $response = $this->get($url_params, $query_params);
        $data = json_decode($response->getBody(), true);
        if ($data["status"] != "success") {
            throw new FacturaComException($data["message"]);
        }
        return $data;
    }

    private function execute_post_request(array $url_params, array $data = null)
    {
        $response = $this->post($url_params, $data);
        $data = json_decode($response->getBody(), true);

        $status = "";
        if (key_exists("status", $data)) {
            $status = $data["status"];
        } else if (key_exists("response", $data)) {
            $status = $data["response"];
        }

        if ($status != "success") {
            throw new FacturaComException($data["message"]);
        }
        return $data;
    }

    /**
     * Retrieves all series from the API.
     *
     * @return Types\Series[] The array of Series objects representing the retrieved series.
     */
    public function all(): array
    {
        $response =  $this->execute_get_request([]);
        return array_map(
            fn ($item) => new Types\Series(
                $item["SerieID"],
                $item["SerieName"],
                $item["SerieType"],
                $item["SerieDescription"],
                $item["SerieStatus"]
            ),
            $response["data"]
        );
    }

    /**
     * Retrieves a series by its unique identifier.
     *
     * @param string $uid The unique identifier of the series.
     * @return Types\Series The series object with the specified unique identifier.
     */
    public function get_by_uid(string $uid): Types\Series
    {
        $response =  $this->execute_get_request([$uid]);
        return new Types\Series(
            $response["data"]["SerieID"],
            $response["data"]["SerieName"],
            $response["data"]["SerieType"],
            $response["data"]["SerieDescription"],
            $response["data"]["SerieStatus"]
        );
    }

    /**
     * Creates a new series with the given letter, document type, and optional folio.
     *
     * @param string $letter The letter of the series. With this parameter you can define a
     *                        letter as an identifier for the series.
     * @param string $document_type The type of CFDI or document that corresponds to the series.
     * @param int|null $folio It is used to define a custom number to start the counter for the
     *                        series(optional).
     * @throws FacturaComException If the document type is invalid.
     * @return string The message from the response.
     */
    public function create(string $letter, string $document_type, int $folio = null): string
    {
        $document_type_reflection = new ReflectionClass(Constants\DocumentType::class);
        $valid_document_types = $document_type_reflection->getConstants();
        if (!in_array($document_type, $valid_document_types)) {
            throw new FacturaComException("Invalid document type. Valid types are: " . implode(", ", $valid_document_types));
        }

        $data = [
            "letra" => $letter,
            "tipoDocumento" => $document_type,
            "folio" => $folio
        ];

        $response =  $this->execute_post_request(["create"], $data);
        return $response["message"];
    }

    /**
     * Disables a series by its unique identifier.
     *
     * @param string $uid The unique identifier of the series to disable.
     * @throws FacturaComException If an error occurs during the disabling process.
     * @return string The message indicating the success of the disabling.
     */
    public function disable(string $uid): string
    {
        $response =  $this->execute_post_request([$uid, "down"]);
        return $response["message"];
    }

    /**
     * Enables a series by its unique identifier.
     *
     * @param string $uid The unique identifier of the series to enable.
     * @throws FacturaComException If an error occurs during the enabling process.
     * @return string The message indicating the success of the enabling.
     */
    public function enable(string $uid): string
    {
        $response = $this->execute_post_request([$uid, "up"]);
        return $response["message"];
    }

    /**
     * Deletes a series by its unique identifier.
     *
     * @param string $uid The unique identifier of the series to delete.
     * @throws FacturaComException If an error occurs during the deletion process.
     * @return string The message indicating the success of the deletion.
     */
    public function delete(string $uid): string
    {
        $response = $this->execute_post_request([$uid, "drop"]);
        return $response["message"];
    }
}
