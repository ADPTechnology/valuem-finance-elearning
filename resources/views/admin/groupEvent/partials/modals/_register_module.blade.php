<div class="modal fade" id="registerModuleModal" tabindex="-1" aria-labelledby="registerModuleModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="registerModuleModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-square-plus"></i> &nbsp;
                        Añadir Módulo
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.specCourses.modules.store', $groupEvent->specCourse) }}" id="registerModuleForm"
                method="POST">
                @csrf

                @include('admin.specCourses.partials.components._form_module')

            </form>
        </div>
    </div>
</div>
