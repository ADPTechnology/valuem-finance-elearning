<div class="modal fade" id="registerGroupParticipantModal" tabindex="-1" aria-labelledby="registerGroupParticipantModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="registerModuleModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-square-plus"></i> &nbsp;
                        Crear Grupo de participantes
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.specCourses.groupEvents.groupParticipants.store', $groupEvent) }}"
                id="registerGroupParticipantForm" method="POST">
                @csrf

                @include('admin.groupEvent.partials.components._form_group_participant')

            </form>
        </div>
    </div>
</div>
