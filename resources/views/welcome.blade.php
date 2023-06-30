<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIENVENIDO IKEL DRINKS</title>
    <!-- Estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .jumbotron {
            background-image: url("https://images.unsplash.com/photo-1687201363580-1cd1bb33c2b5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1332&q=80");
            background-size: cover;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }

        .jumbotron h1 {
            color: #fff;
            font-size: 48px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        .container {
            max-width: 800px;
        }
    </style>
</head>

<body>
    <div class="jumbotron">
        <h1>Bienvenido a IKEL DRINKS</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col">
                <h2>Sobre Nosotros</h2>
                <p>Somos una empresa dedicada a ofrecer soluciones innovadoras para tus necesidades. Nuestro equipo está
                    comprometido con brindarte productos y servicios de calidad.</p>
            </div>

        </div>
        <hr>
        <p>¿Ya tienes una cuenta? Inicia sesión.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
    </div>

    <!-- Scripts de jQuery y Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
