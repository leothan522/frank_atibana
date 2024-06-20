<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>ViewPDF</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
</head>

<body>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img src="{{ asset('img/logo.png') }}" style="width: 100px;" />
                        </td>

                        <td>
                            Factura #: <strong style="font-weight: bold;color: red;">{{ $factura->factura_numero }}</strong><br />
                            Fecha: {{ verFecha($factura->factura_fecha) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            Dirección:<br />
                            {{ $factura->cliente_direccion }}
                        </td>
                        <td>
                            Cedula: {{ $factura->cliente_cedula }}<br />
                            Nombre: {{ $factura->cliente_nombre }} {{ $factura->cliente_apellido }}<br />
                            Teléfono: {{ $factura->cliente_telefono }}<br />
                            Email: {{ $factura->cliente_email }}<br />
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>Plan de Servicio</td>

            <td>Total</td>
        </tr>

        <tr class="item">
            <td>
                {{ $factura->plan_etiqueta }}
            </td>
            <td>{{ $factura->organizacion_moneda }} {{ formatoMillares($factura->plan_precio) }}</td>
        </tr>

        <tr class="total">
            <td></td>
            <td style="background: #eee; border-bottom: 1px solid #ddd;"><small><small style="float: left;">Subtotal:</small></small> {{ $factura->organizacion_moneda }} {{ formatoMillares($factura->plan_precio) }}</td>
        </tr>
        <tr class="total">
            <td></td>
            <td style="background: #eee; border-bottom: 1px solid #ddd;"><small><small style="float: left;">I.V.A.:</small></small>-</td>
        </tr>
        <tr class="total">
            <td></td>
            <td style="background: #eee; border-bottom: 1px solid #ddd; font-weight: bold;color: red;"><span style="float: left;">Total:</span> {{ $factura->organizacion_moneda }} {{ formatoMillares($factura->plan_precio) }}</td>
        </tr>
    </table>
</div>
</body>
</html>
