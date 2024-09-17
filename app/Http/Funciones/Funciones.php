<?php
//Funciones Personalizadas para el Proyecto
use App\Models\Parametro;
use App\Models\Stock;
use App\Models\Unidad;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\Tributario;
use App\Models\Precio;
use App\Models\Articulo;
use App\Models\Empresa;

function generarStringAleatorio($largo = 10, $espacio = false): string
{
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $caracteres = $espacio ? $caracteres . ' ' : $caracteres;
    $string = '';
    for ($i = 0; $i < $largo; $i++) {
        $string .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $string;
}

function verRole($role, $roles_id): string
{
    $response = 'Root';

    if (is_null($roles_id)){
        $roles = Parametro::where('tabla_id', '-2')->where('valor', $role)->first();
    }else{
        $roles = Parametro::where('id', $roles_id)->first();
    }

    if ($roles){
        $response = ucwords($roles->nombre);
    }
    return $response;
}

function verImagen($path, $user = false, $web = null): string
{
    if (!is_null($path)){
        if ($user){
            if (file_exists(public_path('storage/'.$path))){
                return asset('storage/'.$path);
            }else{
                return asset('img/user.png');
            }
        }else{
            if (file_exists(public_path($path))){
                return asset($path);
            }else{
                if (is_null($web)){
                    return asset('img/img_placeholder.png');
                }else{
                    return asset('img/web_img_placeholder.jpg');
                }

            }
        }
    }else{
        if ($user){
            return asset('img/user.png');
        }
        if (is_null($web)){
            return asset('img/img_placeholder.png');
        }else{
            return asset('img/web_img_placeholder.jpg');
        }
    }
}

//Leer JSON
function leerJson($json, $key)
{
    if ($json == null) {
        return null;
    } else {
        $json = $json;
        $json = json_decode($json, true);
        if (array_key_exists($key, $json)) {
            return $json[$key];
        } else {
            return null;
        }
    }
}

function numRowsPaginate(): int
{
    $num = 15;
    $parametro = Parametro::where("nombre", "numRowsPaginate")->first();
    if ($parametro) {
        if (is_int($parametro->valor)) {
            $num = intval($parametro->valor);
        }
    }
    return $num;
}

function getFecha($fecha, $format = null): string
{
    if (is_null($fecha)){
        if (is_null($format)){
            $date = Carbon::now(env('APP_TIMEZONE', "America/Caracas"))->toDateString();
        }else{
            $date = Carbon::now(env('APP_TIMEZONE', "America/Caracas"))->format($format);
        }
    }else{
        if (is_null($format)){
            $date = Carbon::parse($fecha)->format("d/m/Y");
        }else{
            $date = Carbon::parse($fecha)->format($format);
        }
    }
    return $date;
}

function haceCuanto($fecha): string
{
    return Carbon::parse($fecha)->diffForHumans();
}

// Obtener la fecha en español
function fechaEnLetras($fecha, $isoFormat = null): string
{
    // dddd => Nombre del DIA ejemplo: lunes
    // MMMM => nombre del mes ejemplo: febrero
    $format = "dddd D [de] MMMM [de] YYYY"; // fecha completa
    if (!is_null($isoFormat)){
        $format = $isoFormat;
    }
    return Carbon::parse($fecha)->isoFormat($format);
}

function listarDias(): array
{
    return ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];
}

function ListarMeses(): array
{
    return ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
}

function formatoMillares($cantidad, $decimal = 2): string
{
    if (!is_numeric($cantidad)){
        $cantidad = 0;
    }
    return number_format($cantidad, $decimal, ',', '.');
}

function qrCodeGenerate($string = 'Hello World!', $size = 100, $filename = 'qrcode', $path = false, $margin = 0): string
{
    $renderer = new \BaconQrCode\Renderer\ImageRenderer(
        new \BaconQrCode\Renderer\RendererStyle\RendererStyle($size,$margin),
        new \BaconQrCode\Renderer\Image\SvgImageBackEnd(),
    );
    $writer = new \BaconQrCode\Writer($renderer);
    $writer->writeFile($string, "storage/{$filename}.svg");

    if ($path){
        return asset("storage/{$filename}.svg");
    }

    if (file_exists(public_path("storage/{$filename}.svg"))){
        return '<img src="'.asset("storage/{$filename}.svg").'" alt="QRCode">';
    }
    return "QRCode";
}

