<div id="authorization_modal" class="iziModal"
data-izimodal-width="600px"
data-izimodal-icontext="<i class='fa-solid fa-file-contract'></i>"
data-iziModal-title="Autorización de Términos y Condiciones">
    
    <section>

        <div >
            <b>
                Autorizo el almacenamiento de mi "Firma Digital" en los documentos: 
            </b>
            <br><br>

            <b>I)</b> Anexo 4<br>
            <b>II)</b> Carta de Compromiso<br>
            <b>III)</b> Asistencias<br><br>

            Estos documentos con mi firma se mantendrán en poder de HAMA para sustentar las capacitaciones y absolver
            los requerimientos de los clientes o de las autoridades administrativas.<br><br>

            Asimismo, HAMA y los terceros que tengan acceso deberán adoptar las medidas de seguridad, técnicas,
            legales y organizativas necesarias para resguardar mis datos personales.
        </div>

        <footer class="mt-4">
            <a href="{{ route('aula.signatures.create') }}" class="btn submit">
                Aceptar Términos y Condiciones
                {{-- <i class="fa-solid fa-spinner fa-spin loadSpinner"></i> --}}
            </a>
        </footer>

    </section>

</div>