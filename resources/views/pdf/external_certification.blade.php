<!DOCTYPE html>
<html>

<head>

    <meta http-equiv="X-UA-Compatible" content="IE=8">
    <title>Certificado HAMA</title>

    <style>

        @font-face {
            font-family: 'Bai Jamjuree';
            src: url({{ storage_path("fonts/BaiJamjuree-Regular.ttf") }});
        }

        @font-face {
            font-family: 'Charm';
            src: url({{ storage_path("fonts/Charm-Regular.ttf") }});
        }

        @page {
            margin-left: 0;
            margin-right: 0;
            margin-top: -0.5cm;
        }

        .p1 {

            width: auto;
            height: 50px;
            left: 10px;
            //top: 0px;
            background: #DC3545;
        }

        .p2 {
            width: 580px;
            height: 50px;
            left: 440px;
            position: relative;
            top: -65px;
            border-left: 65px solid transparent;
            border-top: 150px solid #DC3545;
        }

        .p3 {
            width: 240px;
            height: 80px;
            left: 219px;
            position: relative;
            top: -283px;
            background: #EEEEEE;
            -webkit-transform: skew(20deg);
            -moz-transform: skew(20deg);
            -ms-transform: skew(20deg);
            -o-transform: skew(20deg);
            transform: skew(20deg);
        }

        .p4 {
            width: 160px;
            height: 60px;
            left: 296px;
            position: relative;
            top: -378px;
            background: #131213;
            -webkit-transform: skew(20deg);
            -moz-transform: skew(20deg);
            -ms-transform: skew(20deg);
            -o-transform: skew(20deg);
            transform: skew(20deg);
        }

        div.img {
            width: 80px;
            height: 0px;
            left: 120px;
            position: relative;
            top: -380px;
        }

        div.fec {
            width: 200px;
            height: 0px;
            left: 14px;
            position: relative;
            top: -300px;
        }

        div.pag {
            width: 200px;
            height: 0px;
            left: 1px;
            position: relative;
            top: -320px;
        }

        div.imgfooter {

            height: 70px;

            /*  poner footer abajo */
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            top: 722px;
        }

        div.imgjefa {
            width: 80px;
            height: 0px;
            left: 170px;
            position: relative;
            top: 30px;
        }

        div.imging {
            width: 80px;
            height: 0px;
            left: 800px;
            position: relative;
            top: 30px;
        }

        div.imgqr {
            width: 80px;
            height: 0px;
            left: 970px;
            position: fixed;
            top: 640px;
        }


        div.p5 {
            width: 80px;
            height: 0px;
            left: 750px;
            position: relative;
            color: #FFF;
            font-size: 70px;
            font-family: 'Bai Jamjuree', sans-serif;
            top: -410px;


        }

        div.p6 {
            width: 500px;
            height: 0px;
            left: 540px;
            position: relative;
            color: #2C2B2B;
            font-size: 33px;
            font-family: 'Bai Jamjuree', sans-serif;
            position: relative;
            top: -310px;

        }

        div.p8 {
            width: 160px;
            height: 0px;
            left: 470px;
            color: #2C2B2B;
            font-size: 32px;
            position: relative;
            top: -240px;
            font-family: "Homer Simpson UI";

        }

        div.p9 {
            width: 650px;
            height: 0px;
            left: 220px;
            color: #2C2B2B;
            font-size: 28px;
            position: relative;
            top: -180px;
            font-family: 'Charm', cursive;

        }

        .linea {
            border-top: 1px solid black;
            position: relative;
            left: 190px;
            top: -130px;
            max-width: 680px;
            //padding: 0;
        }

        div.p10 {
            width: 550px;
            height: 0px;
            left: 280px;
            color: #2C2B2B;
            font-size: 27px;
            position: relative;
            top: -140px;
            font-family: "Homer Simpson UI";
            //line-height: 20px;

        }

        div.p11 {
            width: 650px;
            height: 0px;
            left: 240px;
            color: #2C2B2B;
            font-size: 32px;
            position: relative;
            font-family: 'Bai Jamjuree', sans-serif;
            top: -65px;
            line-height: 20px;

            //line-height: 1.2;


        }

        .linea2 {
            border-top: 1px solid black;
            position: relative;
            left: 120px;
            top: 80px;
            max-width: 200px;

        }

        .linea3 {
            border-top: 1px solid black;
            position: relative;
            left: 740px;
            top: 63px;
            max-width: 200px;
            padding: 0;
        }

        .jefa {
            width: 550px;
            height: 0px;
            left: -60px;
            color: #2C2B2B;
            font-size: 15px;
            position: relative;
            font-weight: bold;
            top: 60px;
            line-height: 1.2;
            font-family: "Homer Simpson UI";

        }

        .ing {
            width: 550px;
            height: 0px;
            left: 560px;
            color: #2C2B2B;
            font-size: 15px;
            font-weight: bold;
            position: relative;
            top: 60px;
            line-height: 1.2;
            font-family: "Homer Simpson UI";

        }

        .desing {
            width: 550px;
            height: 0px;
            left: 560px;
            color: #2C2B2B;
            font-size: 20px;
            position: relative;
            top: 80px;
            line-height: 1.2;
            font-family: "Homer Simpson UI";

        }

        .desjefa {
            width: 550px;
            height: 0px;
            left: -60px;
            color: #2C2B2B;
            font-size: 20px;
            position: relative;
            top: 80px;
            line-height: 1.2;
            font-family: "Homer Simpson UI";

        }
    </style>