function verSpinner(): string
{
    $spinner = '
        <div class="overlay-wrapper" wire:loading>
            <div class="overlay">
                <div class="spinner-border text-navy" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    ';

    return $spinner;
}

function numSizeCodigo(): int
{
    $num = 6;
    $parametro = Parametro::where("nombre", "size_codigo")->first();
    if ($parametro) {
        if (is_int($parametro->tabla_id)) {
            $num = intval($parametro->tabla_id);
        }
    }
    return $num;
}

function cerosIzquierda($cantidad, $cantCeros = 2): int|string
{
    if ($cantidad == 0) {
        return 0;
    }
    return str_pad($cantidad, $cantCeros, "0", STR_PAD_LEFT);
}

function nextCodigo($parametros_nombre, $parametros_tabla_id, $nombre_formato = null): string
{

    $next = 1;
    $codigo = null;

    //buscamos algun formato para el codigo
    if (!is_null($nombre_formato)){
        $parametro = Parametro::where("nombre", $nombre_formato)->where('tabla_id', $parametros_tabla_id)->first();
        if ($parametro) {
            $codigo = $parametro->valor;
        }else{
            $codigo = "N".$parametros_tabla_id.'-';
        }
    }

    //buscamos el proximo numero
    $parametro = Parametro::where("nombre", $parametros_nombre)->where('tabla_id', $parametros_tabla_id)->first();
    if ($parametro){
        $next = $parametro->valor;
        $parametro->valor = $next + 1;
        $parametro->save();
    }else{
        $parametro = new Parametro();
        $parametro->nombre = $parametros_nombre;
        $parametro->tabla_id = $parametros_tabla_id;
        $parametro->valor = 2;
        $parametro->save();
    }

    if (!is_int($next)){ $next = 1; }

    $size = cerosIzquierda($next, numSizeCodigo());

    return $codigo . $size;

}

function crearMiniaturas($imagen_data, $path_data, $opcion = 'mini'): array
{
    //ejemplo de path
    //$miniatura = 'storage/productos/size_'.$nombreImagen;

    //definir tamaños
    switch ($opcion){
        case 'mini':
            $sizes = [
                'mini' => [
                    'width' => 320,
                    'height' => 320,
                    'path' => str_replace('size_', 'mini_', $path_data)
                ]
            ];
            break;
        case 'temporal':
            $sizes = [
                'temporal' => [
                    'width' => 320,
                    'height' => 320,
                    'path' => strtolower(str_replace(' ', '', $path_data))
                ]
            ];
            break;
        default:
            $sizes = [
                'mini' => [
                    'width' => 320,
                    'height' => 320,
                    'path' => str_replace('size_', 'mini_', $path_data)
                ],
                'detail' => [
                    'width' => 540,
                    'height' => 560,
                    'path' => str_replace('size_', 'detail_', $path_data)
                ],
                'cart' => [
                    'width' => 101,
                    'height' => 100,
                    'path' => str_replace('size_', 'cart_', $path_data)
                ],
                'banner' => [
                    'width' => 570,
                    'height' => 270,
                    'path' => str_replace('size_', 'banner_', $path_data)
                ]
            ];
            break;
    }

    $respuesta = array();

    $image = Image::make($imagen_data);
    foreach ($sizes as $nombre => $items){
        $width = null;
        $height = null;
        $path = null;
        foreach ($items as $key => $valor){
            if ($key == 'width') { $width = $valor; }
            if ($key == 'height') { $height = $valor; }
            if ($key == 'path') { $path = $valor; }
        }
        $respuesta[$nombre] = $path;
        $image->resize($width, $height);
        $image->save($path);
    }

    return $respuesta;

}

