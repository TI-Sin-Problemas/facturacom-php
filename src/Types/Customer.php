<?php

namespace TiSinProblemas\FacturaCom\Types;

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
     * @param string|null $name The name of the contact.
     * @param string|null $last_name The last name of the contact.
     * @param string $email The email of the contact.
     * @param string|null $email2 The second email of the contact.
     * @param string|null $email3 The third email of the contact.
     * @param string|null $phone The phone number of the contact.
     */
    function __construct(
        string $name = null,
        string $last_name = null,
        string $email,
        string $email2 = null,
        string $email3 = null,
        string $phone = null
    ) {
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
    public $neighborhood; // Colonia
    public $zip_code;
    public $city;
    public $municipality; // Delegación
    public $state;
    public $locality; // Localidad
    public $country;
    public $foreign_tax_id; // NumRegIdTrib
    public $cfdi_use; // UsoCFDI
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
     * @param string|null $street The street of the Customer.
     * @param string|null $street_number The street number of the Customer.
     * @param string|null $building_number The building number of the Customer.
     * @param string|null $neighborhood The neighborhood of the Customer.
     * @param string $zip_code The ZIP code of the Customer.
     * @param string|null $city The city of the Customer.
     * @param string|null $municipality The municipality of the Customer.
     * @param string|null $state The state of the Customer.
     * @param string|null $locality The locality of the Customer.
     * @param string $country The country of the Customer.
     * @param string|null $foreign_tax_id The number of registration ID of the Customer.
     * @param string|null $cfdi_use The CFDI use of the Customer.
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
        string $street = null,
        string $street_number = null,
        string $building_number = null,
        string $neighborhood = null,
        string $zip_code,
        string $city = null,
        string $municipality = null,
        string $state = null,
        string $locality = null,
        string $country,
        string $foreign_tax_id = null,
        string $cfdi_use = null,
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
        $this->neighborhood = $neighborhood;
        $this->zip_code = $zip_code;
        $this->city = $city;
        $this->municipality = $municipality;
        $this->state = $state;
        $this->locality = $locality;
        $this->country = $country;
        $this->foreign_tax_id = $foreign_tax_id;
        $this->cfdi_use = $cfdi_use;
        $this->contact = $contact;
        $this->cfdi_qty = $cfdi_qty;
        $this->bank_accounts = $bank_accounts;
    }
}
