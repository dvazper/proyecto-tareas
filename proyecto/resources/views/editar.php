<?php
require __DIR__ . '/../app/validate.php';
require __DIR__ . '/../data/seed.php';

$provincias = [
  '01'=>'Álava','02'=>'Albacete','03'=>'Alicante','04'=>'Almería','05'=>'Ávila',
  '06'=>'Badajoz','07'=>'Illes Balears','08'=>'Barcelona','09'=>'Burgos','10'=>'Cáceres',
  '11'=>'Cádiz','12'=>'Castellón','13'=>'Ciudad Real','14'=>'Córdoba','15'=>'A Coruña',
  '16'=>'Cuenca','17'=>'Girona','18'=>'Granada','19'=>'Guadalajara','20'=>'Gipuzkoa',
  '21'=>'Huelva','22'=>'Huesca','23'=>'Jaén','24'=>'León','25'=>'Lleida',
  '26'=>'La Rioja','27'=>'Lugo','28'=>'Madrid','29'=>'Málaga','30'=>'Murcia',
  '31'=>'Navarra','32'=>'Ourense','33'=>'Asturias','34'=>'Palencia','35'=>'Las Palmas',
  '36'=>'Pontevedra','37'=>'Salamanca','38'=>'Santa Cruz de Tenerife','39'=>'Cantabria','40'=>'Segovia',
  '41'=>'Sevilla','42'=>'Soria','43'=>'Tarragona','44'=>'Teruel','45'=>'Toledo',
  '46'=>'València','47'=>'Valladolid','48'=>'Bizkaia','49'=>'Zamora','50'=>'Zaragoza',
  '51'=>'Ceuta','52'=>'Melilla'
];

$datos = [];
$errores = [];
$encontrada = null;

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    foreach ($tareas as $t) {
        if ((int)$t['id'] === $id) { $encontrada = $t; break; }
    }
}

if (!$encontrada && !isset($_POST['contacto'])) {
    http_response_code(404);
    echo 'No existe la tarea solicitada. <a href="index.php">Volver</a>';
    exit;
}

if (isset($_POST['contacto'])) {
    [$datos, $errores] = validarTarea($_POST);
} else {
    $datos = $encontrada;
}

function v($k, $d) { return isset($d[$k]) ? $d[$k] : ''; }
function err($k, $e) { return isset($e[$k]) ? '<small class="error">'.$e[$k].'</small>' : ''; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Editar tarea</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header>
  <h1>Gestor de tareas</h1>
  <nav><a href="index.php">Inicio</a> · <strong>Editar</strong></nav>
</header>

<main>
  <h2>Editar tarea #<?= htmlspecialchars(isset($encontrada['id']) ? $encontrada['id'] : (isset($_GET['id'])?$_GET['id']:'') ) ?></h2>

  <?php if ($errores): ?>
    <p class="msg error">Revisa los campos marcados en rojo.</p>
  <?php elseif (isset($_POST['contacto'])): ?>
    <p class="msg ok">Validación OK (simulación sin BD).</p>
  <?php endif; ?>

  <form action="" method="post" novalidate enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars(v('id',$datos) ?: (isset($encontrada['id'])?$encontrada['id']:'') ) ?>">

    <label>Persona de contacto
      <input type="text" name="contacto" value="<?= v('contacto',$datos) ?>">
      <?= err('contacto',$errores) ?>
    </label>

    <label>NIF/CIF
      <input type="text" name="nif" value="<?= v('nif',$datos) ?>">
      <?= err('nif',$errores) ?>
    </label>

    <label>Teléfono
      <input type="text" name="telefono" value="<?= v('telefono',$datos) ?>">
      <?= err('telefono',$errores) ?>
    </label>

    <label>Correo electrónico
      <input type="text" name="email" value="<?= v('email',$datos) ?>">
      <?= err('email',$errores) ?>
    </label>

    <label>Dirección
      <input type="text" name="direccion" value="<?= v('direccion',$datos) ?>">
    </label>

    <label>Población
      <input type="text" name="poblacion" value="<?= v('poblacion',$datos) ?>">
    </label>

    <label>Código postal
      <input type="text" name="cp" value="<?= v('cp',$datos) ?>">
      <?= err('cp',$errores) ?>
    </label>

    <label>Provincia
      <select name="provincia">
        <option value="">— Selecciona —</option>
        <?php foreach($provincias as $idp=>$nom): ?>
          <option value="<?= $idp ?>" <?= v('provincia',$datos)===$idp?'selected':'' ?>><?= $nom ?></option>
        <?php endforeach; ?>
      </select>
      <?= err('provincia',$errores) ?>
    </label>

    <label>Descripción
      <input type="text" name="descripcion" value="<?= v('descripcion',$datos) ?>">
      <?= err('descripcion',$errores) ?>
    </label>

    <label>Fecha de realización (dd/mm/aaaa)
      <input type="text" name="fecha" value="<?= v('fecha',$datos) ?>">
      <?= err('fecha',$errores) ?>
    </label>

    <label>Estado
      <select name="estado">
        <?php $est = v('estado',$datos) ?: 'B'; ?>
        <option value="B" <?= $est==='B'?'selected':'' ?>>B (Esperando aprobación)</option>
        <option value="P" <?= $est==='P'?'selected':'' ?>>P (Pendiente)</option>
        <option value="R" <?= $est==='R'?'selected':'' ?>>R (Realizada)</option>
        <option value="C" <?= $est==='C'?'selected':'' ?>>C (Cancelada)</option>
      </select>
      <?= err('estado',$errores) ?>
    </label>

    <label>Operario
      <select name="operario">
        <option value="">— Selecciona —</option>
        <option <?= v('operario',$datos)==='ope1'?'selected':'' ?> value="ope1">Operario 1</option>
        <option <?= v('operario',$datos)==='ope2'?'selected':'' ?> value="ope2">Operario 2</option>
      </select>
    </label>

    <label>Anotaciones previas
      <textarea name="anot_prev"><?= v('anot_prev',$datos) ?></textarea>
    </label>

    <label>Anotaciones posteriores
      <textarea name="anot_post"><?= v('anot_post',$datos) ?></textarea>
    </label>

    <label>Fecha de creación
      <input type="text" name="fecha_creacion" value="<?= v('fecha_creacion',$datos) ?>" readonly>
    </label>

    <label>Fichero resumen (placeholder)
      <input type="file" name="fichero" disabled>
    </label>

    <label>Fotos (placeholder)
      <input type="file" name="fotos[]" multiple disabled>
    </label>

    <button type="submit">Guardar cambios</button>
  </form>
</main>
</body>
</html>
