<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?= titulek() ?></title>
</head>
<body>
<script src="./js/ajax.js"></script>
<div class="hlavicka">
	<p>AJAXová aplikace</p>
</div>

<div class="menu">
	<?= menu(array("", "cenik", "kontakt")) ?>
</div>

<div class="obsah" id="obsah">
	<?= obsah() ?>
</div>

<div class="paticka">
	2014, Je čas
</div>

<script>
	Stranka.pripojitOdkazy(document.body);
</script>
</body>
</html>
