<div class="wrap-inner col-group content">
	<div class="col-12 center">
		<h1 class="title center" style="text-transform:uppercase">Se connecter</h1>
		<h3 class="subtitle">Obligatoire avant de passer la commande</h3>
	</div>

	<form method="POST" action="" class="col-6 col-offset-3 login-form">
		<div class="input col-group<?= (!empty($errors)) ? ' error' : '' ?>">
			<label for="playername" class="col-4">Pseudonyme</label>
			<input type="text" class="col-8" name="playername" id="playername" placeholder="Pseudo sur le serveur" value="<?= ($playername != null) ? $playername : "" ?>" required>

			<?php if (isset($errors["playername"])): ?>
				<p class="error col-offset-4 col-8"><?= $errors["playername"]; ?></p>
			<?php endif; ?>
		</div>
		<div class="input col-group<?= (!empty($errors)) ? ' error' : '' ?>">
			<label for="password" class="col-4">Mot de passe</label>
			<input type="password" class="col-8" name="password" id="password" placeholder="Mot de passe de connexion" required>

			<?php if (isset($errors["password"])): ?>
				<p class="error col-offset-4 col-8"><?= $errors["password"]; ?></p>
			<?php endif; ?>
		</div>

		<div class="links col-group">
			<a href="#">Pas inscrit ?</a> &mdash;
			<a href="#">Mot de passe oublié ?</a>
		</div>

		<div class="input col-group">
			<button type="submit" class="col-offset-4 col-8">Se connecter</button>
		</div>
	</form>
</div>