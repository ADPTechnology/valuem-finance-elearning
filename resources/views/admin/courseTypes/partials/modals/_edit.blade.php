<div class="modal fade" id="EditCoursetypeModal" tabindex="-1" aria-labelledby="EditCoursetypeModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="EditCoursetypeModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-pen-to-square"></i> &nbsp;
                        Editar tipo de curso
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="editCoursetypeForm" method="POST" enctype="multipart/form-data">
                @csrf

                @include('admin.courseTypes.partials.components._form')

            </form>
        </div>
    </div>
</div>
