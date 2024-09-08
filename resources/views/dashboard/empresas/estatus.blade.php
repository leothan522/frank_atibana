<div class="row">
    <div class="col-12">
        @if($this->getEstatusTienda($rowquid))
            <div class="alert alert-success">
                <h5><i class="icon fas fa-check"></i> ¡Abierto!</h5>
                Hora actual: <strong>{{ getFecha(null, 'h:i a') }}</strong>. Estatus: <strong> OPEN </strong>
            </div>
        @else
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> ¡Cerrado!</h5>
                Hora actual: <strong>{{ getFecha(null, 'h:i a') }}</strong>. Estatus: <strong> CLOSED </strong>
            </div>
        @endif
    </div>
</div>
