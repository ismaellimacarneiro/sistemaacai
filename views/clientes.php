<div class="container-fluid py-3">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-primary">
                        <i class="bi bi-person-plus me-2"></i><?= isset($clienteEditar) ? 'EDITAR CLIENTE' : 'NOVO CLIENTE' ?>
                    </h5>
                    
                    <form action="index.php?route=salvar-cliente" method="POST">
                        <input type="hidden" name="id" value="<?= $clienteEditar['id'] ?? '' ?>">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">NOME DO CLIENTE</label>
                            <input type="text" name="nome" class="form-control bg-light border-0 p-3 fw-bold" 
                                   value="<?= $clienteEditar['nome'] ?? '' ?>" 
                                   placeholder="Ex: ANA MARIA BRAGA" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">TELEFONE / WHATSAPP</label>
                            <input type="text" name="telefone" class="form-control bg-light border-0 p-3" 
                                   value="<?= $clienteEditar['telefone'] ?? '' ?>" 
                                   placeholder="(88) 99999-9999">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-3 shadow-sm">
                            <i class="bi bi-save me-2"></i><?= isset($clienteEditar) ? 'ATUALIZAR' : 'CADASTRAR' ?>
                        </button>
                        
                        <?php if(isset($clienteEditar)): ?>
                            <a href="index.php?route=clientes" class="btn btn-light w-100 mt-2 text-muted">Cancelar</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 text-muted text-uppercase">Clientes Cadastrados</h6>
                    
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th width="50">ID</th>
                                    <th>NOME</th>
                                    <th>TELEFONE</th>
                                    <th class="text-center">CONSUMO HOJE</th>
                                    <th class="text-center">AÇÕES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($listaClientes as $c): ?>
                                <tr class="border-bottom">
                                    <td class="text-muted small">#<?= $c['id'] ?></td>
                                    <td class="fw-bold text-uppercase"><?= $c['nome'] ?></td>
                                    <td class="text-muted"><?= $c['telefone'] ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info text-white fw-bold px-3" 
                                                style="border-radius: 8px;"
                                                onclick="verConsumo(<?= $c['id'] ?>, '<?= $c['nome'] ?>')"
                                                data-bs-toggle="modal" data-bs-target="#modalConsumo">
                                            <i class="bi bi-eye me-1"></i> ITENS
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <a href="index.php?route=clientes&id=<?= $c['id'] ?>" class="text-primary me-2">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <?php if($c['nome'] != 'CLIENTE'): ?>
                                        <a href="index.php?route=excluir-cliente&id=<?= $c['id'] ?>" class="text-danger" 
                                           onclick="return confirm('Excluir cliente?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConsumo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 15px;">
            <div class="modal-header bg-primary text-white border-0" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <h5 class="modal-title fw-bold">Consumo de <span id="modalNomeCliente"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="listaConsumo" class="mb-4">
                    <div class="text-center p-3 text-muted">Carregando...</div>
                </div>

                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <span class="fw-bold text-muted small uppercase">TOTAL HOJE</span>
                    <span class="fs-3 fw-bold text-success" id="totalConsumo">R$ 0,00</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Função para buscar o consumo via AJAX
 */
function verConsumo(id, nome) {
    document.getElementById('modalNomeCliente').innerText = nome.toUpperCase();
    const lista = document.getElementById('listaConsumo');
    const totalElement = document.getElementById('totalConsumo');
    
    // Limpa a lista e mostra que está carregando
    lista.innerHTML = '<div class="text-center p-3 text-muted"><div class="spinner-border spinner-border-sm me-2"></div>Buscando...</div>';
    totalElement.innerText = 'R$ 0,00';
    
    // Faz a chamada para a nova rota no index.php
    fetch(`index.php?route=get-consumo-cliente&id=${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Erro na rede');
            return response.json();
        })
        .then(data => {
            lista.innerHTML = '';
            let totalGeral = 0;

            if (data.length === 0) {
                lista.innerHTML = '<div class="alert alert-light text-center border-0">Nenhum consumo registrado hoje.</div>';
            } else {
                data.forEach(item => {
                    const valor = parseFloat(item.valor_total);
                    totalGeral += valor;

                    lista.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-light">
                            <div>
                                <div class="fw-bold small text-uppercase">${item.produto}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">${item.peso > 0 ? item.peso + ' qty/g' : '1 un'}</div>
                            </div>
                            <div class="fw-bold text-dark">
                                R$ ${valor.toLocaleString('pt-br', {minimumFractionDigits: 2})}
                            </div>
                        </div>
                    `;
                });
            }
            totalElement.innerText = 'R$ ' + totalGeral.toLocaleString('pt-br', {minimumFractionDigits: 2});
        })
        .catch(error => {
            console.error('Erro:', error);
            lista.innerHTML = '<div class="alert alert-danger text-center">Erro ao carregar dados. Verifique a conexão.</div>';
        });
}
</script>