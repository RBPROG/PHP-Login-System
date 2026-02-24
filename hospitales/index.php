<?php

define('TITLE', "Hospitales");
include '../assets/layouts/header.php';
check_verified();

?>


<main role="main" class="container">

    <div class="row">
        <div class="col-sm-12">
			<div class="card mt-5">
				<div class="card-header p-3 mb-3 text-white bg-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><path fill="#FFFFFF" d="M21.5 6.5h-3v-4c0-.6-.4-1-1-1h-11c-.6 0-1 .4-1 1v4h-3c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h19c.6 0 1-.4 1-1v-14c0-.6-.4-1-1-1zm-14 12h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1zm0-4h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1zm5 4h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1zm0-4h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1zm1-5.5H13v.5c0 .6-.4 1-1 1s-1-.4-1-1V9h-.5c-.6 0-1-.4-1-1s.4-1 1-1h.5v-.5c0-.6.4-1 1-1s1 .4 1 1V7h.5c.6 0 1 .4 1 1s-.4 1-1 1zm4 9.5h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1zm0-4h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1z"/></svg> Nuevo hospital
				</div>
				<div class="card-body">
					<form id="hospitalForm">
					<div class="row">
						<div class="col-sm-12 mb-3">
							<label for="Nombre" class="form-label">Nombre</label>
							<input type="text" class="form-control" id="Nombre" name="Nombre" required>
						</div>
						<div class="mb-3 col-sm-4">
							<label for="Codigo" class="form-label">Código</label>
							<input type="text" class="form-control" id="Codigo" name="Codigo" maxlength="7">
						</div>
						<div class="mb-3 col-sm-4">
							<label for="XmlHospitalName" class="form-label">Nombre XML Hospital</label>
							<input type="text" class="form-control" id="XmlHospitalName" name="XmlHospitalName" maxlength="50">
						</div>
						<div class="mb-3 col-sm-4">
							<label for="XmlUnitName" class="form-label">Nombre XML Unidad</label>
							<input type="text" class="form-control" id="XmlUnitName" name="XmlUnitName" maxlength="50">
						</div>
						<div class="col-sm-12">
							<button type="submit" class="btn btn-primary">Guardar</button>
						</div>
					</form>
					</div>
				</div>
			</div>
			
			<div class="card mt-5">
				<div class="card-header p-3 mb-3 text-white bg-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><path fill="#FFFFFF" d="M21.5 6.5h-3v-4c0-.6-.4-1-1-1h-11c-.6 0-1 .4-1 1v4h-3c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h19c.6 0 1-.4 1-1v-14c0-.6-.4-1-1-1zm-14 12h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1zm0-4h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1zm5 4h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1zm0-4h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1zm1-5.5H13v.5c0 .6-.4 1-1 1s-1-.4-1-1V9h-.5c-.6 0-1-.4-1-1s.4-1 1-1h.5v-.5c0-.6.4-1 1-1s1 .4 1 1V7h.5c.6 0 1 .4 1 1s-.4 1-1 1zm4 9.5h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1zm0-4h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c.6 0 1 .4 1 1s-.4 1-1 1z"/></svg> Listado de hospitales
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
			"ajax": "listar_hospitales.php",
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
				let url = hospitalId ? 'actualizar_hospital.php' : 'guardar_hospital.php';

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
					url: 'eliminar_hospital.php',
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
				url: 'obtener_hospital.php',
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


    <?php

    include '../assets/layouts/footer.php'

    ?>