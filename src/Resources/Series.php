<?php

namespace TiSinProblemas\FacturaCom\Resources;

require_once __DIR__ . '/../Types/Series.php';

use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types;

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
}
