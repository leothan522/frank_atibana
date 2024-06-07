<?php
//Funciones Personalizadas para el Proyecto
use Illuminate\Support\Facades\Auth;

function comprobarPermisos($routeName = null)
{

    if (leerJson(Auth::user()->permisos, $routeName) || Auth::user()->role == 1 || Auth::user()->role == 100) {
        return true;
    } else {
        return false;
    }

}

function comprobarAccesoEmpresa($permisos, $user_id)
{
    if (leerJson($permisos, $user_id) || Auth::user()->roler == 1 || Auth::user()->role == 100){
        return true;
    }else{
        return false;
    }
}

function allPermisos()
{
    $permisos = [
        'Stock' => [
            'route' => 'stock.index',
            'submenu' => [
                'Cambiar Estatus' => 'stock.estatus',
                'Ver Ajustes' => 'ajustes.index',
                'Imprimir Ajustes' => 'ajustes.print',
                'Crear Ajustes' => 'ajustes.create',
                'Editar Ajustes' => 'ajustes.edit',
                'Anular Ajustes' => 'ajustes.anular',

            ]
        ],
        'Articulos' => [
            'route' => 'articulos.index',
            'submenu' => [
                'Crear Articulos' => 'articulos.create',
                'Editar Articulos' => 'articulos.edit',
                'Cambiar Estatus' => 'articulos.estatus',
                'Cambiar Unidades' => 'articulos.unidades',
                'Cambiar Precios' => 'articulos.precios',
                'Cambiar Identificadores' => 'articulos.identificadores',
                'Cambiar Imagenes' => 'articulos.imagenes'
            ]
        ],
        'Categorias' => [
            'route' => 'categorias.index',
            'submenu' => [
                'Crear categorias' => 'categorias.create',
                'Editar categorias' => 'categorias.edit',
                'Borrar categorias' => 'categorias.destroy'

            ]
        ],
        'Unidades' => [
            'route' => 'unidades.index',
            'submenu' => [
                'Crear unidades' => 'unidades.create',
                'Editar unidades' => 'unidades.edit',
                'Borrar unidades' => 'unidades.destroy'

            ]
        ],
        'Usuarios' => [
            'route' => 'usuarios.index',
            'submenu' => [
                'Crear Usuarios' => 'usuarios.create',
                'Editar Usuarios' => 'usuarios.edit',
                'Suspender Usuarios' => 'usuarios.estatus',
                'Reestablecer Contraseña' => 'usuarios.password',
                'Descargar Excel' => 'usuarios.excel',
                'Eliminar Usuarios' => 'usuarios.destroy',
            ]
        ],
        'Empresas' => [
            'route' => 'empresas.index',
            'submenu' => [
                'Crear Empresas' => 'empresas.create',
                '[Abrir][Cerrar] Empresas' => 'empresas.estatus',
                'Definir Horarios' => 'empresas.horario',
                'Editar Empresas' => 'empresas.edit',
                'Borrar Empresas' => 'empresas.destroy'

            ]
        ]
        /*'Procedencias' => [
            'route' => 'procedencias.index',
            'submenu' => [
                'Crear procedencias' => 'procedencias.create',
                'Editar procedencias' => 'procedencias.edit',
                'Borrar procedencias' => 'procedencias.destroy'

            ]
        ],
        'Tributarios' => [
            'route' => 'tributarios.index',
            'submenu' => [
                'Crear tributarios' => 'tributarios.create',
                'Editar tributarios' => 'tributarios.edit',
                'Borrar tributarios' => 'tributarios.destroy'

            ]
        ]*/
    ];
    return $permisos;
}


