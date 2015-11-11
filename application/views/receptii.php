<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/favicon.ico">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/styles.css">
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/receptii.js"></script>
    <title>Recepţii</title>
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
					<li class="active"><a href="#">Recepţii</a></li>
					<li><a href="aviz.html">Aviz de Predare</a></li>
				</ul>
				<div class="navbar-form navbar-left">
					<div class="dropdown">
						<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                            Adaugă Recepţie <span class="caret"></span>
                        </button>
						<ul class="dropdown-menu">
							<li><a href="#" id="receptie9">Recepţie TVA 9%</a></li>
							<li><a href="#" id="receptie24">Recepţie TVA 24%</a></li>
						</ul>
					</div>
				</div>
                <div class="navbar-form navbar-left hidden">
                    <div class="label-page-info">
                        <p>
                            <span class="label label-primary">Rânduri Rămase:
                                <span id="randuri-ramase">0</span>
                            </span>
                        </p>
                    </div>
                </div>
			</div>
		</div>
	</nav>
  <table style="display: none;">
      <tbody>
          <tr>
              <td id="template-input">
                  <input type="text" class="form-control usr-input">
              </td>
          </tr>
      </tbody>
  </table>

	<div id="receptii" class="container empty-message">
        <br />
        <div id="message" class="alert alert-info text-center" role="alert">
            <h4>Adăugaţi o recepţie folosind butonul "Adaugă Recepţie" de mai sus.</h4>
        </div>
		<div id="template_receptie" class="receptie">
            <table class="table table-bordered text-center">
                <tbody>
                    <tr class="receptie-header">
                        <td class="bold">DATA</td>
                        <td class="bold">NOTA DE Recepţie NR.</td>
                        <td rowspan="2">
                            <h4 class="bold">SC LICA SRL<br />Recepţie <span class="procentaj_tva">0</span>%</h4>
                        </td>
                        <td class="bold">Furnizor</td>
                        <td class="bold">Nr. Facturii sau Avizului</td>
                        <td class="bold action">Acţiune</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control usr-input button"></td>
                        <td><input type="text" class="form-control usr-input button"></td>
                        <td><input type="text" class="form-control usr-input button"></td>
                        <td><input type="text" class="form-control usr-input button"></td>
                        <td class="action">
                            <button class="btn btn-primary btn-xs hidden action-edit-header">
                                <span class="glyphicon glyphicon-pencil"></span> Editează
                            </button>
                            <button class="btn btn-success btn-sm action-accept-header">
                                <span class="glyphicon glyphicon-ok"></span> Salvează
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered table-hover text-center pullup">
                <thead>
                    <tr>
                        <th>Denumirea Mărfii</th>
                        <th>U/M</th>
                        <th>Cantitatea col. 3</th>
                        <th>Preţ Furnizor col. 4</th>
                        <th>Valoare Furnizor col. 5 (3x4)</th>
                        <th>Preţ cu Adaos Practicat col. 8</th>
                        <th>Valoare la Preţ cu Adaos col. 9 (3x4)</th>
                        <th>Preţ cu Adaos şi TVA col. 10</th>
                        <th>Valoare la Preţ cu Adaos şi TVA col. 11 (3x10)</th>
                        <th class="action">Acţiune</th>
                    </tr>
                </thead>
                <tbody>

                    <tr class="button">
                        <td><input type="text" class="form-control usr-input"></td>
                        <td><input type="text" class="form-control usr-input"></td>
                        <td class="currency"><input type="text" class="form-control usr-input"></td>
                        <td class="currency"><input type="text" class="form-control usr-input"></td>
                        <td class="val-furnizor"></td>
                        <td class="pret-adaos"></td>
                        <td class="val-adaos"></td>
                        <td class="currency"><input type="text" class="form-control usr-input"></td>
                        <td class="val-pret"></td>
                        <td class="action">
                            <button class="btn btn-primary btn-xs hidden">
                                <span class="glyphicon glyphicon-pencil"></span> Editează
                            </button>
                            <button class="btn btn-success btn-sm">
                                <span class="glyphicon glyphicon-ok"></span> Salvează
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="bold">Total</td>
                        <td class="total5 bold bigfont">0.00</td>
                        <td class="bold"></td>
                        <td class="total9 bold bigfont">0.00</td>
                        <td class="bold"></td>
                        <td class="total11 bold bigfont">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="8" class="bold">Valoare Marfă Furnizor (col. 5)</td>
                        <td class="total5 bold bigfont">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="8" class="bold">Valoare Adaos Comercial (9 - 5)</td>
                        <td class="total-adaos bold bigfont">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="8" class="bold">Valoare TVA Neexigibilă (11 - 9)</td>
                        <td class="total-tva bold bigfont">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="8" class="bold">TOTAL VALOARE AMĂNUNT</td>
                        <td class="total-receptie bold bigfont">0.00</td>
                    </tr>
                </tbody>
            </table>
		</div>
		<div id="end_of_receptii" class="text-center hidden">
			<button class="btn btn-success print" onClick="window.print()">
                <span class="glyphicon glyphicon-print"></span> Printează
            </button>
		</div>
		<br />
	</div>
</body>
</html>
