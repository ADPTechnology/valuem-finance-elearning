<div class="modal fade" id="viewInstructorInformationModal" tabindex="-1" aria-labelledby="viewInstructorInformationModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="viewDocsParticipantModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-building"></i>
                        &nbsp;
                        Mi información
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form action="" id="UpdateInformationForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="informationInstructor">

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary btn-save">
                            Guardar
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
