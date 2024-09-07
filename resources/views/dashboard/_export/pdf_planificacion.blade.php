<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>ViewPDF | Planificación</title>

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
                            <img src="{{ verImagen('img/logo.png') }}" style="width: 100px;"/>
                        </td>

                        <td>
                            Código: <strong
                                    style="font-weight: bold;color: red;">{{ $planificacion->codigo }}</strong><br/>
                            Semana: {{ $semana[0]."-".$semana[8] }}
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
                            Planificación semanal: {{ $planificacion->descripcion }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        @if($lunes->isNotEmpty())
            <tr>
                <td colspan="2">Lunes: {{ getFecha($semana[1], "d-m-Y") }}</td>
            </tr>
            <tr class="heading">
                <td>Receta</td>
                <td>Cantidad</td>
            </tr>
            @foreach($lunes as $detalle)
                <tr class="item">
                    <td>
                        <span>{{ mb_strtoupper($detalle->receta->descripcion) }}</span>
                        <small>[{{ $detalle->receta->codigo }}]</small>
                    </td>
                    <td>{{ formatoMillares($detalle->cantidad, 3) }}</td>
                </tr>
            @endforeach
        @endif

        @if($martes->isNotEmpty())
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">Martes: {{ getFecha($semana[2], "d-m-Y") }}</td>
            </tr>
            <tr class="heading">
                <td>Receta</td>
                <td>Cantidad</td>
            </tr>
            @foreach($martes as $detalle)
                <tr class="item">
                    <td>
                        <span>{{ mb_strtoupper($detalle->receta->descripcion) }}</span>
                        <small>[{{ $detalle->receta->codigo }}]</small>
                    </td>
                    <td>{{ formatoMillares($detalle->cantidad, 3) }}</td>
                </tr>
            @endforeach
        @endif

        @if($miercoles->isNotEmpty())
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">Miercoles: {{ getFecha($semana[2], "d-m-Y") }}</td>
            </tr>
            <tr class="heading">
                <td>Receta</td>
                <td>Cantidad</td>
            </tr>
            @foreach($miercoles as $detalle)
                <tr class="item">
                    <td>
                        <span>{{ mb_strtoupper($detalle->receta->descripcion) }}</span>
                        <small>[{{ $detalle->receta->codigo }}]</small>
                    </td>
                    <td>{{ formatoMillares($detalle->cantidad, 3) }}</td>
                </tr>
            @endforeach
        @endif

        @if($jueves->isNotEmpty())
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">Jueves: {{ getFecha($semana[2], "d-m-Y") }}</td>
            </tr>
            <tr class="heading">
                <td>Receta</td>
                <td>Cantidad</td>
            </tr>
            @foreach($jueves as $detalle)
                <tr class="item">
                    <td>
                        <span>{{ mb_strtoupper($detalle->receta->descripcion) }}</span>
                        <small>[{{ $detalle->receta->codigo }}]</small>
                    </td>
                    <td>{{ formatoMillares($detalle->cantidad, 3) }}</td>
                </tr>
            @endforeach
        @endif

        @if($viernes->isNotEmpty())
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">Viernes: {{ getFecha($semana[2], "d-m-Y") }}</td>
            </tr>
            <tr class="heading">
                <td>Receta</td>
                <td>Cantidad</td>
            </tr>
            @foreach($viernes as $detalle)
                <tr class="item">
                    <td>
                        <span>{{ mb_strtoupper($detalle->receta->descripcion) }}</span>
                        <small>[{{ $detalle->receta->codigo }}]</small>
                    </td>
                    <td>{{ formatoMillares($detalle->cantidad, 3) }}</td>
                </tr>
            @endforeach
        @endif

        @if($sabado->isNotEmpty())
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">Sabado: {{ getFecha($semana[2], "d-m-Y") }}</td>
            </tr>
            <tr class="heading">
                <td>Receta</td>
                <td>Cantidad</td>
            </tr>
            @foreach($sabado as $detalle)
                <tr class="item">
                    <td>
                        <span>{{ mb_strtoupper($detalle->receta->descripcion) }}</span>
                        <small>[{{ $detalle->receta->codigo }}]</small>
                    </td>
                    <td>{{ formatoMillares($detalle->cantidad, 3) }}</td>
                </tr>
            @endforeach
        @endif

        @if($domingo->isNotEmpty())
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">Domingo: {{ getFecha($semana[2], "d-m-Y") }}</td>
            </tr>
            <tr class="heading">
                <td>Receta</td>
                <td>Cantidad</td>
            </tr>
            @foreach($domingo as $detalle)
                <tr class="item">
                    <td>
                        <span>{{ mb_strtoupper($detalle->receta->descripcion) }}</span>
                        <small>[{{ $detalle->receta->codigo }}]</small>
                    </td>
                    <td>{{ formatoMillares($detalle->cantidad, 3) }}</td>
                </tr>
            @endforeach
        @endif

    </table>
</div>
</body>
</html>
