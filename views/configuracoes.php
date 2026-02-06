<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-800 m-0">Configurações do Sistema</h3>
        <p class="text-muted small">Gerencie as informações da sua loja e do sistema.</p>
    </div>
    <i class="bi bi-gear-wide-connected fs-1 text-primary opacity-25"></i>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Dados da Loja</h5>
                
                <?php if(isset($_GET['sucesso'])): ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Configurações salvas com sucesso!
                    </div>
                <?php endif; ?>

                <form action="index.php?route=configuracoes" method="POST">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted">Nome da Empresa</label>
                            <input type="text" name="nome" class="form-control form-control-lg bg-light border-0" value="<?= $dadosLoja['nome'] ?? 'Mais Açaí' ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Telefone / WhatsApp</label>
                            <input type="text" name="telefone" class="form-control form-control-lg bg-light border-0" value="<?= $dadosLoja['telefone'] ?? '' ?>" placeholder="(00) 00000-0000">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Site / Link Instagram</label>
                            <input type="text" name="site" class="form-control form-control-lg bg-light border-0" value="<?= $dadosLoja['site'] ?? '' ?>" placeholder="www.seusite.com.br">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted">Endereço Completo</label>
                            <textarea name="endereco" class="form-control form-control-lg bg-light border-0" rows="3"><?= $dadosLoja['endereco'] ?? '' ?></textarea>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow">
                                <i class="bi bi-save2 me-2"></i> Salvar Alterações
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-dark text-white p-4">
            <h6 class="fw-bold mb-3">Informações do Sistema</h6>
            <hr class="opacity-10">
            <div class="d-flex justify-content-between mb-2">
                <span class="small text-muted">Versão:</span>
                <span class="small fw-bold">v2.4.0</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="small text-muted">Último Fechamento:</span>
                <span class="small fw-bold"><?= date('d/m/Y') ?></span>
            </div>
            <div class="d-flex justify-content-between mb-4">
                <span class="small text-muted">Banco de Dados:</span>
                <span class="small fw-bold text-success">Conectado</span>
            </div>
            
            <button class="btn btn-outline-light btn-sm w-100 rounded-pill mb-2">Limpar Cache</button>
            <button class="btn btn-outline-danger btn-sm w-100 rounded-pill">Backup de Dados</button>
        </div>
    </div>
</div>