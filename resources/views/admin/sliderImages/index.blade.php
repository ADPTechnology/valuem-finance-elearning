@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>IMÁGENES DEL SLIDER DEL LOGIN</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">
                <div class="principal-splitted-container mt-1 mb-2">

                    <div class="principal-inner-container total-width">

                        <div class="inner-title-container">
                            <div id="" class="btn-dropdown-container">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-images not-rotate"></i> &nbsp;
                                    Lista de imágenes
                                </h5>

                                <div class="btn-row-container">
                                    <div>
                                        <span class="text-dropdown-cont">
                                            Ocultar
                                        </span>
                                        <i class="fa-solid fa-chevron-down ms-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="sliderImages-list-container" class="related-dropdown-container">

                            @include('admin.sliderImages.partials.boxes._images_list')

                        </div>

                    </div>

                </div>
            </div>


        </div>

    </div>
@endsection


@section('modals')

    @include('admin.sliderImages.partials.modals._sliderImage_store')
    @include('admin.sliderImages.partials.modals._sliderImage_edit')

@endsection
