<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/styles.css">
    <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/moment-with-locales.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js"></script>
    <title>Registru de Casă</title>
    <script>
        serverData= <?php echo $server_data ?>;
        URLRoot= '<?php echo base_url(); ?>';
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/registru.js"></script>
</head>
<body>
    <div class="hidden">
        <datalist id="furnizori_MarfaTVA9"></datalist>
        <datalist id="furnizori_MarfaTVA24"></datalist>
        <datalist id="furnizori_Cheltuieli"></datalist>
    </div>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Lica SRL</a>
			</div>
			<div>
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">Registru de Casă</a></li>
					<li><a href="receptii.html">Recepţii</a></li>
				</ul>
				<div class="navbar-form navbar-left">
				    <div class="form-group widget">
			            <div class="input-group date" id="datetimepicker1">
			                <input class="form-control calendar" placeholder="Selectează Registru" type="text">
			                <span class="input-group-addon">
			                    <span class="glyphicon glyphicon-calendar"></span>
			                </span>
			            </div>
			        </div>
				</div>
			</div>
		</div>
	</nav>

	<div class="container">
		<h3 class="text-center">SC LICA SRL Registru de Casă din Data <span id="datatitlu"></span></h3>
		<table id="table_cumuli" class="table table-bordered table-hover text-center">
			<thead>
				<tr>
					<th>Sold Iniţial</th>
					<th>Total Utilităţi / Taxe / Contribuţii</th>
					<th>Chitanţe Cumulate 24%</th>
					<th>Chitanţe Cumulate 9%</th>
					<th>Monetare / Aport Capital Cumulate</th>
				</tr>
			</thead>
			<tbody>
				<tr id="cumuli">
					<td id="sold_initial">99999.99</td>
					<td>99999.99</td>
					<td>99999.99</td>
					<td>99999.99</td>
					<td>99999.99</td>
				</tr>
			</tbody>
		</table>

		<table id="tabel_MarfaTVA9" class="table table-bordered table-hover text-center">
			<thead>
				<tr>
					<th>Furnizor Marfă TVA 9%</th>
					<th>Număr Factură</th>
					<th>Număr Chitanţă</th>
					<th>Valoare Chitanţă 9%</th>
					<th class="action">Acţiune</th>
				</tr>
			</thead>
			<tbody>
				<tr class="template_row">
					<td>Agrocomplex SRL</td>
					<td>AB12345678</td>
					<td>AB12345678</td>
					<td>99999.00</td>
					<td class="action" title="Editează">
                        <button class="btn btn-primary btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> Editează
                        </button>
                    </td>
				</tr>
				<tr class="input_row screen">
					<td><input class="form-control usr-input" type="text" list="furnizori_MarfaTVA9"></td>
					<td><input class="form-control usr-input" type="text"></td>
					<td><input class="form-control usr-input" type="text"></td>
					<td><input class="form-control usr-input" type="text"></td>
					<td>
                        <button class="btn btn-success btn-sm">
                            <span class="glyphicon glyphicon-plus"></span> Salvează
                        </button>
                    </td>
				</tr>
				<tr class="totals_row">
					<td colspan="3" class="bold">Total</td>
					<td id="total_cheltuieli" class="bold">99999.99</td>
				</tr>
			</tbody>
		</table>

		<table id="tabel_MarfaTVA24" class="table table-bordered table-hover text-center">
			<thead>
				<tr>
					<th>Furnizor Marfă TVA 24%</th>
					<th>Număr Factură</th>
					<th>Număr Chitanţă</th>
					<th>Valoare Chitanţă 24%</th>
					<th class="action">Acţiune</th>
				</tr>
			</thead>
			<tbody>
				<tr class="template_row">
					<td>Agrocomplex SRL</td>
					<td>AB12345678</td>
					<td>AB12345678</td>
					<td>99999.00</td>
					<td class="action" title="Editează">
                        <button class="btn btn-primary btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> Editează
                        </button>
                    </td>
				</tr>
				<tr class="input_row screen">
					<td><input class="form-control usr-input" type="text"></td>
					<td><input class="form-control usr-input" type="text"></td>
					<td><input class="form-control usr-input" type="text"></td>
					<td><input class="form-control usr-input" type="text" list="furnizori_MarfaTVA24"></td>
					<td>
                        <button class="btn btn-success btn-sm">
                            <span class="glyphicon glyphicon-plus"></span> Salvează
                        </button>
                    </td>
				</tr>
				<tr class="totals_row">
					<td colspan="3"><b>Total</b></td>
					<td><b>99999.99</b></td>
				</tr>
			</tbody>
		</table>

		<table id="tabel_Cheltuieli" class="table table-bordered table-hover text-center">
			<thead>
				<tr>
					<th>Furnizor Utilităţi / Taxe</th>
					<th>Număr Factură</th>
					<th>Număr Chitanţă</th>
					<th>Valoare Chitanţă</th>
					<th class="action">Acţiune</th>
				</tr>
			</thead>
			<tbody>
				<tr class="template_row">
					<td>Agrocomplex SRL</td>
					<td>AB12345678</td>
					<td>AB12345678</td>
					<td>99999.00</td>
					<td class="action" title="Editeaza">
                        <button class="btn btn-primary btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> Editează
                        </button>
                    </td>
				</tr>
				<tr class="input_row screen">
					<td><input class="form-control usr-input" type="text"></td>
					<td><input class="form-control usr-input" type="text"></td>
					<td><input class="form-control usr-input" type="text"></td>
					<td><input class="form-control usr-input" type="text" list="furnizori_Cheltuieli"></td>
					<td><button class="btn btn-success btn-sm">
                            <span class="glyphicon glyphicon-plus"></span> Salvează
                        </button>
                    </td>
				</tr>
				<tr class="totals_row">
					<td colspan="3" class="total"><b>Total</b></td>
					<td class="total">99999.99</td>
				</tr>
			</tbody>
		</table>

		<table id="tabel_Aport" class="table table-bordered table-hover text-center">
			<tbody>
				<tr>
                    <td class="template_cell display_cell"></td>
					<td>Monetare / Aport Capital</td>
					<td class="input_cell"><input id="input_aport" class="form-control screen usr-input" type="text"></td>
					<td>Sold Total Încasări</td>
					<td id="total_Aport">99999.99</td>
					<td class="screen action">
                        <button id="new_aport" class="btn btn-success btn-sm">
                            <span class="glyphicon glyphicon-plus"></span> Salvează
                        </button>
                    </td>
				</tr>
			</tbody>
		</table>

		<table class="table table-bordered table-hover text-center">
			<thead><tr><th>Sold Total Plăţi</th><th>Sold Curent</th><th>Sold Final</th></tr></thead>
			<tbody>
                <tr>
                    <td id="total_plati">99999.99</td>
                    <td id="total_sold_curent">99999.99</td>
                    <td id="total_sold_final">99999.99</td>
                </tr>
            </tbody>
		</table>
		<div class="text-center">
			<button id="end_day" class="btn btn-primary save">
                <span class="glyphicon glyphicon-ok"></span> Salvează Registru
            </button>
		</div>
		<br>
	</div>


    <div class="modal fade" id="sold_initial_popup" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="exampleModalLabel">Modifică soldul iniţial pe luna curentă</h4>
                </div>
                <div class="modal-body">
                    <form class="form-inline">
                        <div class="form-group" style="margin-left: auto; margin-right: auto">
                            <label for="recipient-name" class="control-label">Sold iniţial luna curentă:</label>
                            <input type="text" name="edit_sold_initial" class="form-control" id="edit_sold_initial">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <form class="form-inline">
                        <button type="button" class="btn btn-default" style="width:10em" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" style="width:10em" id="request_new_sold_initial">Actualizează!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


	<footer class="page-space"><p>Copyright © 2015 SC LICA SRL. Toate drepturile rezervate.</p></footer>
</body>
</html>