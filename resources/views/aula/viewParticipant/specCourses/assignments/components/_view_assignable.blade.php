<div class="modal fade" id="ViewAssignmentModal" tabindex="-1" aria-labelledby="ViewAssignmentModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="ViewAssignmentModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-cloud"></i>&nbsp;
                        Información de la asignación:
                        <span class="title-assignment"></span>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <input type="hidden" name='id'>

            <form action="" id="storageFileAssignmentForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="body-assignment">

                </div>
            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
