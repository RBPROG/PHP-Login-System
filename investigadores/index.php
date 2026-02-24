<?php

define('TITLE', "Investigadores");
include '../assets/layouts/header.php';
check_verified();


$sqlRoles = "SELECT RoleId, RoleNombre FROM roles ORDER BY RoleNombre ASC";
$resRoles = mysqli_query($conn, $sqlRoles);

// --- Obtener hospitales ---
$sqlHospitales = "
    SELECT HospitalId, Nombre, Codigo, XmlHospitalName, XmlUnitName, UltMod
    FROM hospitales
    ORDER BY Nombre ASC
";
$resHospitales = mysqli_query($conn, $sqlHospitales);
?>
<main role="main" class="container">

    <div class="row">
        <div class="col-sm-12">
			<div class="card mt-5">
				<div class="card-header p-3 mb-3 text-white bg-secondary">
					<img class="mr-3 svg" src="../assets/images/investigador.svg" alt="" width="48" height="48"> Nuevo investigador
				</div>
				<div class="card-body">
                    <form action="includes/register.inc.php" method="post" enctype="multipart/form-data">

                        <?php insert_csrf_token(); ?>

                        <div class="row">
                            <div class="col-12 text-center mb-3">
                                <small class="text-success font-weight-bold">
                                    <?php
                                        if (isset($_SESSION['STATUS']['signupstatus']))
                                            echo $_SESSION['STATUS']['signupstatus'];

                                    ?>
                                </small>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="first_name" class="sr-only">Nombre del investigador</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Nombre del investigador" required>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="username" class="sr-only">Usuario del investigador</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Usuario del investigador" required autofocus>
                                <sub class="text-danger">
                                    <?php
                                        if (isset($_SESSION['ERRORS']['usernameerror']))
                                            echo $_SESSION['ERRORS']['usernameerror'];

                                    ?>
                                </sub>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="password" class="sr-only">Contraseña del investigador</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña del investigador" required>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="confirmpassword" class="sr-only">Confirmar contraseña</label>
                                <input type="password" id="confirmpassword" name="confirmpassword" class="form-control" placeholder="Confirmar contraseña" required>
                                <sub class="text-danger mb-4">
                                    <?php
                                        if (isset($_SESSION['ERRORS']['passworderror']))
                                            echo $_SESSION['ERRORS']['passworderror'];

                                    ?>
                                </sub>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="email" class="sr-only">Email del investigador</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email del investigador" required autofocus>
                                <sub class="text-danger">
                                    <?php
                                        if (isset($_SESSION['ERRORS']['emailerror']))
                                            echo $_SESSION['ERRORS']['emailerror'];

                                    ?>
                                </sub>
                            </div>
                            <!-- Select de roles -->
                            <div class="col-sm-6 mb-3">
                                <label for="role" class="sr-only">Rol del usuario</label>
                                <select id="role" name="role" class="form-control" required>
                                    <option value="">Seleccione un rol...</option>
                                    <?php while ($row = mysqli_fetch_assoc($resRoles)): ?>
                                        <option value="<?= htmlspecialchars($row['RoleId']) ?>">
                                            <?= htmlspecialchars($row['RoleNombre']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Select de hospitales (inicialmente oculto) -->
                            <div class="col-sm-6 mb-3 align-items-center" id="hospital-container" style="display: none;">
                                <div class="flex-grow-1 me-3">
                                    <label for="hospital" class="sr-only">Hospital del investigador</label>
                                    <select id="hospital" name="hospital" class="form-control">
                                        <option value="">Seleccione un hospital...</option>
                                        <?php while ($row = mysqli_fetch_assoc($resHospitales)): ?>
                                            <option value="<?= htmlspecialchars($row['HospitalId']) ?>">
                                                <?= htmlspecialchars($row['Nombre']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <sub class="text-danger">
                                    <?php
                                        if (isset($_SESSION['ERRORS']['hospitalerror']))
                                            echo $_SESSION['ERRORS']['hospitalerror'];
                                    ?>
                                    </sub>
                                </div>

                                <div class="form-check ms-3 mt-3">
                                    <input type="checkbox" id="principal" name="principal" value="1" class="form-check-input">
                                    <label for="principal" class="form-check-label">Invesigador principal</label>
                                </div>
                            </div>
                                
                            </div>
					    </div>

                        <button class="btn btn-primary" type="submit" name='signupsubmit'>Guardar</button>

                    </form>
				</div>
			</div>
			
			<div class="card mt-5">
				<div class="card-header p-3 mb-3 text-white bg-secondary">
					<img class="mr-3 svg" src="../assets/images/investigador.svg" alt="" width="48" height="48"> Listado de investigadores
				</div>
				<div class="card-body">
					<table class="table table-bordered" id="hospitalTable">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nombre</th>
							<th>Código</th>
							<th>XML Hospital</th>
							<th>XML Unidad</th>
						</tr>
					</thead>
					<tbody>
						<!-- Aquí se cargarán los registros -->
					</tbody>
				</table>
				</div>
			</div>

        </div>
    </div>
</main>


<script src="../assets/js/jquery-3.7.1.js"></script>
<script src="../assets/js/dataTables.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const hospitalContainer = document.getElementById('hospital-container');
        const hospitalSelect = document.getElementById('hospital');
        const principalCheckbox = document.getElementById('principal');

        roleSelect.addEventListener('change', function() {
            const selected = this.value.toLowerCase().trim();

            if (selected === '2') {
                hospitalContainer.style.display = 'flex'; // mostrar ambos
                hospitalSelect.required = true;
            } else {
                hospitalContainer.style.display = 'none';
                hospitalSelect.required = false;
                hospitalSelect.value = '';
                principalCheckbox.checked = false;
            }
        });
    });

	$(document).ready(function () {
		var table = $('#hospitalTable').DataTable({
			"processing": true,
			"serverSide": true,
			"pageLength": 3, // 1 registro por página
			language: {
				url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
				emptyTable: "No hay resultados con los filtros actuales"
			},
			order: [[0, 'desc']],
			"ajax": "listar_investigadores.php",
			"columns": [
				{ "data": "HospitalId" },
				{ "data": "Nombre" },
				{ "data": "Codigo" },
				{ "data": "XmlHospitalName" },
				{ "data": "XmlUnitName" },
				{
					"data": null,
					"render": function (data, type, row) {
						return `
							<button class="btn btn-sm btn-warning editHospital" data-id="${row.HospitalId}">Editar</button>
							<button class="btn btn-sm btn-danger deleteHospital" data-id="${row.HospitalId}">Eliminar</button>
						`;
					},
					"orderable": false
				}
			]
		});
		
		$('#hospitalForm').submit(function(e) {
			e.preventDefault();

			if (confirm('¿Desea guardar este hospital?')) {
				let formData = $(this).serialize();

				let hospitalId = $('#hospitalForm').data('hospitalId') || '';
				let url = hospitalId ? 'actualizar_investigador.php' : 'guardar_hospital.php';

				$.ajax({
					url: url,
					method: 'POST',
					data: $(this).serialize() + (hospitalId ? '&HospitalId=' + hospitalId : ''),
					dataType: 'json',
					success: function(response) {
						if (response.success) {
							alert(hospitalId ? 'Hospital actualizado correctamente.' : 'Hospital guardado correctamente.');
							$('#hospitalForm')[0].reset();
							$('#hospitalForm').removeData('hospitalId'); // Limpiar ID después de actualizar
							table.ajax.reload(null, false);
						} else {
							alert('Error: ' + response.error);
						}
					},
					error: function(xhr) {
						console.log(xhr.responseText);
						alert('Error al guardar el hospital.');
					}
				});
			}
		});
		
		$('#hospitalTable tbody').on('click', '.deleteHospital', function() {
			let hospitalId = $(this).data('id');

			if (confirm('¿Está seguro que desea eliminar este hospital?')) {
				$.ajax({
					url: 'eliminar_investigador.php',
					method: 'POST',
					data: { HospitalId: hospitalId },
					dataType: 'json',
					success: function(response) {
						if (response.success) {
							alert('Hospital eliminado correctamente.');
							$('#hospitalTable').DataTable().ajax.reload(null, false);
						} else {
							alert('Error al eliminar el hospital: ' + response.error);
						}
					},
					error: function(xhr) {
						console.log(xhr.responseText);
						alert('Error al eliminar el hospital.');
					}
				});
			}
		});

		
		$('#hospitalTable tbody').on('click', '.editHospital', function() {
			let hospitalId = $(this).data('id');

			$.ajax({
				url: 'obtener_investigador.php',
				method: 'GET',
				data: { id: hospitalId },
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						// Llenar formulario con los datos
						$('#Nombre').val(response.data.Nombre);
						$('#Codigo').val(response.data.Codigo);
						$('#XmlHospitalName').val(response.data.XmlHospitalName);
						$('#XmlUnitName').val(response.data.XmlUnitName);

						// Opcional: guardar ID del hospital para el submit
						$('#hospitalForm').data('hospitalId', hospitalId);
					} else {
						alert('Error al cargar los datos del hospital.');
					}
				},
				error: function(xhr) {
					console.log(xhr.responseText);
					alert('Error al obtener el hospital.');
				}
			});
		});

	});


</script>

<div class="container">
    <div class="row">
        <div class="col-md-4">

        </div>
        <div class="col-lg-4">

            

        </div>
        <div class="col-md-4">

        </div>
    </div>
</div>



<?php

include '../assets/layouts/footer.php'

?>

<script type="text/javascript">
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);

            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#avatar").change(function() {
        console.log("here");
        readURL(this);
    });
</script>