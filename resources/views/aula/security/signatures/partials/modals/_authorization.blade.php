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

            I) Anexo 4
            <br>
            <br>

            Estos documentos con mi firma se mantendrán en poder de HAMA para sustentarlas capacitaciones y absolver los requerimientos de los clientes o de las autoridades administrativas.
            <br><br>
            Asimismo, HAMA y los terceros que tengan acceso deberán adoptar las medidas de seguridad, técnicas, legales y organizativas necesarias para resguardar mis datos personales.
        </div>

        <footer class="mt-4">
            <a href="{{ route('aula.signatures.security.create', [$event, $miningUnit]) }}" class="btn submit">
                Aceptar Términos y Condiciones
            </a>
        </footer>

    </section>

</div>