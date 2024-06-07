{{-- MODAL --}}
<div wire:ignore.self class="modal fade" id="modal-compartir-qr" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Campartir QR</h4>
                <button type="button" {{--wire:click="limpiar()"--}} class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">

                <div class="visible-print">
                    @if($compartirQr)
                        {!! QrCode::size(200)->generate($compartirQr); !!}
                    @endif
                </div>

            </div>

            {!! verSpinner() !!}

            <div class="modal-footer justify-content-between">
                <button type="button" wire:click="compartirQr('true')" class="btn btn-danger btn-sm" data-dismiss="modal">Dejar de compartir</button>
                <button type="button" {{--wire:click="limpiar()"--}} class="btn btn-default btn-sm" data-dismiss="modal">{{ __('Close') }}</button>
            </div>

        </div>
    </div>
</div>
