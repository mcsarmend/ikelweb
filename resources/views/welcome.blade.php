<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIENVENIDO IKEL DRINKS</title>
    <!-- Estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@900&display=swap" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Noto Sans TC', sans-serif;
        }


        .container {
            max-width: 1000px;
            padding: 20px;
        }

        .tit {
            color: #87A52B;
        }

        .desc {
            color: #82A7CA
        }
    </style>
</head>

<body>


    <div class="container">
        <div class="row">
            <div class="col-md-6 p-3">
                <div class="row p-4">
                    <h2 class="display-5 tit">TODO LO QUE NECESITAS PARA TU FIESTA</h2>
                </div>
                <div class="row">
                    <p class="desc">LLEVAMOS TUS PRODUCTOS HASTA <br> LA PUERTA DE TU CASA TENEMOS <br> TODA LA COCTELERIA DE PISTOS <br> PITS</p>
                </div>
                <div class="row"><a href="{{ route('login') }}" class="btn btn-outline-primary">Accede</a></div>
                <div class="row"><img src="assets/bebidas1.png" alt="" width="300" height="300"></div>
            </div>
            <div class="col-md-6 p-3">
                <div class="row p-3" >
                    <img class ="p-3" src="assets/Ikel_landing.png" alt="" width="350" height="250">
                </div>
                <br>
                <div class="row">
                    <img src="assets/bebidas2.png" alt="" width="400" height="400">
                </div>
            </div>
        </div>
        <footer>
            <div class="text-center">
                <p>Ikel Drinks</p>
                <p>Todos los derechos reservados &copy; 2023</p>
            </div>
        </footer>


    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>



</body>

</html>
