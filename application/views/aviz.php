<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/favicon.ico">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/styles.css">
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/aviz.js"></script>
    <title>Aviz de Predare</title>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Lica SRL</a>
			</div>
			<div>
				<ul class="nav navbar-nav">
					<li><a href="http://lica.srl">Registru de Casă</a></li>
					<li><a href="Recepţii.html">Recepţii</a></li>
					<li class="active"><a href="#">Aviz de Predare</a></li>
				</ul>
			</div>
		</div>
	</nav>
    <div class="container">
        <br />
		<table class="table table-bordered text-center">
			<tbody>
				<tr>
					<td class="bold">DATA</td>
					<td rowspan="2" class="bold">
                        <h4 class="bold">SC LICA SRL<br />Aviz Intern de Predare Primire</h4>
                    </td>
					<td class="bold">Numărul Avizului</td>
					<td class="bold action" style="width: 115px;">Acţiune</td>
				</tr>
				<tr id="header-data-row">
					<td class="bigfont bold">
                        <input type="text" class="form-control usr-input button" value="10.10.2015">
                    </td>
					<td class="bigfont bold">
                        <input type="text" class="form-control usr-input button" value="155">
                    </td>
					<td class="action">
						<button class="btn btn-success btn-sm">
							<span class="glyphicon glyphicon-ok"></span> Salvează
						</button>
						<button class="btn btn-primary btn-sm hidden">
							<span class="glyphicon glyphicon-pencil"></span> Editează
						</button>
					</td>
				</tr>
			</tbody>
		</table>
        <table class="table table-bordered text-center pullup">
            <thead>
                <tr>
                    <th>Furnizor</th>
                    <th>Denumirea Mărfii</th>
                    <th>U/M</th>
                    <th>Cantitatea Predată</th>
                    <th>Preţ Vânzare</th>
                    <th>Valoarea</th>
                    <th class="action">Acţiune</th>
                </tr>
            </thead>
            <tbody id="avize">
                <tr class="button input-row">
                    <td><input type="text" class="form-control usr-input"></td>
                    <td><input type="text" class="form-control usr-input"></td>
                    <td class="currency"><input type="text" class="form-control usr-input"></td>
                    <td class="currency"><input type="text" class="form-control usr-input"></td>
                    <td class="currency"><input type="text" class="form-control usr-input"></td>
                    <td class="currency"><input type="text" class="form-control usr-input"></td>
                    <td class="action">
                        <button class="btn btn-primary btn-xs hidden">
                            <span class="glyphicon glyphicon-pencil"></span> Editează
                        </button>
                        <button class="btn btn-success btn-sm">
                            <span class="glyphicon glyphicon-ok"></span> Salvează
                        </button>
                    </td>
                </tr>
                <tr class="subtotal-row hidden">
                    <td colspan="2" class="subtotal-border-right"></td>
                    <td colspan="3" class="bold subtotal-border-left">Subtotal</td>
                    <td class="bold">99999.00</td>
                </tr>
                <tr id="total-row" class="big-row">
                    <td class="bold">Ştampila Unităţii</td>
                    <td class="bold">Semnătura de Primire</td>
                    <td colspan="3" class="bold">Total</td>
                    <td id="total" class="bold bigfont">99999.99</td>
                </tr>
            </tbody>
        </table>

        <div class="text-center">
            <button class="btn btn-primary print" onClick="window.print()">
                <span class="glyphicon glyphicon-print"></span> Printează
            </button>
        </div>
        <br />
    </div>
</body>
</html>
