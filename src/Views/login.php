<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
<div class="login-form">
    <h2>Iniciar sesión</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <div>
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <button type="submit">Iniciar sesión</button>
        </div>
    </form>
</div>
</body>
</html>
