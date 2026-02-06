<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mais Açaí - Sistema de Gestão</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root { --primary-purple: #6f42c1; --dark-sidebar: #1e1e2d; }
        body { font-family: 'Inter', sans-serif; background-color: #f4f5f7; margin: 0; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar { min-height: 100vh; background: var(--dark-sidebar); color: white; width: 250px; position: fixed; z-index: 1000; transition: 0.3s; }
        .sidebar-brand { padding: 30px 20px; text-align: center; font-weight: 800; color: var(--primary-purple); letter-spacing: 1px; }
        .nav-link { color: #a2a3b7; padding: 12px 25px; margin: 5px 15px; border-radius: 10px; transition: 0.3s; font-weight: 500; display: flex; align-items: center; }
        .nav-link i { font-size: 1.2rem; margin-right: 15px; }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.08); }
        .nav-link.active { background: var(--primary-purple); color: white; }
        
        /* Content Area */
        .main-content { margin-left: 250px; padding: 40px; min-height: 100vh; transition: 0.3s; }
        .card { border: none; border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .stat-card { padding: 25px; border-left: 6px solid; }

        @media (max-width: 992px) {
            .sidebar { width: 80px; }
            .sidebar span, .sidebar-brand { display: none; }
            .main-content { margin-left: 80px; }
        }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column">
    <div class="sidebar-brand"><h4>MAIS AÇAÍ</h4></div>
    
    <nav class="nav flex-column">
        <a href="index.php?route=home" class="nav-link <?= !isset($paginaInterna) ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>
        <a href="index.php?route=vendas" class="nav-link <?= ($paginaInterna ?? '') == 'vendas.php' ? 'active' : '' ?>">
            <i class="bi bi-cart3"></i> <span>PDV / Vendas</span>
        </a>
        <a href="index.php?route=clientes" class="nav-link <?= ($paginaInterna ?? '') == 'clientes.php' ? 'active' : '' ?>">
            <i class="bi bi-people"></i> <span>Clientes</span>
        </a>
        <a href="index.php?route=produtos" class="nav-link <?= ($paginaInterna ?? '') == 'produtos.php' ? 'active' : '' ?>">
            <i class="bi bi-box-seam"></i> <span>Produtos</span>
        </a>
        <a href="index.php?route=configuracoes" class="nav-link <?= ($paginaInterna ?? '') == 'configuracoes.php' ? 'active' : '' ?>">
            <i class="bi bi-sliders"></i> <span>Configurações</span>
        </a>
        <div class="mt-auto mb-4">
            <a href="index.php?route=logout" class="nav-link text-danger">
                <i class="bi bi-power"></i> <span>Sair</span>
            </a>
        </div>
    </nav>
</div>

<div class="main-content">
    <?php if (isset($paginaInterna)): ?>
        <div class="card p-4">
            <?php include $paginaInterna; ?>
        </div>
    <?php else: ?>
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-800 m-0">Dashboard</h2>
                <p class="text-muted">Visão geral do Mais Açaí</p>
            </div>
            <div class="text-end">
                <span class="badge bg-white text-dark shadow-sm p-3 border rounded-pill">
                    <i class="bi bi-calendar3 text-primary me-2"></i><?= date('d/m/Y') ?>
                </span>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card stat-card border-success h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted fw-bold">VENDAS HOJE</small>
                            <h2 class="fw-800 mt-2 mb-0">R$ <?= number_format($totalVendasHoje ?? 0, 2, ',', '.') ?></h2>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill p-2">
                                <i class="bi bi-cart-check-fill me-1"></i> <?= count($vendasHoje ?? []) ?> pedidos
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card stat-card border-primary h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted fw-bold">FECHAMENTO MÊS</small>
                            <h2 class="fw-800 mt-2 mb-0">R$ <?= number_format($totalMes ?? 0, 2, ',', '.') ?></h2>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill p-2">
                                <i class="bi bi-calendar-check-fill me-1"></i> <?= $totalPedidosMes ?? 0 ?> pedidos
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card p-4">
                    <h6 class="fw-bold mb-4">Faturamento Mensal (<?= date('Y') ?>)</h6>
                    <div style="height: 350px;">
                        <canvas id="graficoVendas"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Últimas do Dia</h6>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <tbody>
                                <?php if(empty($vendasHoje)): ?>
                                    <tr><td class="text-center py-4 text-muted small">Sem vendas hoje.</td></tr>
                                <?php else: ?>
                                    <?php foreach(array_slice($vendasHoje, 0, 7) as $v): ?>
                                    <tr>
                                        <td><small class="fw-bold text-muted"><?= date('H:i', strtotime($v['data_venda'])) ?></small></td>
                                        <td class="small fw-bold"><?= $v['cliente'] ?></td>
                                        <td class="text-end fw-bold text-success">R$ <?= number_format($v['valor_total'], 2, ',', '.') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('graficoVendas');
            const meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            const valores = [0,0,0,0,0,0,0,0,0,0,0,0];
            
            <?php if(!empty($dadosGrafico)): foreach($dadosGrafico as $d): ?>
                valores[<?= (int)$d['mes'] - 1 ?>] = <?= (float)$d['total'] ?>;
            <?php endforeach; endif; ?>

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Vendas R$',
                        data: valores,
                        backgroundColor: '#6f42c1',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        </script>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>