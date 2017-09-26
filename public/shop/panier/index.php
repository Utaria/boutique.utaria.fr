<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style.css">
		<title>Basket</title>
	</head>
	<body>
		<div class="basket">
			<table>
				<thead>
					<tr>
						<th>Articles</th>
						<th>Quantité</th>
						<th>Prix</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<span class="booster">Booster</span>
							<a href="#">Booster (x3)</a>
						</td>
						<td>1</td>
						<td>5.00$</td>
					</tr>
					<tr>
						<td>
							<span class="rank">Grade</span>
							<a href="#">Grade Utarien</a>
						</td>
						<td>1</td>
						<td>7.00$</td>
					</tr>
					<tr>
						<td>
							<span class="booster">Booster</span>
							<a href="#">Grade VIP</a>
						</td>
						<td>1</td>
						<td>15.00$</td>
					</tr>
					<tr>
						<td>
							<span class="booster">Booster</span>
							<a href="#">Booster (x1)</a>
						</td>
						<td><input class="quantity" type="number" min="1" max="10"></td>
						<td>35.00$</td>
					</tr>

				</tbody>
			</table>
			<div class="total"><span class="txt">Total :</span> 65.00$</div>
			<div class="promotions">
				<input type="text" placeholder="Code Promo">
				<input type="submit" value="">
			</div>
			<input type="submit" class="check" value="Valider">
			<div class="links">
				<a href="#">Conditions de ventes</a>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="app.js"></script>
</html>