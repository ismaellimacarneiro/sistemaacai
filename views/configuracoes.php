<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-gear-fill text-primary fs-4 me-2"></i>
                        <h5 class="fw-bold mb-0 text-dark">Configurações do Sistema</h5>
                    </div>
                    
                    <?php if(isset($_GET['success'])): ?>
                        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div class="small fw-bold">Alterações salvas com sucesso!</div>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?route=salvar-configuracao" method="POST">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label small fw-bold text-muted">NOME DA EMPRESA</label>
                                <input type="text" name="nome_loja" class="form-control bg-light border-0 p-3 fw-bold" value="<?= $dadosLoja['nome_loja'] ?? '' ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">TELEFONE</label>
                                <input type="text" name="telefone" class="form-control bg-light border-0 p-3" value="<?= $dadosLoja['telefone'] ?? '' ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">SITE / INSTAGRAM</label>
                                <input type="text" name="site" class="form-control bg-light border-0 p-3" value="<?= $dadosLoja['site'] ?? '' ?>">
                            </div>
                            
                            <div class="col-md-12 mb-4">
                                <label class="form-label small fw-bold text-muted">ENDEREÇO</label>
                                <textarea name="endereco" class="form-control bg-light border-0 p-3" rows="3"><?= $dadosLoja['endereco'] ?? '' ?></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary fw-bold px-5 py-3 shadow-sm" style="border-radius: 10px;">
                            <i class="bi bi-save2 me-2"></i> SALVAR ALTERAÇÕES
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-dark text-white" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Informações do Sistema <i class="bi bi-cpu ms-2 text-muted"></i></h6>
                    
                    <div class="bg-white bg-opacity-10 p-3 rounded-3 mb-4 small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-white-50">Versão</span> <span>v2.4.0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-white-50">Status</span> <span class="text-success fw-bold">Online</span>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <a href="index.php?route=limpar-cache" class="btn btn-outline-light border-secondary btn-sm py-2" onclick="return confirm('Encerrar sessão?')">
                            <i class="bi bi-trash3 me-1"></i> Limpar Cache
                        </a>
                        <a href="index.php?route=backup-dados" class="btn btn-outline-danger btn-sm py-2">
                            <i class="bi bi-cloud-download me-1"></i> Backup de Dados
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>