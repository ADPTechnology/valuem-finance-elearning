<div class="modal fade" id="editGroupParticipantModal" tabindex="-1" aria-labelledby="editGroupParticipantModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editModuleModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-pen-to-square"></i> &nbsp;
                        Editar Grupo de participantes
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="editGroupParticipantForm"
                method="POST">
                @csrf

                @include('admin.groupEvent.partials.components._form_group_participant', ["edit" => true])

            </form>
        </div>
    </div>
</div>