function crearImagenTemporal($photo, $carpeta): string
{
    $path_data = "storage/$carpeta/tmp_".$photo->getClientOriginalName();
    $imagen = $photo->temporaryUrl();
    $miniatura = crearMiniaturas($imagen, $path_data, 'temporal');
    return "".$miniatura['temporal'];
}

//borrar imagenes incluyendo las miniaturas
function borrarImagenes($imagen, $carpeta): void
{
    if ($imagen){
        //reenplazamos storage por public
        $imagen = str_replace('storage/', 'public/', $imagen);
        //definir tamaños
        $sizes = [
            'mini' => [
                'path' => str_replace($carpeta.'/', $carpeta.'/mini_', $imagen)
            ],
            'detail' => [
                'path' => str_replace($carpeta.'/', $carpeta.'/detail_', $imagen)
            ],
            'cart' => [
                'path' => str_replace($carpeta.'/', $carpeta.'/cart_', $imagen)
            ],
            'banner' => [
                'path' => str_replace($carpeta.'/', $carpeta.'/banner_', $imagen)
            ]
        ];

        $exite = Storage::exists($imagen);
        if ($exite){
            Storage::delete($imagen);
        }

        foreach ($sizes as $items){
            $exite = Storage::exists($items['path']);
            if ($exite){
                Storage::delete($items['path']);
            }
        }
    }
}

function verUtf8($string, $safeNull = false): string
{
    //$utf8_string = "Some UTF-8 encoded BATE QUEBRADO ÑñíÍÁÜ niño ó Ó string: é, ö, ü";
    $response = null;
    $text = 'NULL';
    if ($safeNull){
        $text = '';
    }
    if (!is_null($string)){
        $response = mb_convert_encoding($string, 'UTF-8');
    }
    if (!is_null($response)){
        $text = "$response";
    }
    return $text;
}

function obtenerPorcentaje($cantidad, $total): float|int
{
    if ($total != 0) {
        $porcentaje = ((float)$cantidad * 100) / $total; // Regla de tres
        $porcentaje = round($porcentaje, 2);  // Quitar los decimales
        return $porcentaje;
    }
    return 0;
}

//Crear JSON
function crearJson($array): false|string
{
    $json = array();
    foreach ($array as $key){
        $json[$key] = true;
    }
    return json_encode($json);
}

//Función comprueba una hora entre un rango
function hourIsBetween($from, $to, $input): bool
{
    $dateFrom = DateTime::createFromFormat('!H:i', $from);
    $dateTo = DateTime::createFromFormat('!H:i', $to);
    $dateInput = DateTime::createFromFormat('!H:i', $input);
    if ($dateFrom > $dateTo) $dateTo->modify('+1 day');
    return ($dateFrom <= $dateInput && $dateInput <= $dateTo) || ($dateFrom <= $dateInput->modify('+1 day') && $dateInput <= $dateTo);
    /*En la función lo que haremos será pasarle, el desde y el hasta del rango de horas que queremos que se encuentre y el datetime con la hora que nos llega.
Comprobaremos si la segunda hora que le pasamos es inferior a la primera, con lo cual entenderemos que es para el día siguiente.
Y al final devolveremos true o false dependiendo si el valor introducido se encuentra entre lo que le hemos pasado.*/
}

function dataSelect2($rows, $text = null): array
{
    $data = array();
    foreach ($rows as $row){
        switch ($text){
            case 'nombre':
                $text = $row->nombre;
                break;
            default:
                $text = $row->codigo.'  '.$row->nombre;
                break;
        }
        $option = [
            'id' => $row->id,
            'text' => $text
        ];
        array_push($data, $option);
    }
    return $data;
}

function getDataSelect2($rows, $text, $id = "rowquid"): array
{
    $data = [];
    $filas = $rows->toArray();
    foreach ($filas as $row){
        $option = [
            'id' => $row[$id],
            'text' => $row[$text]
        ];
        $data[] = $option;
    }
    return $data;
}

//********************** FUNCIONES PROPIAS DEL PROYECTO ATUAL ******************************

