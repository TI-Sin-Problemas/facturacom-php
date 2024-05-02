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
}
