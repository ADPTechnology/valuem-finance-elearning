<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Carta de Compromiso</title>

    <style>
       
        * {
            padding: 0;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1em;
            box-sizing: border-box;
        }

        body { 
            margin: 1.2cm 3cm; 
            position: relative;
        }

        ul {
            list-style: armenian;
        }

        .text-justify {text-align: justify;}
        .text-center {text-align: center;}
        .text-left {text-align: left;}
        .text-right {text-align: right;}
        .text-bold {font-weight: bold;}
        .capitalize {text-transform: capitalize;}
        .italic {font-style: italic;}
        .m-auto {margin: auto;}
        .m-0 {margin: 0;}
        .mt-1 {margin-top: 0.25em;}
        .mt-2 {margin-top: 0.5em;}
        .mt-3 {margin-top: 0.75em;}
        .mt-4 {margin-top: 1em;}
        .mt-5 {margin-top: 1.5em;}
        .ms-auto {margin-left: auto;}
        .ms-1 {margin-left: 0.25em;}
        .ms-2 {margin-left: 0.5em;}
        .ms-3 {margin-left: 0.75em;}
        .ms-4 {margin-left: 1em;}
        .ms-5 {margin-left: 1.5em;}
        .me-1 {margin-right: 0.25em;}
        .me-2 {margin-right: 0.5em;}
        .me-3 {margin-right: 0.75em;}
        .mb-1 {margin-bottom: 0.25em;}
        .mb-2 {margin-bottom: 0.5em;}
        .mb-3 {margin-bottom: 0.75em;}
        .mb-4 {margin-bottom: 1em;}
        .mb-5 {margin-bottom: 1.5em;}
        .f-left {float: left;}
        .f-right {float: right;}
        .f-clear {clear: both;}
        .p-0 {padding: 0;}
        .p-1 {padding: 0.15cm;}
        .p-2 {padding: 0.5em;}
        .pt-1 {padding-top: 0.15cm;}
        .pb-1 {padding-bottom: 0.15cm;}
        .ps-4 {padding-left: 1em;}
        .bg-dark {background-color: rgb(66, 66, 66);}
        .text-white {color: white;}
        .border {border: 1px solid rgb(66, 66, 66);}
        .border-top {border-top: 1px solid rgb(66, 66, 66);}
        .border-bottom {border-bottom: 1px solid rgb(66, 66, 66);}
        .d-block {display: block;}
        .d-inline-block {display: inline-block;}
        .pos-absolute {position: absolute;}
        .pos-relative {position: relative;}

        .not-page-break{page-break-inside: avoid;}

        .div-sello-parada {
            position: absolute;
            right: 0;
            transform: translateY(-.6cm)
        }

        .img-sello-parada {
            width: 90px;
            height: auto;
        }

        .signature-user {
            width: 100%;
            position: absolute;
            bottom: 0;
        }


        .user-firma {
            margin-top: 5px;
            font-size: 10px;

        }

        .user-firma-detalles {
            display: inline-block;
        }

        .signature-user-img {
            height: 70px;
            width: auto;
        }

    </style>
</head>

