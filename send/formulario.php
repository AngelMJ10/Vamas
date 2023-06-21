<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</head>
<body>
<form action="upload.php" method="POST" enctype="multipart/form-data" id="formulario">
    <h4>Formulario para enviar cosas a Drive</h4>

    <div class="mb-3">
        <div class="input-group mb-3">
            <span class="input-group-text bg-light" id="mensaje2">Mensaje</span>
            <textarea type="text" class="form-control" id="mensaje" name="mensaje" placeholder="Mensaje" aria-label="mensaje" aria-describedby="mensaje" required></textarea>
        </div>
    </div>

    <div class="mb-3">
        <div class="input-group mb-3">
            <span class="input-group-text bg-light" id="porcentaje2">Porcentaje de la tarea</span>
            <input type="number" class="form-control" id="porcentaje" name="porcentaje" placeholder="Porcentaje %" aria-label="porcentaje" aria-describedby="porcentaje" required>
        </div>
    </div>

    <div class="input-group mt-5">
        <input type="file" class="form-control form-control-sm text-right mb-3" name="documento" id="documento" required>
    </div>

    <button type="submit" class="btn btn-outline-primary">Enviar</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formulario = document.getElementById('formulario');

        formulario.addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar que se envíe el formulario de forma convencional

            // Obtener el archivo seleccionado
            const fileInput = document.getElementById('documento');
            const file = fileInput.files[0];

            // Crear objeto FormData con el archivo y los campos adicionales
            const formData = new FormData();
            formData.append('documento', file);
            formData.append('mensaje', document.getElementById('mensaje').value);
            formData.append('porcentaje', document.getElementById('porcentaje').value);

            // Enviar petición AJAX al archivo upload.php
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                return response.text();
            })
            .then(function(data) {
                // Manejar la respuesta del servidor
                alert(data);
                console.log(data);
            })
            .catch(function(error) {
                // Manejar errores de la petición
                console.error(error);
            });
        });
    });
</script>





</body>
</html>