function dataMunicipiosParroquias(): array
{
    /*$municipios = [
        [
            "id"=>1,
            "nombre"=>"JUAN GERMAN ROSCIO NIEVES",
            "mini"=>"ROSCIO",
            "parroquias"=>3
        ],
        [
            "id"=>2,
            "nombre"=>"FRANCISCO DE MIRANDA",
            "mini"=>"MIRANDA",
            "parroquias"=>4
        ],
        [
            "id"=>3,
            "nombre"=>"LEONARDO INFANTE",
            "mini"=>"INFANTE",
            "parroquias"=>2
        ],
        [
            "id"=>4,
            "nombre"=>"PEDRO ZARAZA",
            "mini"=>"ZARAZA",
            "parroquias"=>2
        ],
        [
            "id"=>5,
            "nombre"=>"JOSE TADEO MONAGAS",
            "mini"=>"MONAGAS",
            "parroquias"=>7
        ],
        [
            "id"=>6,
            "nombre"=>"JOSE FELIX RIBAS",
            "mini"=>"RIBAS",
            "parroquias"=>2
        ],
        [
            "id"=>7,
            "nombre"=>"CAMAGUAN",
            "mini"=>"CAMAGUAN",
            "parroquias"=>3
        ],
        [
            "id"=>8,
            "nombre"=>"JULIAN MELLADO",
            "mini"=>"MELLADO",
            "parroquias"=>2
        ],
        [
            "id"=>9,
            "nombre"=>"EL SOCORRO",
            "mini"=>"EL SOCORRO",
            "parroquias"=>1
        ],
        [
            "id"=>10,
            "nombre"=>"SANTA MARIA DE IPIRE",
            "mini"=>"SANTA MARIA",
            "parroquias"=>2
        ],
        [
            "id"=>11,
            "nombre"=>"CHAGUARAMAS",
            "mini"=>"CHAGUARAMAS",
            "parroquias"=>1
        ],
        [
            "id"=>12,
            "nombre"=>"JUAN JOSE RONDON",
            "mini"=>"RONDON",
            "parroquias"=>3
        ],
        [
            "id"=>13,
            "nombre"=>"SAN JOSE DE GUARIBE",
            "mini"=>"GUARIBE",
            "parroquias"=>1
        ],
        [
            "id"=>14,
            "nombre"=>"SAN GERONIMO DE GUAYABAL",
            "mini"=>"GUAYABAL",
            "parroquias"=>2
        ],
        [
            "id"=>15,
            "nombre"=>"ORTIZ",
            "mini"=>"ORTIZ",
            "parroquias"=>4
        ]
    ];
    $parroquias = [
        [
            "id"=>1,
            "nombre"=>"CAMAGUAN",
            "mini"=>null,
            "municipios_id"=>7
        ],
        [
            "id"=>2,
            "nombre"=>"PUERTO MIRANDA",
            "mini"=>null,
            "municipios_id"=>7
        ],
        [
            "id"=>3,
            "nombre"=>"UVERITO",
            "mini"=>null,
            "municipios_id"=>7
        ],
        [
            "id"=>4,
            "nombre"=>"CHAGUARAMAS",
            "mini"=>null,
            "municipios_id"=>11
        ],
        [
            "id"=>5,
            "nombre"=>"EL SOCORRO",
            "mini"=>null,
            "municipios_id"=>9
        ],
        [
            "id"=>6,
            "nombre"=>"CALABOZO",
            "mini"=>null,
            "municipios_id"=>2
        ],
        [
            "id"=>7,
            "nombre"=>"EL RASTRO",
            "mini"=>null,
            "municipios_id"=>2
        ],
        [
            "id"=>8,
            "nombre"=>"GUARDATINAJAS",
            "mini"=>null,
            "municipios_id"=>2
        ],
        [
            "id"=>9,
            "nombre"=>"EL CALVARIO",
            "mini"=>null,
            "municipios_id"=>2
        ],
        [
            "id"=>10,
            "nombre"=>"TUCUPIDO",
            "mini"=>null,
            "municipios_id"=>6
        ],
        [
            "id"=>11,
            "nombre"=>"SAN RAFAEL DE LAYA",
            "mini"=>null,
            "municipios_id"=>6
        ],
        [
            "id"=>12,
            "nombre"=>"ALTAGRACIA DE ORITUCO",
            "mini"=>"ALTAGRACIA",
            "municipios_id"=>5
        ],
        [
            "id"=>13,
            "nombre"=>"SAN RAFAEL DE ORITUCO",
            "mini"=>"SAN RAFAEL",
            "municipios_id"=>5
        ],
        [
            "id"=>14,
            "nombre"=>"LIBERTAD DE ORITUCO",
            "mini"=>"LIBERTAD",
            "municipios_id"=>5
        ],
        [
            "id"=>15,
            "nombre"=>"SAN FRANCISCO DE MACAIRA",
            "mini"=>"MACAIRA",
            "municipios_id"=>5
        ],
        [
            "id"=>16,
            "nombre"=>"PASO REAL DE MACAIRA",
            "mini"=>"PASO REAL",
            "municipios_id"=>5
        ],
        [
            "id"=>17,
            "nombre"=>"CARLOS SOUBLETTE",
            "mini"=>"SOUBLETTE",
            "municipios_id"=>5
        ],
        [
            "id"=>18,
            "nombre"=>"FRANCISCO JAVIER DE LAZAMA",
            "mini"=>"LEZAMA",
            "municipios_id"=>5
        ],
        [
            "id"=>19,
            "nombre"=>"SAN JUAN",
            "mini"=>null,
            "municipios_id"=>1
        ],
        [
            "id"=>20,
            "nombre"=>"PARAPARA",
            "mini"=>null,
            "municipios_id"=>1
        ],
        [
            "id"=>21,
            "nombre"=>"CANTAGALLO",
            "mini"=>null,
            "municipios_id"=>1
        ],
        [
            "id"=>22,
            "nombre"=>"LAS MERCEDES DEL LLANO",
            "mini"=>"LAS MERCEDES",
            "municipios_id"=>12
        ],
        [
            "id"=>23,
            "nombre"=>"SANTA RITA DE MANAPIRE",
            "mini"=>"SANTA RITA",
            "municipios_id"=>12
        ],
        [
            "id"=>24,
            "nombre"=>"CABRUTA",
            "mini"=>null,
            "municipios_id"=>12
        ],
        [
            "id"=>25,
            "nombre"=>"EL SOMBRERO",
            "mini"=>null,
            "municipios_id"=>8
        ],
        [
            "id"=>26,
            "nombre"=>"SOSA",
            "mini"=>null,
            "municipios_id"=>8
        ],
        [
            "id"=>27,
            "nombre"=>"VALLE DE LA PASCUA",
            "mini"=>null,
            "municipios_id"=>3
        ],
        [
            "id"=>28,
            "nombre"=>"ESPINO",
            "mini"=>null,
            "municipios_id"=>3
        ],
        [
            "id"=>29,
            "nombre"=>"Ortiz",
            "mini"=>null,
            "municipios_id"=>15
        ],
        [
            "id"=>30,
            "nombre"=>"SAN JOSE DE TIZNADOS",
            "mini"=>"SAN JOSE",
            "municipios_id"=>15
        ],
        [
            "id"=>31,
            "nombre"=>"SAN LORENZO DE TIZNADOS",
            "mini"=>"SAN LORENZO",
            "municipios_id"=>15
        ],
        [
            "id"=>32,
            "nombre"=>"SAN FRANCISCO DE TIZNADOSº",
            "mini"=>"SAN FRANCISCO",
            "municipios_id"=>15
        ],
        [
            "id"=>33,
            "nombre"=>"ZARAZA",
            "mini"=>null,
            "municipios_id"=>4
        ],
        [
            "id"=>34,
            "nombre"=>"SAN JOSE DE UNARE",
            "mini"=>null,
            "municipios_id"=>4
        ],
        [
            "id"=>35,
            "nombre"=>"GUAYABAL",
            "mini"=>null,
            "municipios_id"=>14
        ],
        [
            "id"=>36,
            "nombre"=>"CAZORLA",
            "mini"=>null,
            "municipios_id"=>14
        ],
        [
            "id"=>37,
            "nombre"=>"GUARIBE",
            "mini"=>null,
            "municipios_id"=>13
        ],
        [
            "id"=>38,
            "nombre"=>"SANTA MARIA",
            "mini"=>null,
            "municipios_id"=>10
        ],
        [
            "id"=>39,
            "nombre"=>"ALTAMIRA",
            "mini"=>null,
            "municipios_id"=>10
        ]
    ];*/
    $municipios = [
        [
            "id" => 1,
            "nombre" => "JUAN GERMAN ROSCIO NIEVES",
            "mini" => "ROSCIO",
            "parroquias" => 3,
            "familias" => 32586
        ],
        [
            "id" => 2,
            "nombre" => "FRANCISCO DE MIRANDA",
            "mini" => "MIRANDA",
            "parroquias" => 4,
            "familias" => 34452
        ],
        [
            "id" => 3,
            "nombre" => "LEONARDO INFANTE",
            "mini" => "INFANTE",
            "parroquias" => 2,
            "familias" => 24811
        ],
        [
            "id" => 4,
            "nombre" => "PEDRO ZARAZA",
            "mini" => "ZARAZA",
            "parroquias" => 2,
            "familias" => 20063
        ],
        [
            "id" => 5,
            "nombre" => "JOSE TADEO MONAGAS",
            "mini" => "MONAGAS",
            "parroquias" => 7,
            "familias" => 14086
        ],
        [
            "id" => 6,
            "nombre" => "JOSE FELIX RIBAS",
            "mini" => "RIBAS",
            "parroquias" => 2,
            "familias" => 9551
        ],
        [
            "id" => 7,
            "nombre" => "CAMAGUAN",
            "mini" => "CAMAGUAN",
            "parroquias" => 3,
            "familias" => 10160
        ],
        [
            "id" => 8,
            "nombre" => "JULIAN MELLADO",
            "mini" => "MELLADO",
            "parroquias" => 2,
            "familias" => 6838
        ],
        [
            "id" => 9,
            "nombre" => "EL SOCORRO",
            "mini" => "EL SOCORRO",
            "parroquias" => 1,
            "familias" => 7146
        ],
        [
            "id" => 10,
            "nombre" => "SANTA MARIA DE IPIRE",
            "mini" => "SANTA MARIA",
            "parroquias" => 2,
            "familias" => 4631
        ],
        [
            "id" => 11,
            "nombre" => "CHAGUARAMAS",
            "mini" => "CHAGUARAMAS",
            "parroquias" => 1,
            "familias" => 1588
        ],
        [
            "id" => 12,
            "nombre" => "JUAN JOSE RONDON",
            "mini" => "RONDON",
            "parroquias" => 3,
            "familias" => 420
        ],
        [
            "id" => 13,
            "nombre" => "SAN JOSE DE GUARIBE",
            "mini" => "GUARIBE",
            "parroquias" => 1,
            "familias" => 2936
        ],
        [
            "id" => 14,
            "nombre" => "SAN GERONIMO DE GUAYABAL",
            "mini" => "GUAYABAL",
            "parroquias" => 2,
            "familias" => 7096
        ],
        [
            "id" => 15,
            "nombre" => "ORTIZ",
            "mini" => "ORTIZ",
            "parroquias" => 4,
            "familias" => 6581
        ]
    ];

    $parroquias = [
        [
            "id" => 1,
            "nombre" => "CAMAGUAN",
            "mini" => null,
            "municipios_id" => 7
        ],
        [
            "id" => 2,
            "nombre" => "PUERTO MIRANDA",
            "mini" => null,
            "municipios_id" => 7
        ],
        [
            "id" => 3,
            "nombre" => "UVERITO",
            "mini" => null,
            "municipios_id" => 7
        ],
        [
            "id" => 4,
            "nombre" => "CHAGUARAMAS",
            "mini" => null,
            "municipios_id" => 11
        ],
        [
            "id" => 5,
            "nombre" => "EL SOCORRO",
            "mini" => null,
            "municipios_id" => 9
        ],
        [
            "id" => 6,
            "nombre" => "CALABOZO",
            "mini" => null,
            "municipios_id" => 2
        ],
        [
            "id" => 7,
            "nombre" => "EL RASTRO",
            "mini" => null,
            "municipios_id" => 2
        ],
        [
            "id" => 8,
            "nombre" => "GUARDATINAJAS",
            "mini" => null,
            "municipios_id" => 2
        ],
        [
            "id" => 9,
            "nombre" => "EL CALVARIO",
            "mini" => null,
            "municipios_id" => 2
        ],
        [
            "id" => 10,
            "nombre" => "TUCUPIDO",
            "mini" => null,
            "municipios_id" => 6
        ],
        [
            "id" => 11,
            "nombre" => "SAN RAFAEL DE LAYA",
            "mini" => null,
            "municipios_id" => 6
        ],
        [
            "id" => 12,
            "nombre" => "ALTAGRACIA DE ORITUCO",
            "mini" => "ALTAGRACIA",
            "municipios_id" => 5
        ],
        [
            "id" => 13,
            "nombre" => "SAN RAFAEL DE ORITUCO",
            "mini" => "SAN RAFAEL",
            "municipios_id" => 5
        ],
        [
            "id" => 14,
            "nombre" => "LIBERTAD DE ORITUCO",
            "mini" => "LIBERTAD",
            "municipios_id" => 5
        ],
        [
            "id" => 15,
            "nombre" => "SAN FRANCISCO DE MACAIRA",
            "mini" => "MACAIRA",
            "municipios_id" => 5
        ],
        [
            "id" => 16,
            "nombre" => "PASO REAL DE MACAIRA",
            "mini" => "PASO REAL",
            "municipios_id" => 5
        ],
        [
            "id" => 17,
            "nombre" => "CARLOS SOUBLETTE",
            "mini" => "SOUBLETTE",
            "municipios_id" => 5
        ],
        [
            "id" => 18,
            "nombre" => "FRANCISCO JAVIER DE LAZAMA",
            "mini" => "LEZAMA",
            "municipios_id" => 5
        ],
        [
            "id" => 19,
            "nombre" => "SAN JUAN",
            "mini" => null,
            "municipios_id" => 1
        ],
        [
            "id" => 20,
            "nombre" => "PARAPARA",
            "mini" => null,
            "municipios_id" => 1
        ],
        [
            "id" => 21,
            "nombre" => "CANTAGALLO",
            "mini" => null,
            "municipios_id" => 1
        ],
        [
            "id" => 22,
            "nombre" => "LAS MERCEDES DEL LLANO",
            "mini" => "LAS MERCEDES",
            "municipios_id" => 12
        ],
        [
            "id" => 23,
            "nombre" => "SANTA RITA DE MANAPIRE",
            "mini" => "SANTA RITA",
            "municipios_id" => 12
        ],
        [
            "id" => 24,
            "nombre" => "CABRUTA",
            "mini" => null,
            "municipios_id" => 12
        ],
        [
            "id" => 25,
            "nombre" => "EL SOMBRERO",
            "mini" => null,
            "municipios_id" => 8
        ],
        [
            "id" => 26,
            "nombre" => "SOSA",
            "mini" => null,
            "municipios_id" => 8
        ],
        [
            "id" => 27,
            "nombre" => "VALLE DE LA PASCUA",
            "mini" => null,
            "municipios_id" => 3
        ],
        [
            "id" => 28,
            "nombre" => "ESPINO",
            "mini" => null,
            "municipios_id" => 3
        ],
        [
            "id" => 29,
            "nombre" => "Ortiz",
            "mini" => null,
            "municipios_id" => 15
        ],
        [
            "id" => 30,
            "nombre" => "SAN JOSE DE TIZNADOS",
            "mini" => "SAN JOSE",
            "municipios_id" => 15
        ],
        [
            "id" => 31,
            "nombre" => "SAN LORENZO DE TIZNADOS",
            "mini" => "SAN LORENZO",
            "municipios_id" => 15
        ],
        [
            "id" => 32,
            "nombre" => "SAN FRANCISCO DE TIZNADOSº",
            "mini" => "SAN FRANCISCO",
            "municipios_id" => 15
        ],
        [
            "id" => 33,
            "nombre" => "ZARAZA",
            "mini" => null,
            "municipios_id" => 4
        ],
        [
            "id" => 34,
            "nombre" => "SAN JOSE DE UNARE",
            "mini" => null,
            "municipios_id" => 4
        ],
        [
            "id" => 35,
            "nombre" => "GUAYABAL",
            "mini" => null,
            "municipios_id" => 14
        ],
        [
            "id" => 36,
            "nombre" => "CAZORLA",
            "mini" => null,
            "municipios_id" => 14
        ],
        [
            "id" => 37,
            "nombre" => "GUARIBE",
            "mini" => null,
            "municipios_id" => 13
        ],
        [
            "id" => 38,
            "nombre" => "SANTA MARIA",
            "mini" => null,
            "municipios_id" => 10
        ],
        [
            "id" => 39,
            "nombre" => "ALTAMIRA",
            "mini" => null,
            "municipios_id" => 10
        ]

    ];

    return [$municipios, $parroquias];
}
