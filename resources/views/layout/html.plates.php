<!DOCTYPE html>
<html>
<head>
  <title><?= $this->e($title ?? '') ?></title>

  <!-- materialize.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body>

  <div class="container">

    <?=$this->section('content')?>

  </div>

</body>
</html>
