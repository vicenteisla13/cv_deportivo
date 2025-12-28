<?php
require __DIR__ . '/db.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campeonato = trim($_POST['campeonato'] ?? '');
    $fecha = trim($_POST['fecha'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $lugar_obtenido = trim($_POST['lugar_obtenido'] ?? '');
    $puntos_mejorar = trim($_POST['puntos_mejorar'] ?? '');
    $observacion = trim($_POST['observacion'] ?? '');

    if ($campeonato === '' || $fecha === '' || $categoria === '' || $lugar_obtenido === '' || $puntos_mejorar === '' || $observacion === '') {
        $errors[] = 'Todos los campos son obligatorios.';
    }

    $fecha_valida = DateTime::createFromFormat('Y-m-d', $fecha);
    if ($fecha !== '' && (!$fecha_valida || $fecha_valida->format('Y-m-d') !== $fecha)) {
        $errors[] = 'La fecha debe tener el formato YYYY-MM-DD.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO curriculum (campeonato, fecha, categoria, lugar_obtenido, puntos_mejorar, observacion) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$campeonato, $fecha, $categoria, $lugar_obtenido, $puntos_mejorar, $observacion]);
        $success = true;
    }
}

$rows = $pdo->query('SELECT * FROM curriculum ORDER BY fecha DESC, id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Currículum Deportivo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 32px;
        }
        h1 {
            margin-bottom: 8px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccd3da;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            min-height: 80px;
            resize: vertical;
        }
        .full {
            grid-column: 1 / -1;
        }
        .actions {
            display: flex;
            justify-content: flex-end;
        }
        button {
            background: #2a6f97;
            color: #fff;
            border: none;
            padding: 12px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #245e80;
        }
        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 16px;
        }
        .alert.error {
            background: #ffe3e3;
            color: #a32626;
        }
        .alert.success {
            background: #dff5e1;
            color: #1c6b2e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border-bottom: 1px solid #e0e5ea;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #f0f3f6;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Currículum Deportivo</h1>
    <p>Registra tus campeonatos y mejora tu historial deportivo.</p>

    <?php if ($success): ?>
        <div class="alert success">Registro guardado correctamente.</div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <div>
            <label for="campeonato">Campeonato</label>
            <input type="text" id="campeonato" name="campeonato" required>
        </div>
        <div>
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" required>
        </div>
        <div>
            <label for="categoria">Categoría</label>
            <input type="text" id="categoria" name="categoria" required>
        </div>
        <div>
            <label for="lugar_obtenido">Lugar obtenido</label>
            <input type="text" id="lugar_obtenido" name="lugar_obtenido" required>
        </div>
        <div class="full">
            <label for="puntos_mejorar">Puntos a mejorar</label>
            <textarea id="puntos_mejorar" name="puntos_mejorar" required></textarea>
        </div>
        <div class="full">
            <label for="observacion">Observación</label>
            <textarea id="observacion" name="observacion" required></textarea>
        </div>
        <div class="actions full">
            <button type="submit">Guardar registro</button>
        </div>
    </form>

    <h2>Historial de campeonatos</h2>
    <?php if (!$rows): ?>
        <p>No hay registros todavía.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Campeonato</th>
                    <th>Fecha</th>
                    <th>Categoría</th>
                    <th>Lugar obtenido</th>
                    <th>Puntos a mejorar</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['campeonato'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['lugar_obtenido'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['puntos_mejorar'], ENT_QUOTES, 'UTF-8')); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['observacion'], ENT_QUOTES, 'UTF-8')); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
