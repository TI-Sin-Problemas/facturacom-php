<?php

namespace TiSinProblemas\FacturaCom\Resources;

use TiSinProblemas\FacturaCom\Exceptions\FacturaComException;
use TiSinProblemas\FacturaCom\Http\BaseCilent;
use TiSinProblemas\FacturaCom\Types;

class Customer extends BaseCilent
{
    protected $ENDPOINT = "clients";
    protected $API_VERSION = "v1";

    /**
     * Builds a customer object based on the given data.
     *
     * @param array $data The data used to build the customer object.
     *                    The data should have the following structure:
     *                    [
     *                        "UID" => string,
     *                        "RazonSocial" => string,
     *                        "RFC" => string,
     *                        "Regimen" => string,
     *                        "RegimenId" => int,
     *                        "Calle" => string,
     *                        "Numero" => string,
     *                        "Interior" => string|null,
     *                        "Colonia" => string,
     *                        "CodigoPostal" => string,
     *                        "Ciudad" => string,
     *                        "Delegacion" => string,
     *                        "Estado" => string,
     *                        "Localidad" => string|null,
     *                        "Pais" => string,
     *                        "NumRegIdTrib" => string,
     *                        "UsoCFDI" => string,
     *                        "Contacto" => [
     *                            "Nombre" => string,
     *                            "Apellidos" => string,
     *                            "Email" => string,
     *                            "Email2" => string|null,
     *                            "Email3" => string|null,
     *                            "Telefono" => string
     *                        ],
     *                        "cfdis" => array,
     *                        "cuentas_banco" => array
     *                    ]
     * @return Types\Customer The built customer object.
     */
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

    /**
     * Creates a new customer record.
     *
     * @param string $rfc The RFC (Registro Federal de Contribuyentes) of the customer.
     * @param string $company_name The name of the company (Razon Social) of the customer.
     * @param string $zip_code The zip code of the customer.
     * @param string $email The email of the customer.
     * @param int $tax_regime The tax regime of the customer (Regimen Fiscal).
     * @param string|null $cfdi_usage The CFDI usage (Uso de CFDI) code. Default is null.
     * @param string|null $street The street name of the customer. Default is null.
     * @param string|null $street_number The street number of the customer. Default is null.
     * @param string|null $building_number The building number of the customer. Default is null.
     * @param string|null $neighborhood The neighborhood of the customer. Default is null.
     * @param string|null $city The city of the customer. Default is null.
     * @param string|null $municipality The municipality of the customer. Default is null.
     * @param string|null $locality The locality of the customer. Default is null.
     * @param string|null $state The state of the customer. Default is null.
     * @param string $country The country of the customer. Default is "MEX".
     * @param string|null $foreign_tax_id The foreign tax ID of the customer. In case of foreign customers. Default is null.
     * @param string|null $first_name The first name of the customer. Default is null.
     * @param string|null $last_name The last name of the customer. Default is null.
     * @param string|null $phone The phone number of the customer. Default is null.
     * @param string|null $email2 The secondary email of the customer. Default is null.
     * @param string|null $email3 The tertiary email of the customer. Default is null.
     * @throws FacturaComException If the API response status is not "success".
     * @return Types\Customer The customer data built from the API response.
     */
    public function create(
        string $rfc,
        string $company_name,
        string $zip_code,
        string $email,
        int $tax_regime,
        string $street = null,
        string $cfdi_usage = null,
        string $street_number = null,
        string $building_number = null,
        string $neighborhood = null,
        string $city = null,
        string $municipality = null,
        string $locality = null,
        string $state = null,
        string $country = "MEX",
        string $foreign_tax_id = null,
        string $first_name = null,
        string $last_name = null,
        string $phone = null,
        string $email2 = null,
        string $email3 = null
    ) {
        $data = [
            "rfc" => $rfc,
            "razons" => $company_name,
            "codpos" => $zip_code,
            "email" => $email,
            "usocfdi" => $cfdi_usage,
            "regimen" => $tax_regime,
            "calle" => $street,
            "numero_exterior" => $street_number,
            "numero_interior" => $building_number,
            "colonia" => $neighborhood,
            "ciudad" => $city,
            "delegacion" => $municipality,
            "localidad" => $locality,
            "estado" => $state,
            "pais" => $country,
            "numregidtrib" => $foreign_tax_id,
            "nombre" => $first_name,
            "apellidos" => $last_name,
            "telefono" => $phone,
            "email2" => $email2,
            "email3" => $email3
        ];

        $response = json_decode($this->post(["create"], $data)->getBody(), true);
        if ($response["status"] != "success") {
            $msg = "";
            foreach ($response["message"] as $key => $value) {
                $msg .= $key . ": " . implode(" ", $value) . "\n";
            }
            throw new FacturaComException($msg);
        }
        return $this->build_customer($response["Data"]);
    }

