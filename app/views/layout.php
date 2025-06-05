<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? $title : 'ERP' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">ERP</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="/app/home/urunler/">Ürünler</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/app/home/customers/">Müşteriler</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/app/home/projects/">Projeler</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/app/home/sales/">Satış</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/app/home/purchase/">Satınalma</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container py-4">
<?= $content ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
