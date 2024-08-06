<div class="modal fade" id="RegisterCoursetypeModal" tabindex="-1" aria-labelledby="RegisterCoursetypeModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="RegisterCoursetypeModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-square-plus"></i> &nbsp;
                        Registrar tipo de curso
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.coursetypes.store') }}" id="registerCoursetypeForm" method="POST" enctype="multipart/form-data">
                @csrf

                @include('admin.courseTypes.partials.components._form')

            </form>
        </div>
    </div>
</div>
