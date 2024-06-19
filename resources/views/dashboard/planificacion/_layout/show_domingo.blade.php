<div class="tab-pane fade @if($dia == 'domingo') active show @endif " id="custom-tabs-three-domingo" role="tabpanel"
     aria-labelledby="custom-tabs-three-domingo-tab" xmlns:wire="http://www.w3.org/1999/xhtml">

    <div class="row table-responsive p-0">

        <table class="table">
            <thead>
            <tr class="text-navy">
                <th style="width: 10%">#</th>
                <th>Receta</th>
                <th style="width: 50%">Descripción</th>
                <th><span class="float-right">Cantidad</span></th>
            </tr>
            </thead>
            <tbody>

            @for($i = 0; $i < $contador_domingo; $i++)

                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="text-uppercase">{{ $codigoRecetaDomingo[$i] }}</td>
                    <td class="text-uppercase">{{ $descripcionRecetaDomingo[$i] }}</td>
                    <td class="text-right">
                        {{ formatoMillares($cantidadDomingo[$i], 3) }}
                    </td>
                </tr>

            @endfor

            </tbody>
        </table>

    </div>

</div>
