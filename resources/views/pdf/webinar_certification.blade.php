<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>Certificado HAMA</title>

        <style>

            @font-face
            {
                font-family: 'monotype-corsiva';
                src: url({{ storage_path("fonts/Monotype-Corsiva.ttf") }});  /* Ruta de la fuente personalizada
                                                                                (!) Sólo me funcionó usando el método "storage_path()"
                                                                                (!) la ruta actual es: "storage/fonts/Monotype-Corsiva.ttf"
                                                                            */
            }

            @font-face
            {
                font-family: 'bell-mt';
                font-weight: normal;
                src: url({{ storage_path('fonts/BELL.ttf') }});
            }
            @font-face
            {
                font-family: 'bell-mt-semibold';
                src: url({{ storage_path('fonts/Bell-SemiBold.ttf') }});
            }

            *
            {
                box-sizing: border-box;
                margin: 0; padding: 0;
                font-family: 'bell-mt', sans-serif;
            }

            p.txt-primary
            {
                padding: 0.5cm 0;
                font-size: 1.5rem;
                font-weight: light;
            }

            p.txt-primary + p
            {
                padding: 0 0 0.3cm 0;
            }

            p.text-secondary
            {
                font-size: 1.3rem;
                font-weight: light;
                padding: 0 !important;
                margin: 0 !important;
            }

            .monotype-font
            {
                font-family: 'monotype-corsiva', sans-serif !important;
            }

            .text-bold{font-weight: 600 !important;}
            .text-semibold{font-family: 'bell-mt-semibold' !important;}
            .text-light{font-weight: light !important;}
            .text-uppercase{text-transform: uppercase !important;}
            .text-left{text-align: left !important;}
            .text-center{text-align: center !important;}
            .f-left{float: left !important;}
            .f-right{float: right !important;}
            .f-clear{clear: both !important;}
            .f-clear-left{clear: left !important;}
            .f-clear-right{clear: right !important;}

            .content-container
            {
                position: relative;
                height: 15cm;
                margin: 0 1.3cm;
            }

            .content-container
            .content-child
            {
                height: 15cm;
            }

            .content-container
            .content-child.left
            {
                position: relative;
                width: 20.5cm;
            }

            .content-child.left
            .bottom-set
            {
                position: absolute;
                width: 20.5cm;
                bottom: 0;
                padding-bottom: 1.2cm;
            }

            .content-child.left
            .logo-container #logo-img
            {
                width: 6.2cm;
            }

            .content-child.left
            .participant-name
            {
                font-size: 4.3em;
                line-height: 1em;
                padding-bottom: 0.1em;
            }

            .participant-name > span
            {
                padding-bottom: 0.1em;
                border-bottom: 1px solid black;
            }

            .content-container
            .content-child.right
            {
                width: 6.6cm;
            }

            .content-child.right > div
            {
                width: 100%;
            }

            .content-child.right
            .upper-mark-container
            {
                height: 8cm;
            }

            .upper-mark-container
            .mark-img-cont 
            {
                width: 3.4cm;
                margin: auto;
            }

            .upper-mark-container
            .mark-img-cont img
            {
                height: 8cm;
            }

            .content-child.right
            .iso-mark-container
            {
                height: 7cm;
                position: relative;
            }

            .iso-mark-container img
            {
                position: absolute;
                height: 4.8cm;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            .footer
            {
                background-color: rgb(226, 226, 226);
                height: 4cm;
                position: relative;
                padding: 1cm 0;
            }

            .footer
            .footer-content
            {
                width: 25cm;
                height: 4cm;
                margin: auto;
            }

            .footer-content > div
            {
                width: 10.5cm;
                height: 4cm;
            }

            .footer-content
            .foot-left
            {
                position: relative;
            }

            .info-qr-container
            {
                height: 2.2cm;
            }

            .info-qr-container
            .qr-img-cont
            {
                width: 2.2cm;
            }

            .info-qr-container
            .text-qr-info
            {
                width: 8cm;
                height: 2.2cm;
                position: relative;
            }

            .text-qr-info
            .raw-text-info
            {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
            }

            .foot-left
            .info-qr-container
            .qr-img-cont img
            {
                width: 2.2cm;
            }

            .dates-container
            {
                position: absolute;
                width: 100%;
                left: 0;
                bottom: 0;
            }

            .foot-right
            .signature-container
            {
                height: 2.2cm;
                max-height: 2.28cm;
                width: 7cm;
                margin: 0 auto 0.2cm auto;
                border-bottom: 2px solid black;
                position: relative;
            }

            .signature-container img
            {
                position: absolute;
                height: 2.4cm;
                left: 50%;
                transform: translateX(-50%);
            }

        </style>
    </head>


    <body>

        <div class="content-container">

            <div class="content-child left general-info f-left">
                <div class="bottom-set">

                    <div class="logo-container">
                                    
                        <img id="logo-img" src="{{public_path('assets/certificates/images/hama-logo.png')}}" alt="">
                    </div>

                    <p class="txt-primary"> Otorga la presente constancia a: </p>

                    <div class="participant-name">
                        {{----- Nombre del participante -----}}
                        <span class="monotype-font">
                            {{ ucwords(mb_strtolower($certification->user->full_name_complete_reverse, 'UTF-8')) }} 
                        </span>
                    </div>

                    <p class="txt-primary"> Por haber participado en el webinar virtual de: </p>

                    {{------- Nombre del curso -----}}
                    <p class="text-uppercase text-bold txt-primary">
                        {{ mb_strtolower($certification->course->description, 'UTF-8') }}   
                    </p>
                    
                    <p class="text-semibold txt-primary">
                        Duración del curso:
                        {{----- Duración  ------}}
                        <span class="text-light">
                            {{ $certification->course->hours }} horas
                        </span>
                    </p>
                    
                    {{----- Fecha ------}}
                    <p class="txt-primary">
                        Lima, {{ $certification->event->date_es }}
                    </p>
                </div>

            </div>

            <div class="content-child right image-marks f-right">

                <div class="upper-mark-container">
                    <div class="mark-img-cont">
                        <img src="{{public_path('assets/certificates/images/upper-mark.png')}}" alt="">
                    </div>
                </div>

                <div class="iso-mark-container">
                    <img src="{{public_path('assets/certificates/images/iso-mark.png')}}" alt="">
                </div>

            </div>

        </div>

        <div class="footer">
            <div class="footer-content">

                <div class="foot-left f-left">
                    <div class="info-qr-container">
                        <div class="qr-img-cont f-left">
                            <img src="{{public_path('assets/certificates/images/qr_image_cert.jpeg')}}" alt="">
                        </div>
                        <div class="text-qr-info f-right">
                            <div class="raw-text-info">
                                Verifique la autenticidad de este certificado,
                                escaneando el código QR o ingrese a: 
                                <a href=""> www.hamaperu.net/certificados </a>
                            </div>
                        </div>
                    </div>

                    <div class="dates-container f-clear">

                        @php
                            setlocale( LC_ALL,"es_ES@euro","es_ES","esp" );
                        @endphp

                        <div class="text-bold">
                            Fecha de emisión: 
                            <span class="text-light">
                                {{ strftime("%d de %B del %Y", strtotime(getCarbonInstance($certification->event->date)->isoFormat('DD-MM-YYYY'))) }}
                            </span>
                        </div>

                        <div class="text-bold">
                            Fecha de caducidad:
                            <span class="text-light">
                                {{ strftime("%d de %B del %Y", strtotime(getCarbonInstance($certification->event->date)->addYear(1)->isoFormat('DD-MM-YYYY'))) }}
                            </span>
                        </div>

                    </div>
                </div>


                <div class="foot-right f-right text-center">

                    {{-- Imagen de la Firma --}}
                    <div class="signature-container">
                        <img src="data:image/jpg;base64, {{ base64_encode($new_image) }}" alt="">
                    </div>

                    {{-- Nombre de Instructor --}}
                    <p class="text-bold text-secondary">  {{ strtoupper($certification->event->user->full_name_complete_reverse) }}</p>
                    <p class="text-secondary"> Instructor(a) HAMA Perú </p>
                </div>

            </div>
        </div>

    </body>

</html>