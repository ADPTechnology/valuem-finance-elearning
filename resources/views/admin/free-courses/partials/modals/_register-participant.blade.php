<div class="modal fade" id="fc_registerParticipantsModal" tabindex="-1" aria-labelledby="fc_registerParticipantsModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="wb_registerParticipantsModalTitle">
                    <div class="title-header-show mt-0">
                        <i class="fa-solid fa-plus fa-xs"></i> &nbsp;
                        <span id="txt-context-element" class="text-bold">
                            Registrar participantes
                        </span>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"> &times; </span>
                </button>
            </div>

            <form action="{{ route('admin.freeCourses.users.store', $course) }}" id="fc-register-participants-form"
                method="POST">
                @csrf

                @include('admin.free-courses.partials.components._form_store')

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
                    <div id="btn-store-participant-container">
                        <button class="btn btn-primary btn-save not-user-allowed" disabled>
                            Registrar participantes
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
