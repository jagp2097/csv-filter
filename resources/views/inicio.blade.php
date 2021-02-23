<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CSV-Filter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

</head>
<body>
    
    <div class="container my-3 card p-3">

        <h3>CSV-Filter Kirana Labs</h3>

        <div class="row">

            <div class="col-6">

                <form action="{{ route('csvfilter.uploadCsv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="mb-3">
                        <label class="form-label" for="csv">Carge el archivo CSV:</label>
                        <input class="form-control" id="csv" type="file" name="csv_file" accept="text/csv" required>
                        @error('csv_file')
                            <span class="text-danger">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button class="btn btn-success"type="submit">Cargar archivo</button>
                </form>

            </div>

        </div>

    </div>


</body>
</html>
