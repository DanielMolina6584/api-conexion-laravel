<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
       
    <title>Usuarios</title>

    <script>
        axios.defaults.headers.common = {
            "token": '<?= session()->get('token') ?>'
        }
    </script>
</head>
<style>
    body{
        background-color: gray;
    }
    .divTable {
        margin: 0 60px;
    }

    a {
        text-decoration: none;
        color: black;
    }

    button {
        border: black 2px solid;
        border-radius: 10px;
    }

    tr {
        text-align: center;
    }
</style>

<body>

    <button class="btn btn-primary" onclick='logout()'>Logout</button>


    <div class="col-md-6 offset-md-3">
        <h2>Formulario de Registro</h2>

        <form id="miFormulario" enctype="multipart/form-data">

            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre">

            <label for="apellido">Apellido:</label>
            <input type="text" class="form-control" id="apellido" name="apellido">

            <label for="email">Email:</label>
            <input type="text" class="form-control" id="email" name="email">

            <label for="cel">Celular:</label>
            <input type="text" class="form-control" id="cel" name="cel">

            <label for="image">Imagen:</label>
            <input type="file" class="form-control" id="image" name="image"><br>

            <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
        </form>
    </div>


    <br><br>

    <div class="divTable">

        <table id="miTabla" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Celular</th>
                    <th>Imagen</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($respuesta as $usuario): ?>
                    <tr>
                        <td>
                            <?= $usuario['id'] ?>
                        </td>
                        <td>
                            <?= $usuario['nombre'] ?>
                        </td>
                        <td>
                            <?= $usuario['apellido'] ?>
                        </td>
                        <td>
                            <?= $usuario['email'] ?>
                        </td>
                        <td>
                            <?= $usuario['cel'] ?>
                        </td>
                        <td>
                            <?php if (!empty($usuario['image'])): ?>
                                <img src="<?= url($usuario['image']) ?>" alt="Imagen de Usuario" width="100" height="80">
                            <?php else: ?>
                                Sin imagen
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-success"><a href="<?= url('obtenerId?id='.$usuario['id']) ?>">Editar</a></button>
                        </td>
                        <td>
                        <button class="btn btn-danger"
                            onclick="confirmarEliminacion(<?= $usuario['id']; ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

</body>

<script>

    /**************************Agregar************************************ */


    jQuery('#btnGuardar').on("click", function (event) {
        event.preventDefault();

        let form = document.getElementById('miFormulario')
        const formData = new FormData(form);

        axios.post('<?= url('crear') ?>', formData, {

        })
            .then(response => {
                console.log(response.data.errors)
                if (response.data.errors) {
                    const errorMessages = Object.values(response.data.errors).join('<br>');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        html: errorMessages
                    });
                    if (response.data.error === 'NO AUTORIZADO') {
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    }
                } else {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Tu usuario ha sido añadido",
                        showConfirmButton: false,
                        timer: 1500

                    });
                    setTimeout(function () {
                        location.reload();

                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Error:', error);

            });
    });



    /**********************Eliminar******************************/

    function confirmarEliminacion(id) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¡No podrás deshacer esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminarlo",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                eliminarUsuario(id);
            }
        });

    }
    function eliminarUsuario(id) {
        axios.get('<?= url('eliminar?id=') ?>' + id)
            .then(response => {
                if(response.data.errors){
                    const errorMessages = Object.values(response.data.errors).join('<br>');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        html: errorMessages
                    });
                    if (response.data.error === 'NO AUTORIZADO') {
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    }
                }else {
                    
                    Swal.fire({
                        title: "Borrado!",
                        text: "Tu usuario ha sido borrado.",
                        icon: "success"
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 1000)
                }

            })
            .catch(error => {
                console.error('Error:', error);
            });
    }


    /********************Logout*******************/

    function logout() {
        axios.get('<?= url('logout') ?>')
        setTimeout(function () {
            location.reload()
        }, 300)

    }
</script>

</html>