<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {{-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> --}}
    <title>Certificado HAMA</title>

    <style media="screen">

.f-left {float: left;}
.f-right {float: right;}
.f-clear {clear: both;}

.content{
    font-family: helvetica;
}
.section-1{
    position: absolute;
    left: 0%;
    width: 22%;
    margin-left: -20px;
}
.section-2{
    position: absolute;
    left: 26%;
    width: 70%;
    font-family: helvetica;
    margin-left: 28px;
}

.line-vertical {
    position: absolute;
    left: 25%;
    border-left: 4px solid red;
    height: 875px;
    width: 0px;
    top: 29px;
}

.signature{
    height: 120px;
    width: auto;
}

.logo{
    height: 22px;
    width: auto;
}

.emision{
    margin-top: 327px;
}
.text-center{
    text-align: center;
}

.text-left{
    text-align: left;
}
.date{
    margin-left: 17px;
    text-align: left;
}

.font-size-hama-detail{
    font-size: 14px;
}
.font-size-hama-title{
    margin-top: 70px;
    font-size: 28px;
    font-weight: bold;
}
.font-size-date{
    font-size: 11px;
}

.cuerpo-subtitle{
    font-size: 18px;
    margin-top: 70px;
}

.cuerpo-object{
    font-size: 18px;
    margin-top: 70px;
    font-weight: bold;
}
.cuerpo-object-subtitle{
    font-size: 15px;
    margin-top: 20px;
    font-weight: bold;
}

.user-cip{
    font-size: 15px;
    margin-top: 30px;
    font-weight: bold;
}

.cuerpo{
    margin-top: 50px;
}

</style>
</head>
<body>
    <div class="content">

        <div class="section-1">
            <div class="emision">
                <div class="text-center font-size-hama-detail">
                    <div class="">
                        <img src="{{ public_path('assets/common/images/logo_rediseno.svg') }}" class="logo" alt="" align=”middle”>
                    </div>
                    <div class="" style="margin-top:30px;">
                        <span>www.hamaperu.com</span>
                    </div>
                    <div class="" style="margin-top:25px;">
                        <span>Lima, Perú</span>
                    </div>
                </div>

                <div class="date font-size-date" style="margin-top:25px;">
                    <div class="">
                        <span>F. Emisión &nbsp;&nbsp;&nbsp;&nbsp;: {{ getCarbonInstance($certification->updated_at)->isoFormat('DD-MM-YYYY') }} </span>
                    </div>
                    <div class="">
                        <span>F. Caducidad : {{ getCarbonInstance($certification->updated_at)->addYear(1)->isoFormat('DD-MM-YYYY') }} </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="line-vertical"></div>

        <div class="section-2">

            <div class="cuerpo text-center">
                <div class="">
                    <span>Pensar en tu propia seguridad es pensar en el bien mayor...</span>
                </div>
                <div class="" style="margin-top: 20px;">
                    <span>¡TU FAMILIA!</span>
                </div>

                <div class="font-size-hama-title">
                    <span>HAMA Perú</span>
                </div>
                <div class="cuerpo-subtitle">
                    <span>Otorga la presente constancia a:</span>
                </div>

                <div class="cuerpo-object">
                        <span>{{ strtoupper($certification->user->full_name_complete_reverse) }}</span>
                </div>
                <div class="cuerpo-subtitle">
                    <span>Por haber participado y aprobado</span><br>
                    <span>satisfactoriamente el curso de:</span>
                </div>

                <div class="cuerpo-object" style="font-size: 22px;">
                    <span>{{ strtoupper($certification->course->description) }}</span>
                </div>
                @if ($certification->course->subtitle)

                    <div class="cuerpo-object" style="font-size: 15px; margin-top: -5px;">
                        <br><span> {{ strtoupper($course->subtitle) }} </span>
                    </div>

                @endif

                {{-- <div class="cuerpo-subtitle" style="margin-top: 10px;">
                    <br>
                    <span>Válido para la Unidad Minera
                        @forelse ($certification->miningUnits as $miningUnit)
                            @if (!$loop->first) - @endif
                            {{ ucwords(mb_strtolower($miningUnit->description, 'UTF-8')) }}
                        @empty
                        -
                        @endforelse
                    </span>
                </div>
                <div class="cuerpo-object">
                    <span>
                        @if (!$certification->event->ownerCompany)
                        NEXA RESOURCES
                        @else
                        {{ strtoupper($certification->event->ownerCompany->name) }}
                        @endif
                    </span>
                </div> --}}

                <div class="cuerpo-subtitle text-left" style="margin-left: 160px;">
                        <div>
                            <div class="f-left" style="width: 2.5cm;">
                                Duración
                            </div>
                            <div class="f-left">
                                : {{ $course->hours }} Horas
                            </div>
                        </div>

                        <div class="f-clear" >
                            <div class="f-left" style="width: 2.5cm; margin-top: 10px;">
                                Nota
                            </div>
                            <div class="f-left" style="margin-top: 10px;">
                                : {{ $certification->score }}
                            </div>
                        </div>
                </div>

                <div class="cuerpo-subtitle f-clear">
                    <div style="margin-top: 20px;">Pasco, {{ $certification->date_es }}</div>
                </div>

                {{-- <div class="" style="margin-top: 30px;">
                    <img src="{{ $certification->event->user->file->file_url ?? '#' }}" class="signature" alt="">
                </div>
                <div class="" style="font-size: 14px;">
                    <span>Instructor SSO - HAMA Perú</span>
                </div>
                <div class="cuerpo-object" style="margin-top: 0px;">
                    <span>{{ strtoupper($certification->event->user->full_name_complete_reverse) }}</span>
                </div> --}}

                {{-- @if ($certification->event->user->cip)
                    <div class="user-cip" style="margin-top: 0px;">
                        <span>{{ 'CIP '. $certification->event->user->cip }}</span>
                    </div>
                @endif --}}

            </div>
        </div>
    </div>
</body>
</html>

