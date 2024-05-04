<?php

namespace TiSinProblemas\FacturaCom\Resources;

use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types;

class Customer extends BaseCilent
{
    protected $ENDPOINT = "clients";

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
            $ret[] = new Types\Customer(
                $customer["UID"],
                $customer["RazonSocial"],
                $customer["RFC"],
                $customer["Regimen"],
                $customer["RegimenId"],
                $customer["Calle"],
                $customer["Numero"],
                $customer["Interior"],
                $customer["Colonia"],
                $customer["CodigoPostal"],
                $customer["Ciudad"],
                $customer["Delegacion"],
                $customer["Estado"],
                $customer["Localidad"],
                $customer["Pais"],
                $customer["NumRegIdTrib"],
                $customer["UsoCFDI"],
                new Types\Contact(
                    $customer["Contacto"]["Nombre"],
                    $customer["Contacto"]["Apellidos"],
                    $customer["Contacto"]["Email"],
                    $customer["Contacto"]["Email2"],
                    $customer["Contacto"]["Email3"],
                    $customer["Contacto"]["Telefono"]
                ),
                $customer["cfdis"],
                $customer["cuentas_banco"]
            );
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
        $data = $response["Data"];
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
            $data["Localidad"],
            $data["Pais"],
            $data["NumRegIdTrib"],
            $data["UsoCFDI"],
            new Types\Contact(
                $data["Contacto"]["Nombre"],
                $data["Contacto"]["Apellidos"],
                $data["Contacto"]["Email"],
                $data["Contacto"]["Email2"],
                $data["Contacto"]["Email3"],
                $data["Contacto"]["Telefono"]
            ),
            $data["cfdis"],
            $data["cuentas_banco"]
        );
    }
}
