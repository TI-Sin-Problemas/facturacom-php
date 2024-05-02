<?php

namespace TiSinProblemas\FacturaCom\Types;

use stdClass;


class Contact
{
    public $name;
    public $last_name;
    public $email;
    public $email2;
    public $email3;
    public $phone;

    /**
     * Constructs a new instance of the Customer Contact class.
     *
     * @param string $name The name of the contact.
     * @param string $last_name The last name of the contact.
     * @param string $email The email of the contact.
     * @param string|null $email2 The second email of the contact.
     * @param string|null $email3 The third email of the contact.
     * @param string $phone The phone number of the contact.
     */
    function __construct(string $name, string $last_name, string $email, string $email2 = null, string $email3 = null, string $phone)
    {
        $this->name = $name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->email2 = $email2;
        $this->email3 = $email3;
        $this->phone = $phone;
    }
}


class Customer
{
    public $uid;
    public $company_name; // Razón social
    public $rfc;
    public $tax_regime; // Regimen fiscal
    public $tax_regime_id; // Código del reégimen fiscal
    public $street;
    public $street_number; // Número exterior
    public $building_number; // Número interior
    public $neibourhood; // Colonia
    public $zip_code;
    public $city;
    public $municipality; // Delegación
    public $state;
    public $locality; // Localidad
    public $country;
    public $num_reg_id_trib; // NumRegIdTrib
    public $cfdi_usage; // UsoCFDI
    public $contact;
    public $cfdi_qty;
    public $bank_accounts;

    /**
     * Constructs a new instance of the Customer class.
     *
     * @param string $uid The UID of the Customer.
     * @param string $company_name The company name (Razón social).
     * @param string $rfc The RFC of the Customer.
     * @param string $tax_regime The tax regime of the Customer (Regimen fiscal).
     * @param string $tax_regime_id The ID of the tax regime of the Customer (Código del reégimen fiscal).
     * @param string $street The street of the Customer.
     * @param string $street_number The street number of the Customer.
     * @param string $building_number The building number of the Customer.
     * @param string $neighborhood The neighborhood of the Customer.
     * @param string $zip_code The ZIP code of the Customer.
     * @param string $city The city of the Customer.
     * @param string $municipality The municipality of the Customer.
     * @param string $state The state of the Customer.
     * @param string|null $locality The locality of the Customer.
     * @param string $country The country of the Customer.
     * @param string|null $num_reg_id_trib The number of registration ID of the Customer.
     * @param string $cfdi_usage The CFDI usage of the Customer.
     * @param Contact $contact The contact information of the Customer.
     * @param int $cfdi_qty The CFDI quantity of the Customer.
     * @param array $bank_accounts The bank accounts of the Customer.
     * @return void
     */
    function __construct(
        string $uid,
        string $company_name,
        string $rfc,
        string $tax_regime = null,
        string $tax_regime_id = null,
        string $street,
        string $street_number,
        string $building_number,
        string $neibourhood,
        string $zip_code,
        string $city,
        string $municipality,
        string $state,
        string $locality = null,
        string $country,
        string $num_reg_id_trib = null,
        string $cfdi_usage = null,
        Contact $contact,
        int $cfdi_qty,
        array $bank_accounts
    ) {
        $this->uid = $uid;
        $this->company_name = $company_name;
        $this->rfc = $rfc;
        $this->tax_regime = $tax_regime;
        $this->tax_regime_id = $tax_regime_id;
        $this->street = $street;
        $this->street_number = $street_number;
        $this->building_number = $building_number;
        $this->neibourhood = $neibourhood;
        $this->zip_code = $zip_code;
        $this->city = $city;
        $this->municipality = $municipality;
        $this->state = $state;
        $this->locality = $locality;
        $this->country = $country;
        $this->num_reg_id_trib = $num_reg_id_trib;
        $this->cfdi_usage = $cfdi_usage;
        $this->contact = $contact;
        $this->cfdi_qty = $cfdi_qty;
        $this->bank_accounts = $bank_accounts;
    }
}
