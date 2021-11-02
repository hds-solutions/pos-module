<div class="card h-auto h-sm-150px mh-sm-150px h-md-auto h-xl-150px mh-xl-150px bg-primary text-white" id="last-product">
    <div class="row no-gutters h-100">
        <div style="background-image: url({{ asset('backend-module/assets/images/default.jpg') }});" id="preview"
            class="col-12 col-sm-4 col-md-12 col-xl-4 h-100px h-xl-auto bg-contain bg-xl-cover bg-center bg-no-repeat">
        </div>
        <div class="col-12 col-sm-8 col-md-12 col-xl-8">
            <div class="card-body h-100 d-flex flex-column p-3">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title font-weight-bold mb-1" id="name">Product name</h5>
                    </div>
                </div>
                <div class="row flex-grow-1">
                    <div class="col d-flex flex-column flex-sm-row flex-md-column flex-xl-row justify-content-between">
                        <p class="text-truncate mb-xl-0 mr-xl-2" id="description">Product description</p>
                        <span class="align-self-end text-nowrap btn btn-warning disabled btn-no-action font-weight-bold">{{ pos_settings()->currency()->code }} <span id="price"></span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
