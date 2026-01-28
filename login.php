<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header("Location: /Formato/dashboard.php", true, 302);
    exit();
}

include("conexion.php");

$error = '';
$timeout = isset($_GET['timeout']) ? 'Su sesión ha expirado. Por favor inicie sesión nuevamente.' : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    if ($usuario && $password) {
        $con = odbc_connect($dsn, $usudb, $pwdb);
        
        if ($con) {
            $sql = "SELECT id_usuario, usuario, nombre_completo, email, rol 
                    FROM dbo.usuarios_ambulancia 
                    WHERE usuario = '$usuario' AND password = '$password' AND activo = 1";
            
            $result = odbc_exec($con, $sql);
            
            if ($result && $row = odbc_fetch_array($result)) {
                // Login exitoso
                $_SESSION['usuario_id'] = $row['id_usuario'];
                $_SESSION['usuario'] = $row['usuario'];
                $_SESSION['nombre_completo'] = $row['nombre_completo'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['rol'] = $row['rol'];
                $_SESSION['ultimo_acceso'] = time();
                
                // Actualizar último acceso
                $update = "UPDATE dbo.usuarios_ambulancia SET ultimo_acceso = GETDATE() WHERE id_usuario = " . $row['id_usuario'];
                odbc_exec($con, $update);
                
                odbc_close($con);
                header("Location: /Formato/dashboard.php", true, 302);
                exit();
            } else {
                $error = 'Usuario o contraseña incorrectos';
            }
            
            odbc_close($con);
        } else {
            $error = 'Error de conexión a la base de datos';
        }
    } else {
        $error = 'Por favor complete todos los campos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Gestión Ambulancias</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #205ca4 0%, #164273 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .login-header img {
            max-width: 180px;
            margin-bottom: 20px;
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 18px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .alert-danger {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert-danger i {
            margin-right: 10px;
        }

        .alert-warning {
            background: #ffeaa7;
            color: #d63031;
            border: 1px solid #fdcb6e;
        }

        .alert-warning i {
            margin-right: 10px;
        }

        .login-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            font-size: 13px;
            color: #666;
        }

        .show-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 18px;
        }

        .show-password:hover {
            color: #667eea;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <img src="img/logo_dos.png" alt="Logo Gestión Salud">
        <h1>Sistema de Gestión</h1>
        <p>Control de Ambulancias y Formatos</p>
    </div>

    <div class="login-body">
        <?php if ($timeout): ?>
        <div class="alert alert-warning">
            <i class="fas fa-clock"></i>
            <?= htmlspecialchars($timeout) ?>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="usuario">
                    <i class="fas fa-user"></i> Usuario
                </label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" 
                           class="form-control" 
                           id="usuario" 
                           name="usuario" 
                           placeholder="Ingrese su usuario"
                           required
                           autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Contraseña
                </label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Ingrese su contraseña"
                           required>
                    <span class="show-password" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>
    </div>

    <div class="login-footer">
        <p>&copy; 2026 Gestión Salud IPS. Todos los derechos reservados.</p>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
</script>

</body>
</html>