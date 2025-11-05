<?php
function validarTarea(array $datosFormulario): array {
    $datosValidados = [
        'id'             => isset($datosFormulario['id']) ? htmlspecialchars(trim((string)$datosFormulario['id'])) : '',
        'contacto'       => isset($datosFormulario['contacto']) ? htmlspecialchars(trim($datosFormulario['contacto'])) : '',
        'nif'            => isset($datosFormulario['nif']) ? htmlspecialchars(trim($datosFormulario['nif'])) : '',
        'telefono'       => isset($datosFormulario['telefono']) ? htmlspecialchars(trim($datosFormulario['telefono'])) : '',
        'email'          => isset($datosFormulario['email']) ? htmlspecialchars(trim($datosFormulario['email'])) : '',
        'direccion'      => isset($datosFormulario['direccion']) ? htmlspecialchars(trim($datosFormulario['direccion'])) : '',
        'poblacion'      => isset($datosFormulario['poblacion']) ? htmlspecialchars(trim($datosFormulario['poblacion'])) : '',
        'cp'             => isset($datosFormulario['cp']) ? htmlspecialchars(trim($datosFormulario['cp'])) : '',
        'provincia'      => isset($datosFormulario['provincia']) ? htmlspecialchars(trim($datosFormulario['provincia'])) : '',
        'descripcion'    => isset($datosFormulario['descripcion']) ? htmlspecialchars(trim($datosFormulario['descripcion'])) : '',
        'fecha'          => isset($datosFormulario['fecha']) ? htmlspecialchars(trim($datosFormulario['fecha'])) : '',
        'operario'       => isset($datosFormulario['operario']) ? htmlspecialchars(trim($datosFormulario['operario'])) : '',
        'anot_prev'      => isset($datosFormulario['anot_prev']) ? htmlspecialchars(trim($datosFormulario['anot_prev'])) : '',
        'anot_post'      => isset($datosFormulario['anot_post']) ? htmlspecialchars(trim($datosFormulario['anot_post'])) : '',
        'estado'         => isset($datosFormulario['estado']) ? htmlspecialchars(trim($datosFormulario['estado'])) : '',
        'fecha_creacion' => isset($datosFormulario['fecha_creacion']) ? htmlspecialchars(trim($datosFormulario['fecha_creacion'])) : '',
    ];

    $erroresValidacion = [];

    if ($datosValidados['contacto'] === '')    $erroresValidacion['contacto'] = 'La persona de contacto es obligatoria.';
    if ($datosValidados['descripcion'] === '') $erroresValidacion['descripcion'] = 'La descripción es obligatoria.';
    if ($datosValidados['email'] === '')       $erroresValidacion['email'] = 'El email es obligatorio.';

    if ($datosValidados['email'] !== '' && !filter_var($datosValidados['email'], FILTER_VALIDATE_EMAIL))
        $erroresValidacion['email'] = 'Email con formato no válido.';

    if ($datosValidados['telefono'] === '') {
        $erroresValidacion['telefono'] = 'El teléfono es obligatorio.';
    } else {
        $telefonoSoloDigitos = str_replace([' ', '-', '+', '.', '(', ')'], '', $datosValidados['telefono']);
        if (!ctype_digit($telefonoSoloDigitos) || strlen($telefonoSoloDigitos) < 7 || strlen($telefonoSoloDigitos) > 16)
            $erroresValidacion['telefono'] = 'Teléfono no válido.';
    }

    if ($datosValidados['nif'] !== '') {
        $nifSinGuiones = str_replace('-', '', $datosValidados['nif']);
        if (!ctype_alnum($nifSinGuiones) || strlen($nifSinGuiones) < 8 || strlen($nifSinGuiones) > 12)
            $erroresValidacion['nif'] = 'Formato de NIF/CIF no válido.';
    }

    if ($datosValidados['cp'] !== '') {
        if (!ctype_digit($datosValidados['cp']) || strlen($datosValidados['cp']) !== 5)
            $erroresValidacion['cp'] = 'Código postal debe ser 5 números.';
    }

    if ($datosValidados['provincia'] === '') {
        $erroresValidacion['provincia'] = 'Selecciona una provincia.';
    } else {
        if (!ctype_digit($datosValidados['provincia']) || strlen($datosValidados['provincia']) !== 2) {
            $erroresValidacion['provincia'] = 'Código de provincia no válido.';
        } elseif ($datosValidados['cp'] !== '' && strlen($datosValidados['cp']) === 5) {
            $prefijoCp = substr($datosValidados['cp'], 0, 2);
            if ($prefijoCp !== $datosValidados['provincia'])
                $erroresValidacion['provincia'] = 'La provincia debe coincidir con los dos primeros dígitos del CP.';
        }
    }

    if ($datosValidados['fecha'] === '') {
        $erroresValidacion['fecha'] = 'La fecha de realización es obligatoria.';
    } else {
        $partesFecha = explode('/', $datosValidados['fecha']);
        if (count($partesFecha) !== 3) {
            $erroresValidacion['fecha'] = 'Usa formato dd/mm/aaaa.';
        } else {
            [$dd, $mm, $aaaa] = $partesFecha;
            if (!ctype_digit($dd) || !ctype_digit($mm) || !ctype_digit($aaaa) || strlen($dd)!==2 || strlen($mm)!==2 || strlen($aaaa)!==4) {
                $erroresValidacion['fecha'] = 'Usa formato dd/mm/aaaa.';
            } else {
                $dia = (int)$dd; $mes = (int)$mm; $anio = (int)$aaaa;
                if (!checkdate($mes, $dia, $anio)) {
                    $erroresValidacion['fecha'] = 'Fecha no válida.';
                } else {
                    $hoyTs = strtotime('today');
                    $fechaTs = strtotime("$anio-$mm-$dd");
                    if ($fechaTs <= $hoyTs) $erroresValidacion['fecha'] = 'Debe ser posterior a hoy.';
                }
            }
        }
    }

    $estadosPermitidos = ['B','P','R','C'];
    if ($datosValidados['estado'] === '' || !in_array($datosValidados['estado'], $estadosPermitidos, true))
        $erroresValidacion['estado'] = 'Estado no válido.';

    if ($datosValidados['fecha_creacion'] === '') {
        $datosValidados['fecha_creacion'] = date('d/m/Y');
    } else {
        $partesCreacion = explode('/', $datosValidados['fecha_creacion']);
        if (count($partesCreacion) !== 3) {
            $datosValidados['fecha_creacion'] = date('d/m/Y');
        } else {
            [$d,$m,$y] = $partesCreacion;
            if (!ctype_digit($d) || !ctype_digit($m) || !ctype_digit($y) || !checkdate((int)$m,(int)$d,(int)$y))
                $datosValidados['fecha_creacion'] = date('d/m/Y');
        }
    }

    return [$datosValidados, $erroresValidacion];
}
