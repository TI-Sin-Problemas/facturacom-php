<?php

namespace TiSinProblemas\FacturaCom\Constants;

abstract class BaseConstants
{
    public static function values()
    {
        echo static::class;
        $reflection = new \ReflectionClass(static::class);
        return $reflection->getConstants();
    }
}

class DocumentType extends BaseConstants
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

class TaxFactorType extends BaseConstants
{
    const TASA = "Tasa";
    const CUOTA = "Cuota";
    const EXENTO = "Exento";
}

class GlobalCfdiPeriodicity extends BaseConstants
{
    const DIARIO = "01";
    const SEMANAL = "02";
    const QUINCENAL = "03";
    const MENSUAL = "04";
    const BIMESTRAL = "05";
}

class GlobalCfdiMonth extends BaseConstants
{
    const ENERO = "01";
    const FEBRERO = "02";
    const MARZO = "03";
    const ABRIL = "04";
    const MAYO = "05";
    const JUNIO = "06";
    const JULIO = "07";
    const AGOSTO = "08";
    const SEPTIEMBRE = "09";
    const OCTUBRE = "10";
    const NOVIEMBRE = "11";
    const DICIEMBRE = "12";
    const ENERO_FEBRERO = "13";
    const MARZO_ABRIL = "14";
    const MAYO_JUNIO = "15";
    const JULIO_AGOSTO = "16";
    const SEPTIEMBRE_OCTUBRE = "17";
    const NOVIEMBRE_DICIEMBRE = "18";
}