    /**
     * Updates a customer record with the provided data.
     *
     * @param string $uid The UID of the customer record to update.
     * @param string|null $rfc The RFC (Registro Federal de Contribuyentes) of the customer. Optional.
     * @param string|null $company_name The name of the company (Razon Social) of the customer. Optional.
     * @param string|null $zip_code The zip code of the customer. Optional.
     * @param string|null $email The email of the customer. Optional.
     * @param int|null $tax_regime The tax regime of the customer (Regimen Fiscal). Optional.
     * @param string|null $cfdi_usage The CFDI usage (Uso de CFDI) code. Optional.
     * @param string|null $street The street name of the customer. Optional.
     * @param string|null $street_number The street number of the customer. Optional.
     * @param string|null $building_number The building number of the customer. Optional.
     * @param string|null $neighborhood The neighborhood of the customer. Optional.
     * @param string|null $city The city of the customer. Optional.
     * @param string|null $municipality The municipality of the customer. Optional.
     * @param string|null $locality The locality of the customer. Optional.
     * @param string|null $state The state of the customer. Optional.
     * @param string|null $country The country of the customer. Optional.
     * @param string|null $foreign_tax_id The foreign tax ID of the customer. In case of foreign customers. Optional.
     * @param string|null $first_name The first name of the customer. Optional.
     * @param string|null $last_name The last name of the customer. Optional.
     * @param string|null $phone The phone number of the customer. Optional.
     * @param string|null $email2 The secondary email of the customer. Optional.
     * @param string|null $email3 The tertiary email of the customer. Optional.
     * @throws FacturaComException If the API response status is not "success".
     * @return Types\Customer The updated customer data built from the API response.
     */
    public function update(
        string $uid,
        string $rfc = null,
        string $company_name = null,
        string $zip_code = null,
        string $email = null,
        int $tax_regime = null,
        string $cfdi_usage = null,
        string $street = null,
        string $street_number = null,
        string $building_number = null,
        string $neighborhood = null,
        string $city = null,
        string $municipality = null,
        string $locality = null,
        string $state = null,
        string $country = null,
        string $foreign_tax_id = null,
        string $first_name = null,
        string $last_name = null,
        string $phone = null,
        string $email2 = null,
        string $email3 = null
    ) {
        // build data
        $data = [];
        if ($rfc) $data["rfc"] = $rfc;
        if ($company_name) $data["razons"] = $company_name;
        if ($zip_code) $data["codpos"] = $zip_code;
        if ($email) $data["email"] = $email;
        if ($tax_regime) $data["regimen"] = $tax_regime;
        if ($cfdi_usage) $data["usocfdi"] = $cfdi_usage;
        if ($street) $data["calle"] = $street;
        if ($street_number) $data["numero_exterior"] = $street_number;
        if ($building_number) $data["numero_interior"] = $building_number;
        if ($neighborhood) $data["colonia"] = $neighborhood;
        if ($city) $data["ciudad"] = $city;
        if ($municipality) $data["delegacion"] = $municipality;
        if ($locality) $data["localidad"] = $locality;
        if ($state) $data["estado"] = $state;
        if ($country) $data["pais"] = $country;
        if ($foreign_tax_id) $data["numregidtrib"] = $foreign_tax_id;
        if ($first_name) $data["nombre"] = $first_name;
        if ($last_name) $data["apellidos"] = $last_name;
        if ($phone) $data["telefono"] = $phone;
        if ($email2) $data["email2"] = $email2;
        if ($email3) $data["email3"] = $email3;

        $response = json_decode($this->post([$uid, "update"], $data)->getBody(), true);

        if ($response["status"] != "success") {
            $msg = "";
            foreach ($response["message"] as $key => $value) {
                $msg .= $key . ": " . implode(" ", $value) . "\n";
            }
            throw new FacturaComException($msg);
        }

        return $this->build_customer($response["Data"]);
    }

    /**
     * Deletes a customer by their unique identifier.
     *
     * @param string $uid The unique identifier of the customer to delete.
     * @throws FacturaComException If an error occurs during the deletion process.
     * @return string The message indicating the success of the deletion.
     */
    public function delete(string $uid)
    {
        $response = json_decode($this->post(["destroy", $uid])->getBody(), true);

        if ($response["response"] != "success") {
            throw new FacturaComException($response["message"]);
        }

        return $response["message"];
    }
}
