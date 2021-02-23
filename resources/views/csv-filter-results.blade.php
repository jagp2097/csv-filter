<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resultados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

</head>
<body>
    
    <div class="container">

        <div class="row">

            <div class="col-5 p-1">

                <h4>Registros filtrados</h4>

                <table class="table">
                    <thead>
                        <th>Num.</th>
                        <th>Nombre</th>
                        <th>Correo eléctronico</th>
                        <th>Teléfono</th>
                    </thead>
                    <tbody>
                        @foreach ($filteredRows as $row)
                            <tr @if($row['paint']) style="color: red;"  @endif >
                                <td>{{$row['rowNumber']}}</td>
                                <td>{{$row['row'][0]}}</td>
                                <td>{{$row['row'][1]}}</td>
                                <td>{{$row['row'][2]}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <div class="col-2"></div>

            <div class="col-5 p-1">
                
                <h4>Registros eliminados</h4>

                <table class="table">
                    <thead>
                        <th>Num.</th>
                        <th>Nombre</th>
                        <th>Correo eléctronico</th>
                        <th>Teléfono</th>
                    </thead>
                    <tbody>
                        @foreach ($deletedRows as $row)
                            <tr>
                                <td>{{$row['rowNumber']}}</td>
                                <td>{{$row['row'][0]}}</td>
                                <td>{{$row['row'][1]}}</td>
                                <td>{{$row['row'][2]}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <a href="{{ url('/') }}">Regresar a subir un archivo</a>

        </div>

    </div>
    
</body>
</html>