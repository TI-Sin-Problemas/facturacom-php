<?php

namespace TiSinProblemas\FacturaCom\Constants;

class DocumentType
{
    const FACTURA = "factura"; // Factura
    const FACTURA_HOTEL = "factura_hotel"; // Factura para hoteles
    const HONORARIOS = "honorarios"; // Recibo de honorarios
    const NOTA_CARGO = "nota_cargo"; // Nota de cargo
    const DONATIVOS = "donativos"; // Donativo
    const ARRENDAMIENTO = "arrendamiento"; // Recibo de arrendamiento
    const NOTA_CREDITO = "nota_credito"; // Nota de crédito
    const NOTA_DEBITO = "nota_debito"; // Nota de débito
    const NOTA_DEVOLUCION = "nota_devolucion"; // Nota de devolución
    const CARTA_PORTE = "carta_porte"; // Carta porte
    const CARTA_PORTE_INGRESO = "carta_porte_ingreso"; // Carta porte de Ingreso
    const PAGO = "pago"; // Pago
    const RETENCION = "retencion"; // Retención
}

class TaxFactorType
{
    const TASA = "Tasa";
    const CUOTA = "Cuota";
    const EXENTO = "Exento";
}
