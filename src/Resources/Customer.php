<?php

namespace TiSinProblemas\FacturaCom\Resources;

use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types;

class Customer extends BaseCilent
{
    protected $ENDPOINT = "clients";

    private function build_customer($data)
    {
        $contact = new Types\Contact(
            $data["Contacto"]["Nombre"],
            $data["Contacto"]["Apellidos"],
            $data["Contacto"]["Email"],
            $data["Contacto"]["Email2"],
            $data["Contacto"]["Email3"],
            $data["Contacto"]["Telefono"]
        );

        return new Types\Customer(
            $data["UID"],
            $data["RazonSocial"],
            $data["RFC"],
            $data["Regimen"],
            $data["RegimenId"],
            $data["Calle"],
            $data["Numero"],
            $data["Interior"],
            $data["Colonia"],
            $data["CodigoPostal"],
            $data["Ciudad"],
            $data["Delegacion"],
            $data["Estado"],
            key_exists("Localidad", $data) ? $data["Localidad"] : null,
            $data["Pais"],
            $data["NumRegIdTrib"],
            $data["UsoCFDI"],
            $contact,
            $data["cfdis"],
            $data["cuentas_banco"]
        );
    }

    /**
     * Retrieves all customers from the API and returns an array of Customer objects.
     *
     * @return array An array of Customer objects representing all customers.
     * @throws FacturaComException If the API response status is not "success".
     */
    public function all()
    {
        $response = json_decode($this->get()->getBody(), true);
        if ($response["status"] != "success") {
            throw new FacturaComException($response["message"]);
        }
        $ret = [];
        foreach ($response["data"] as $customer) {
            $ret[] = $this->build_customer($customer);
        }
        return $ret;
    }

    /**
     * Retrieves a customer object by its RFC or UID from the API.
     *
     * @param string $id The RFC or UID of the customer to retrieve.
     * @throws FacturaComException If the API response status is not "success".
     * @return Types\Customer The customer object with the specified RFC or UID.
     */
    public function get_by_id(string $id)
    {
        $response = json_decode($this->get([$id])->getBody(), true);
        if ($response["status"] != "success") {
            throw new FacturaComException($response["message"]);
        }
        return $this->build_customer($response["Data"]);
    }

    /**
     * Retrieves a list of customers by their duplicated RFC.
     *
     * @param string $rfc The duplicated RFC to search for.
     * @throws FacturaComException If an error occurs during retrieval.
     * @return array An array of customer objects.
     */
    public function filter_duplicated_by_rfc(string $rfc)
    {
        $response = json_decode($this->get(["rfc", $rfc])->getBody(), true);
        if ($response["status"] != "success") {
            throw new FacturaComException($response["message"]);
        }
        $ret = [];
        foreach ($response["Data"] as $customer) {
            $ret[] = $this->build_customer($customer);
        }
        return $ret;
    }
}
