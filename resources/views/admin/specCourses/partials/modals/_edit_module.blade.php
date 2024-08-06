<div class="modal fade" id="editModuleModal" tabindex="-1" aria-labelledby="editModuleModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editModuleModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-pen-to-square"></i> &nbsp;
                        Editar Módulo
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="editModuleForm"
                method="POST">
                @csrf

                @include('admin.specCourses.partials.components._form_module', ["edit" => true])

            </form>
        </div>
    </div>
</div>