<body>

    @if (!in_array($sufix, ['C', 'S']))
    <div class="div-sello-parada">
        <img src="{{ public_path('assets/certificates/images/nexalogo.png') }}" class="img-sello-parada">
    </div>   
    @endif

    <div class="content">

        <div>
            <div>
                <div class="text-bold mt-4 text-center">
                    CARTA COMPROMISO - REGLAS DE ORO, REGLAS POR LA VIDA
                </div>
                <div class="mt-2 text-center">
                    {{ ucwords(strtolower($miningUnit->district ?? ' - ')) }}, {{ $certification->event->date_es}}
                </div>

                <div class="mt-4">
                    Señor/a
                </div>

                <div class="mt-3">
                    <div class="f-left">
                        {{ strtoupper($certification->user->full_name_complete_reverse) }}
                    </div>
                    <div class="f-right">
                        DNI: {{ $certification->user->dni }}
                    </div>
                </div>
            </div>

        </div>

        <div class="commitment-content mt-5 f-clear" style="font-size: 15px;">
            <div class="mb-3 mt-4">
                De nuestra consideración: 
            </div>

            @if (!in_array($sufix, ['C', 'S']))
            <div class="text-justify mb-3">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Como es de su conocimiento, la empresa Nexa Resources {{ config('parameters.miningUnit_company_name')[$sufix] ?? '-' }} cuenta con el respectivo
                Reglamento Interno de Trabajo, Reglamento de Seguridad y Salud en el Trabajo, así como políticas, lineamientos y procedimientos, vigentes
                en todas las Unidades Mineras y Administrativas, los mismos que el fueron entregados en su oportunidad.
                
            </div>
            <div class="text-justify mb-3">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Le recordamos que, además de tener que informarse sobre las normas establecidas en los documentos adjuntos, de conformidad 
                con el Reglamento Interno de Trabajo, artículo 24°, inciso 8, es obligación de todos los trabajadores de la Empresa cumplir
                y hacer cumplir las Políticas, Normas, Reglamentos y Procedimientos de Trabajo u Operación establecidos.
            </div>
            <div class="text-justify mb-3">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Al respecto, es necesario tomar especial atención en el cumplimiento de las siguientes pautas contenidas en las políticas,
                lineamientos y procedimientos antes mencionados:
            </div>

            @else

            <div class="text-justify mb-3">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Como es de su conocimiento, su Empresa suscribió un contrato con Nexa Resources PERÚ S.A.A, a fin de realizar diversos 
                servicio.
            </div>
            <div class="text-justify mb-3">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Asimismo, parte de nuestras obligaciones es difundir su Reglamento Interno de Trabajo, Reglamento de Seguridad y Salud
                en el Trabajo, así como las políticas, lineamientos y procedimientos vigentes que tiene citada la Empresa.
            </div>
            <div class="text-justify mb-3">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Por lo tanto, le reiteramos que cumplan con las citadas normas y, además que tenga especial atención en el cumplimiento
                de las siguientes pautas contenidas en las políticas, lineamientos y procedimientos antes mencionados:
            </div>
            @endif

            {{-------- LIST--------}}

            <div class="ms-5 mb-3">
                <ol class="ms-5">
                    <li class="text-justify">
                        <span class="text-bold">
                            Trabajo en altura:
                        </span>Los trabajos realizados en altura superior a un metro ochenta requieren el uso de
                        sistema de prevención de caídas y punto de anclaje aprobado para uso.
                    </li>
                    <li class="text-justify">
                        <span class="text-bold">
                            Bloqueo y aislamiento de energías:
                        </span>Las actividades de construcción, limpieza, mantenimiento, puesta en marcha, operación o emergencia en las que el 
                        trabajador tenga que exponer su cuerpo, o parte de él, a riesgos en donde la liberación de energías le pueda causar
                        daños se deben realizar solo en la condición de energía cero.
                        Por este motivo, todas las fuentes de energía se deben bloquear, de acuerdo con la matriz de bloqueo, usando candado,
                        tarjeta de bloqueo y prueba de energía CERO. El gerente general de la unidad o su sustituto deberá autorizar todas
                        las actividades para las cuales no sea posible el estado de energía cero, a través de un proceso formalizado.
                    </li>
                    <li class="text-justify">
                        <span class="text-bold">
                            Espacio confinado:
                        </span>Solo profesionales capacitados y autorizados deben realizar trabajos en espacios confinados. 
                        También son obligatorios la emisión del permiso de entrada y el acompañamiento del vigía durante toda la actividad.
                    </li>
                    <li class="text-justify">
                        <span class="text-bold">
                            Vehículos livianos y equipos móviles:
                        </span>La operación de vehículos livianos o de
                        equipos móviles exige el uso del cinturón de seguridad para el conductor y los
                        pasajeros. Durante la conducción u operación, está prohibido utilizar el celular. Se
                        deben respetar los límites de velocidad.
                    </li>
                    <li class="text-justify">
                        <span class="text-bold">
                            Alcohol y drogas:
                        </span>En las instalaciones o durante la jornada de trabajo, todos los
                        trabajadores deben ejercer sus actividades sin la influencia de estas sustancias.
                    </li>
                    <li class="text-justify">
                        <span class="text-bold">
                            Caída de rocas:
                        </span>La entrada en frentes de explotación y desarrollo solo se permitirá
                        después de que se realice la inspección formal que compruebe la ausencia de
                        fragmentos de roca.
                    </li>
                    <li class="text-justify">
                        <span class="text-bold">
                            Cargas suspendidas:
                        </span>Los equipos utilizados para elevación deben ser adecuados
                        para la actividad, y se deben inspeccionar antes de usarse con “check list (lista de
                        verificación)”, y todos los ítems deben estar conforme. Las cargas críticas se deben
                        liberar a través del PPT y plan de “rigging”. El área de operación debe estar aislada y
                        señalizada.
                    </li>
                    <li class="text-justify">
                        <span class="text-bold">
                            Protección de máquinas:
                        </span>Las protecciones se pueden retirar solo con las máquinas
                        y los equipos bloqueados y en estado de energía cero. Al final de las actividades, las
                        rotecciones se deben volver a colocar y fijar de manera adecuada.
                    </li>
                    <li class="text-justify">
                        <span class="text-bold">
                            Sustancias químicas peligrosas:
                        </span>Las sustancias químicas peligrosas se deben
                        manipular solo cuando el empleado esté utilizando equipos de protección personal, los
                        EPP, aprobados por SSMA. Todas las sustancias químicas peligrosas deben estar en la
                        lista maestra de productos autorizados. Los sistemas de seguridad y protección de
                        tanques, cilindros y tuberías de productos químicos peligrosos no se pueden alterar o
                        deshabilitar.
                    </li>
                    <li class="text-justify">
                        <span class="text-bold">
                            Comunicación de accidentes:
                        </span>Se debe comunicar todo accidente,
                        independientemente de su gravedad.
                    </li>
                    <li class="text-justify">
                        <span class="text-bold">
                            Autorización de trabajo:
                        </span>Para las actividades que involucren riesgos críticos de
                        seguridad, es obligatorio que todos los trabajadores involucrados tengan autorización
                        formal. Estas actividades son: bloqueo y aislamiento de energías; cargas suspendidas;
                        excavación a tajo abierto y subterránea; espacio confinado; herramientas manuales
                        para: motosierra e ignición a pólvora; trabajo en caliente, sustancias químicas
                        peligrosas; metal líquido; trabajo en altura; sistemas presurizados; vehículos livianos
                        y equipos muebles e instalaciones eléctricas.
                    </li>
                    <li class="text-justify">
                        <span class="text-bold">
                            Evaluación de riesgos:
                        </span>Antes de cualquier actividad se debe hacer una evaluación
                        previa y formal de los riesgos por medio de las siguientes herramientas:
                        <ul class="ms-4 mt-2">
                            <li>
                                IPERC para todas las actividades, con excepción de las administrativas, las de
                                supervisión; inspecciones de área y visitas a las unidades.
                            </li>
                            <li>
                                Procedimientos para actividades rutinarias.
                            </li>
                            <li>
                                ATS y PETAR para actividades no rutinarias y de alto riesgo.
                            </li>
                        </ul>
                    </li>
                </ol>
            </div>

            @if (!in_array($sufix, ['C', 'S']))
            <div class="text-justify mb-3">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Finalmente, debe indicarse que el cumplimiento de las obligaciones contenidas en las
                disposiciones antes indicadas se encuentra sujeto a la imposición de las sanciones previstas
                en nuestro Reglamento Interno de Trabajo en concordancia con el artículo 25 del TUO 728.
            </div>
            @endif

            <div class="text-justify mb-3">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Respecto al Manual de las Reglas de Oro adjunto a la presente carta, es importante
                precisar que el texto de la página 15 deberá ser reemplazado por el siguiente:
            </div>
            <div class="text-justify text-bold mb-3">
                “Gestión de Consecuencias”
            </div>
            <div class="text-justify mb-3"> 
                El incumplimiento de cualquier regla, norma o procedimiento de seguridad incluyendo las
                Reglas de Oro, será evaluado por un Comité y las consecuencias serán aplicadas
                inmediatamente.
            </div>
            <div class="text-justify mb-3"> 
                Es importante indicar que los incumplimientos arriba anotados constituyen faltas graves
                sujetas a sanción disciplinaria de acuerdo nuestras normas internas y la legislación laboral
                vigente.
            </div>
            <div class="text-justify mb-3"> 
                En caso el incumplimiento lo cometa algún trabajador de la contratista, esta deberá tomar
                las acciones correctivas del caso; así como también se le aplicarán a la empresa las multas
                establecidas contractualmente.
            </div>
            <div class="text-justify mb-3"> 
                Muy Atentamente,
            </div>
        </div>

        
        <div class="not-page-break mt-5">

            <div class="text-center m-auto mt-5">

                <div class="me-2 d-inline-block">
                    <span class="italic">
                        Carlos Garcia
                    </span><br>
                    <span style="font-size: 13px;">
                        Gerente General
                        @if (in_array($sufix, ['C']))
                        de Cerro Lindo
                        @else
                        del Complejo Pasco
                        @endif
                    </span>
                </div>

                @if (in_array($sufix, ['C', 'S']))
                <div class="ms-2 d-inline-block">
                    <hr class="mb-1">
                    <span>Residente Empresa Especializada</span>
                </div>
                @endif
           
            </div>

            <div class="signature-user">
                <div>
                    <div class="mb-1">
                        Apellidos y Nombres: {{ strtoupper($certification->user->full_name_complete_reverse) }}
                    </div>
                    <div class="mb-1">
                        DNI: {{ $certification->user->dni }}
                    </div>
                    <div class="mb-1">
                        Fecha: {{ getCarbonInstance($certification->event->date)->isoFormat('DD / MM / YYYY') }}
                    </div>
                </div>

                <div class="ms-auto mt-3 text-right">

                    <div class="d-inline-block mb-1 text-left">
                        <div class="mb-2">
                            Firma:
                        </div>
        
                        <div class="user-firma">
                            <div class="user-firma-detalles">
                                <span>Documento firmado el </span><br>
                                <span>{{ getCarbonInstance($certification->event->date)->isoFormat('DD-MM-YYYY')
                                    }}</span><br>
                                <span>por el usuario</span><br>
                                <span>con DNI: {{ $certification->user->dni }}</span>
                            </div>
                        </div>
                    </div>
                   
                    <div class="d-inline-block ms-3">
                        <img src="{{ $certification->user->file->file_url ?? '#'}}" class="signature-user-img"
                            style="height:70px; width: auto;" align="middle" alt="">
                    </div>
                </div>
            </div>
            
        </div>


    </div>
</body>

</html>