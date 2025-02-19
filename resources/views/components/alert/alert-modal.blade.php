<div class="modal fade" id="alertModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h3 class="modal-title text-light">{{ $title }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>


            <div class="modal-body">

                <span>
                    {{ $text }}
                </span>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary bg-white text-primary" type="button"
                    data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