</head>

<body>
    <center>
        <div>
            <p class="p1"></p>
            <p class="p2"></p>
            <p class="p3"></p>
            <p class="p4"></p>
            <div class="img"><img src="{{ public_path('assets/certificates/images/hama-logo.png') }}" width="260" height="50" class="img" alt="">
            </div>
            <div class="imgjefa"><img src="{{ $certification->event->responsable->file->file_url ?? '' }}" width="260"
                    height="100" class="img" alt=""></div>
            <div class="imging"><img src="{{ $certification->event->instructor->file->file_url ?? '' }}" width="260"
                    height="100" class="img" alt=""></div>
            <div class="imgqr"><img src="{{ public_path('assets/certificates/images/qr.png') }}" width="80" height="80" class="img" alt="">
            </div>
            <div class="fec">F. Emisión &nbsp;: {{ getCarbonInstance($certification->event->date)->isoFormat('DD-MM-YYYY') }}</div>
            <div class="pag">www.hamaperu.com</div>
            <div class="p5">CERTIFICADO</div>
            <div class="p6">DE PARTICIPACIÓN</div>
            <div class="p8">Otorgado a :</div>
            <div class="p9">{{ mb_strtoupper($certification->user->full_name_complete_reverse, 'UTF-8') }}</div>
            <p class="linea"></p>
            <div class="p10">Por haber completado satisfactoriamente el curso teórico de :</div>
            <div class="p11">{{ mb_strtoupper($certification->event->webinar->title, 'UTF-8') }}</div>
        </div>

        <div>
            <p class="linea2"></p>
            <p class="linea3"></p>
            <div class="jefa">{{ mb_strtoupper($certification->event->responsable->full_name_complete ?? '', 'UTF-8') }}</div>
            <div class="ing">{{ mb_strtoupper($certification->event->instructor->full_name_complete_reverse, 'UTF-8') }}</div>
            <div class="desjefa">Coordinadora de Capacitaciones</div>
            <div class="desing">Instructor HAMA Perú</div>
        </div>

        <div>

            <div class="imgfooter"><img src="{{ public_path('assets/certificates/images/cert_ext_footer.png') }}" width="1200" height="70"
                    class="img" alt=""></div>
        </div>

    </center>

</body>

</html>
