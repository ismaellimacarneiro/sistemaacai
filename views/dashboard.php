<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-speedometer2 text-primary fs-4 me-2"></i>
        <h5 class="fw-bold mb-0 text-dark">Painel de Controle</h5>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm bg-primary text-white" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 small fw-bold mb-1 text-uppercase">Vendas Hoje</h6>
                            <h3 class="fw-bold mb-0">R$ <?= number_format($totaisDia['valor'] ?? 0, 2, ',', '.') ?></h3>
                            <p class="mb-0 small opacity-75"><?= $totaisDia['quantidade'] ?? 0 ?> pedidos</p>
                        </div>
                        <div class="fs-1 opacity-25">
                            <i class="bi bi-cart-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm bg-success text-white" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 small fw-bold mb-1 text-uppercase">Vendas do Mês</h6>
                            <h3 class="fw-bold mb-0">R$ <?= number_format($totaisMes['valor'] ?? 0, 2, ',', '.') ?></h3>
                            <p class="mb-0 small opacity-75"><?= $totaisMes['quantidade'] ?? 0 ?> pedidos</p>
                        </div>
                        <div class="fs-1 opacity-25">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <h6 class="fw-bold text-muted mb-1">Bem-vindo, Ismael!</h6>
                <p class="text-muted small mb-0">O sistema está pronto para monitorar suas vendas de açaí.</p>
            </div>
        </div>
    </div>
</div>