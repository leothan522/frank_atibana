<div class="row justify-content-center" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="col-sm-6 col-md-5">
        <form wire:submit="sendMessage">
            <div class="card card-navy">

                <div class="card-header">
                    <h3 class="card-title">
                        Firebase Cloud Messaging (FCM)
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" wire:click="limpiar" onclick="cancelar()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label for="name">Título</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-window-maximize"></i></span>
                            </div>
                            <input type="text" class="form-control" wire:model="title" placeholder="Titulo para la Notificación">
                            @error('title')
                                <span class="col-sm-12 text-sm text-bold text-danger">
                                    <i class="icon fas fa-exclamation-triangle"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Contenido</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope-open-text"></i></span>
                            </div>
                            <input type="text" class="form-control" wire:model="body" placeholder="Mensaje para la Notificación">
                            @error('body')
                                <span class="col-sm-12 text-sm text-bold text-danger">
                                    <i class="icon fas fa-exclamation-triangle"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Dispositivos</label>
                        <div class="input-group mb-3" wire:ignore>
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                            </div>
                            <select id="dispositivos_users">
                                <option value="todos">Enviar a Todos</option>
                                @if(!$listarUsers->isEmpty())
                                    @foreach($listarUsers as $user)
                                        <option value="{{ $user->user->rowquid }}">{{ ucfirst($user->user->name) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @error('dispositivos')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <div class="custom-control custom-checkbox ml-3">
                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" wire:click="btnWithData({{ $withData }})">
                            <label for="customCheckbox1" class="custom-control-label">With Data</label>
                            @if($withData)
                                <button type="button" class="btn btn-xs btn-outline-info mt-1 mr-3 float-right" wire:click="setItems('add')">
                                    <i class="fas fa-plus"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($withData)
                        <div class="form-group">
                            @for($i = 0; $i < $items; $i++)
                                <div class="input-group mb-3">
                                    <div class="row col-12">

                                        <div class="col-4">
                                            <input type="text" class="form-control @error('keys.'.$i) is-invalid @enderror" placeholder="keys.{{ $i }}" wire:model="keys.{{ $i }}">
                                        </div>
                                        <div class="col-7">
                                            <input type="text" class="form-control @error('values.'.$i) is-invalid @enderror" placeholder="values.{{ $i }}" wire:model="values.{{ $i }}">
                                        </div>
                                        <div class="col-1">
                                            @if($items > 1)
                                                <button type="button" class="btn btn-xs btn-outline-danger m-2 float-right" wire:click="setItems({{ $i }})">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            @else
                                                &nbsp;
                                            @endif
                                        </div>

                                        @error('keys.'.$i)
                                            <span class="col-sm-12 text-sm text-bold text-danger">
                                                <i class="icon fas fa-exclamation-triangle"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                            @endfor
                        </div>
                    @endif

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-block btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar
                    </button>
                </div>

                {!! verSpinner() !!}

            </div>
        </form>
    </div>
</div>
