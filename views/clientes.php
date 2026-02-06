<div class="card shadow border-0 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0 text-primary"><i class="bi bi-people-fill"></i> Clientes</h4>
        <button class="btn btn-primary btn-sm fw-bold shadow-sm" data-bs-toggle="collapse" data-bs-target="#formCliente">
            <i class="bi bi-plus-lg"></i> NOVO CLIENTE
        </button>
    </div>

    <div class="collapse mb-4 <?= isset($clienteEditar) ? 'show' : '' ?>" id="formCliente">
        <form action="index.php?route=salvar-cliente" method="POST" class="card card-body border-primary shadow-sm bg-light">
            <input type="hidden" name="id" value="<?= $clienteEditar['id'] ?? '' ?>">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="small fw-bold">NOME</label>
                    <input type="text" name="nome" class="form-control" required value="<?= $clienteEditar['nome'] ?? '' ?>">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold">TELEFONE</label>
                    <input type="text" name="telefone" class="form-control" value="<?= $clienteEditar['telefone'] ?? '' ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100 fw-bold">SALVAR</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark small">
                <tr>
                    <th>ID</th>
                    <th>NOME</th>
                    <th>TELEFONE</th>
                    <th class="text-center">CONSUMO HOJE</th>
                    <th class="text-end">AÇÕES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listaClientes as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td class="fw-bold"><?= $c['nome'] ?></td>
                    <td><?= $c['telefone'] ?></td>
                    <td class="text-center">
                        <button onclick="verConsumo(<?= $c['id'] ?>, '<?= $c['nome'] ?>')" class="btn btn-sm btn-info text-white shadow-sm px-3 fw-bold">
                            <i class="bi bi-eye-fill"></i> ITENS
                        </button>
                    </td>
                    <td class="text-end">
                        <a href="index.php?route=clientes&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary border-0"><i class="bi bi-pencil-square"></i></a>
                        <?php if($c['id'] != 1): ?>
                        <a href="index.php?route=excluir-cliente&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Excluir este cliente?')"><i class="bi bi-trash"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalConsumo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="nomeClienteModal">Detalhes</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="listaConsumoBody" class="p-3"></div>
                <div class="bg-light p-3 border-top text-end">
                    <span class="text-muted small d-block">TOTAL HOJE</span>
                    <h3 class="fw-bold text-success mb-0" id="totalConsumoModal">R$ 0,00</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function verConsumo(id, nome) {
    document.getElementById('nomeClienteModal').innerText = `Consumo de ${nome}`;
    const listBody = document.getElementById('listaConsumoBody');
    listBody.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>';
    new bootstrap.Modal(document.getElementById('modalConsumo')).show();

    try {
        const res = await fetch(`index.php?route=vendas-cliente-json&cliente_id=${id}`);
        const dados = await res.json();
        if (dados.length === 0) {
            listBody.innerHTML = '<div class="alert alert-warning text-center">Sem pedidos hoje.</div>';
            document.getElementById('totalConsumoModal').innerText = "R$ 0,00"; return;
        }
        let total = 0; let html = '<div class="list-group list-group-flush">';
        dados.forEach(item => {
            total += parseFloat(item.valor_total);
            const hora = item.data_venda.split(' ')[1].substring(0,5);
            html += `<div class="list-group-item px-0 d-flex justify-content-between">
                <div><span class="badge bg-secondary mb-1">${hora}</span><span class="fw-bold d-block">${item.produto.toUpperCase()}</span>
                <small class="text-muted">${parseFloat(item.quantidade).toFixed(3)} x R$ ${item.valor_unitario}</small></div>
                <div class="text-end fw-bold">R$ ${parseFloat(item.valor_total).toLocaleString('pt-br', {minimumFractionDigits: 2})}</div></div>`;
        });
        listBody.innerHTML = html + '</div>';
        document.getElementById('totalConsumoModal').innerText = `R$ ${total.toLocaleString('pt-br', {minimumFractionDigits: 2})}`;
    } catch (e) { listBody.innerHTML = '<div class="alert alert-danger">Erro ao carregar dados.</div>'; }
}
</script>