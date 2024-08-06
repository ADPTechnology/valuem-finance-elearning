<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Anexo 4</title>

    <style media="screen">
        /* @font-face {
            font-family: arial;
            src: url('fonts/ARI.ttf');
        } */

        body {
            position: relative;
        }

        .content {
            margin-top: 20px;
            font-family: helvetica;
        }

        .div-sello-parada {
            position: absolute;
            transform: translateX(550px) translateY(0px);
        }

        .img-sello-parada {
            width: 140px;
            height: auto;
        }

        .center {
            text-align: center;
        }

        .top-1 {
            font-size: 18px;
        }

        .top-2 {
            font-size: 14px;
        }

        .details {
            margin-top: 15px;
        }

        .details-top {
            text-align: center;
            font-size: 14px;
        }

        .details-table {
            margin-top: 5px;
            font-size: 12px;
        }

        .imagenes {
            margin-top: 20px;
            margin-left: -20px;
            margin-right: -20px;
            height: 550px;
        }

        .nexa-vb {
            margin: 0;
            position: absolute;
            bottom: 2cm;
            right: 0;
        }

        .div-sello {
            position: absolute;
            transform: translateX(0px) translateY(180px);
            height: 200px;
            width: 110px;
        }

        .img-sello {
            width: 110px;
            height: auto;
        }

        .img-firma-sello {
            width: 110px;
            height: auto;
        }

        .txt-firma-sello {
            font-size: 8px;
            font-weight: bold;
            text-align: center;
        }


        .div-anexo {
            position: absolute;
            transform: translateX(110px) translateY(0px);
        }

        .img-anexo {
            width: 600px;
            height: auto;
        }

        .cuerpo-subtitle {
            font-size: 18px;
            margin-top: 10px;
            text-align: center;
        }

        .signature-section {
            width: 100%;
            position: absolute;
            bottom: 0;
            height: auto;
        }

        .signature-employee-img {
            height: 70px;
            width: auto;
        }

        .signature-employee-description {
            font-size: 10px;
            width: auto;
        }

        .signature-employee-bot {
            font-size: 12px;
        }

        .signature-manager-img {
            height: 70px;
            width: auto;
        }

        .signature-manager-description {
            margin-top: 5px;
            font-size: 10px;
        }

        hr.style1 {
            border-top: 0.1px solid;
        }


        table,
        td,
        th {
            border: 1px solid black;

        }

        td {
            width: 50%;
            padding: 3px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .f-left {float: left;}
        .f-right {float: right;}
        .f-clear {clear: both;}
        .w-50 {width: 50%;}

        .pos-relative {position: relative;}
        .pos-absolute {position: absolute;}

        .inner-signature-container
        {
            height: 100%;
        }

        .signature-employee
        {
            position: absolute;
            bottom: .67cm;
            left: 1.3cm;
            width: 250px;
            text-align: center;
        }

        .signature-manager {
            position: absolute;
            bottom: .5cm;
            right: 1.3cm;
            width: 250px;
            text-align: center;
            font-size: 10px;
        }

    </style>
</head>

<body>

    @if (Str::is('*TRABAJOS ESPECIALES*', strtoupper($certification->course->description)))
    <div class="div-sello-parada">
        <img src="{{ public_path('assets/certificates/images/sello_te.png') }}" class="img-sello-parada" alt="">
    </div>
    @elseif (Str::is('*PARADA DE PLANTA*', strtoupper($certification->course->description)))
    <div class="div-sello-parada">
        <img src="{{ public_path('assets/certificates/images/sello_parada_planta.png') }}" class="img-sello-parada" alt="">
    </div>
    @else
    <div class="div-sello-parada">
        <img src="{{ public_path('assets/certificates/images/sello_anexo_4.png') }}" class="img-sello-parada" alt="">
    </div>
    @endif
    <div class="content">

        <div class="center">
            <div class="top-1">
                <strong>ANEXO N° 4</strong>
            </div>
            <div class="top-2">
                <span>INDUCCIÓN Y ORIENTACIÓN BÁSICA</span>
            </div>
            <div class="details-top">
                PARA USO DE LA GERENCIA DE SEGURIDAD Y SALUD OCUPACIONAL
            </div>
        </div>
        <div class="details">
            <div class="details-table">
                <table>
                    <tr>
                        <td>Titular: {{ strtoupper($miningUnit->owner ?? '-') }}</td>
                        <td>Trabajador: {{ strtoupper($certification->user->full_name_complete_reverse) }}</td>
                    </tr>
                    <tr>
                        <td>ECM : {{ $certification->company->abbreviation }}</td>
                        <td>Fecha de Ingreso: {{
                            getCarbonInstance($certification->event->date)->addDay(1)->isoFormat('DD/MM/YYYY') }}</td>
                    </tr>
                    <tr>
                        <td>Unidad de Producción: {{ strtoupper($miningUnit->description) }}</td>
                        <td>Registro o N° de Fotocheck: {{ $certification->user->dni }}</td>
                    </tr>
                    <tr>
                        <td>Distrito: {{ strtoupper($miningUnit->district) }}</td>
                        <td>Ocupación: {{ strtoupper($certification->user->position) }}</td>
                    </tr>
                    <tr>
                        <td>Provincia: {{ strtoupper($miningUnit->Province) }}</td>
                        <td>Area de Trabajo: MINA / SUPERFICIE</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="imagenes">
            <div class="div-sello">

                <img src="{{ public_path("assets/certificates/images/sello_2023.png") }}" class="img-sello" alt="">
                <div class="">
                    @if ($certification->event->user->signature == 'S' )
                    <img src="{{ $certification->event->user->file->file_url }}" class="img-firma-sello" alt="">
                    @else
                    <span style="margin-left: 15px; font-size: 12px; font-weight: normal;">No tiene firma.</span>
                    @endif

                </div>
                <div class="txt-firma-sello">
                    Documento firmado el<br>
                    {{ getCarbonInstance($certification->event->date)->isoFormat('DD-MM-YYYY') }} <br>
                    por el instructor S.S.O <br>
                    <span>{{ 'con CIP: '. $certification->event->user->cip }}</span>
                </div>
            </div>


            <div class="div-anexo">
                <img src="{{ public_path('assets/certificates/images/anexo4.png') }}" class="img-anexo" alt="">
            </div>


        </div>

        <div class="">
            <div class="cuerpo-subtitle">
                <span>Pasco, {{ $certification->event->date_es }}</span>
            </div>
        </div>

    </div>

    <div class="signature-section">

        <div class="inner-signature-container pos-relative">

            <div class="signature-employee">

                <div class="">
                    <img src="{{ $certification->user->file->file_url }}" class="signature-employee-img" align="middle"
                        alt="">
                </div>
    
                <div class="signature-employee-description">
                    <span>Documento firmado el</span><br>
                    <span>{{ getCarbonInstance($certification->event->date)->isoFormat('DD-MM-YYYY') }}</span><br>
                    <span>por el usuario</span><br>
                    <span>con DNI: {{ $certification->user->dni }}</span>
                </div>
                <hr class="style1">
                <div class="signature-employee-bot">
                    Firma del Trabajador
                </div>
            </div>

            @php
                $sufix = getMiningUnitSufix($miningUnit->description);
                if ($sufix == 'A') {
                    $security_user = $certification->event->security;
                    $vb_nexa = public_path('assets/certificates/images/vb-nexa.png');
                };
                if ($sufix == 'P') {
                    $security_user = $certification->event->securityPor;
                    $vb_nexa = public_path('assets/certificates/images/vb-nexa2.png');
                };
            @endphp

            @if ($security_user ?? false)

            <div class="nexa-vb">
                <img src="{{ $vb_nexa }}" class="signature-manager-img" alt="">
            </div>
                
            @endif
    
            <div class="signature-manager">
    
                @if ($security_user ?? false)
    
                <div class="">
                    <img src="{{ $url }}{{ $certification->event->id }}_{{ $security_user->dni }}.png" class="signature-manager-img" align="middle" alt="">
                </div>
    
                <div class="signature-manager-description">
    
                    <span>Documento firmado el </span><br>
                    <span>{{ getCarbonInstance($certification->event->date)->isoFormat('DD-MM-YYYY') }}</span><br>
    
                    <span>por: {{ $security_user->full_name_complete }}
                    </span><br>
                    <span>con CIP: {{ $security_user->cip }}</span>
    
                </div>
    
                @endif
    
                <hr class="style1">
                <span>VºBº del Gerente de Seguridad y Salud</span><br>
                <span>Ocupacional o Ingeniero de Seguridad</span>
            </div>

        </div>

    </div>

</body>

</html>