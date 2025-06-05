<?php
require_once __DIR__ . '/../../config/db.php';
$title = 'Ana Sayfa';
ob_start();
?>
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-center text-center">
                <h5 class="card-title">Ürünler</h5>
                <p class="card-text">Ürün yönetimi işlemlerinizi buradan gerçekleştirin.</p>
                <a href="urunler/" class="btn btn-primary mt-auto">Ürünlere Git</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-center text-center">
                <h5 class="card-title">Müşteriler</h5>
                <p class="card-text">Müşteri kayıtlarını yönetmek için tıklayın.</p>
                <a href="customers/" class="btn btn-primary mt-auto">Müşterilere Git</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-center text-center">
                <h5 class="card-title">Projeler</h5>
                <p class="card-text">Projelerinizi yönetin ve takip edin.</p>
                <a href="projects/" class="btn btn-primary mt-auto">Projelere Git</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-center text-center">
                <h5 class="card-title">Satış Yönetimi</h5>
                <p class="card-text">Teklif, sipariş, irsaliye ve fatura işlemleri.</p>
                <a href="sales/" class="btn btn-primary mt-auto">Satış Modülü</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-center text-center">
                <h5 class="card-title">Satınalma Yönetimi</h5>
                <p class="card-text">Teklif, sipariş ve fatura süreçleri.</p>
                <a href="purchase/" class="btn btn-primary mt-auto">Satınalma Modülü</a>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layout.php';