function getSemana($fecha): array
{
    $key = '-W';
    $carbon = CarbonImmutable::now();
    $explode = explode($key, $fecha);
    $year = $explode[0];
    $semana = intval($explode[1]);
    $date = Carbon::parse($carbon->week($semana)->format('d-m-Y'));
    $lunes = $date->startOfWeek()->format('Y-m-d');
    $martes = Carbon::parse($lunes)->addDay()->format('Y-m-d');
    $miercoles = Carbon::parse($lunes)->addDay(2)->format('Y-m-d');
    $jueves = Carbon::parse($lunes)->addDay(3)->format('Y-m-d');
    $viernes = Carbon::parse($lunes)->addDay(4)->format('Y-m-d');
    $sabado = Carbon::parse($lunes)->addDay(5)->format('Y-m-d');
    $domingo = $date->endOfWeek()->format('Y-m-d');
    return [$semana, $lunes, $martes, $miercoles, $jueves, $viernes, $sabado, $domingo, $year];
}

function array_sort_by($arrIni, $col, $order = SORT_ASC)
{
    $arrAux = array();
    foreach ($arrIni as $key=> $row)
    {
        $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
        $arrAux[$key] = strtolower($arrAux[$key]);
    }
    array_multisort($arrAux, $order, $arrIni);
    return $arrIni;
}

function calcularPrecios($empresa_id, $articulo_id, $tributarios_id, $unidades_id): array
{
    $resultado = array();
    $moneda_base = null;
    $precio_dolares = 0;
    $precio_bolivares = 0;
    $iva_dolares = 0;
    $iva_bolivares = 0;
    $neto_dolares = 0;
    $neto_bolivares = 0;
    $dolar = 1;
    $iva = 0;
    $oferta_dolares = 0;
    $oferta_bolivares = 0;
    $porcentaje = 0;

    $moneda = null;

    $parametro = Parametro::where('nombre', 'precio_dolar')->first();
    if ($parametro){
        $dolar = $parametro->valor;
    }

    $empresa = Empresa::find($empresa_id);
    if ($empresa){
        $moneda_base = $empresa->moneda;
    }

    $tributario = Tributario::find($tributarios_id);
    if ($tributario){
        $taza = intval($tributario->taza);
        if ($taza){
            $iva = $taza;
        }
    }

    $precio = Precio::where('empresas_id', $empresa_id)
        ->where('articulos_id', $articulo_id)
        ->where('unidades_id', $unidades_id)
        ->first();
    if ($precio){

        $moneda = $precio->moneda;

        if ($precio->moneda == "Dolares"){
            $precio_dolares = $precio->precio;
            $precio_bolivares = $precio->precio * $dolar;
        }else{
            $precio_bolivares = $precio->precio;
            $precio_dolares = round($precio->precio / $dolar, 2);
        }

        if ($iva){
            $iva_dolares = round($precio_dolares * ( $iva / 100), 2);
            $iva_bolivares = round($precio_bolivares * ( $iva / 100 ), 2);
        }

        $neto_dolares = round($precio_dolares + $iva_dolares, 2);
        $neto_bolivares = round($precio_bolivares + $iva_bolivares, 2);

    }

    $resultado['moneda_base'] = $moneda_base;
    $resultado['precio_dolares'] = $precio_dolares;
    $resultado['precio_bolivares'] = $precio_bolivares;
    $resultado['iva_dolares'] = $iva_dolares;
    $resultado['iva_bolivares'] = $iva_bolivares;
    $resultado['neto_dolares'] = $neto_dolares;
    $resultado['neto_bolivares'] = $neto_bolivares;
    $resultado['oferta_dolares'] = $oferta_dolares;
    $resultado['oferta_bolivares'] = $oferta_bolivares;
    $resultado['porcentaje'] = $porcentaje;
    //reportes excel
    $resultado['moneda'] = $moneda;

    //$resultado = ( $monto_total * ( $valor_iva / 100 ) );
    //En caso de que quieras redondear a dos decimales, te recomiendo usar la función number_format
    //$resultado = number_format($resultado, 2, '.', false);
    return $resultado;
}
