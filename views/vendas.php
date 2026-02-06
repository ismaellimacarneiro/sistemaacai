<div class="container-fluid py-3">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-primary">
                        <i class="bi bi-upc-scan me-2"></i>CAIXA ABERTO
                    </h5>
                    
                    <form action="index.php?route=salvar-venda" method="POST" id="formVenda">
                        <input type="hidden" name="id" value="<?= $vendaEditar['id'] ?? '' ?>">

                        <div class="mb-3">
                            <label class="form-label small fw-bold">CÓDIGO DO PRODUTO</label>
                            <input type="number" name="produto_id" id="produto_id" 
                                   class="form-control bg-light border-0 p-3 fw-bold fs-5" 
                                   value="<?= $vendaEditar['produto_id'] ?? '' ?>" 
                                   placeholder="Ex: 1" autofocus required>
                            <div id="nome_produto_preview" class="text-primary fw-bold mt-1 small"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">PESO (G) / QTD (UN)</label>
                            <input type="number" step="0.001" name="peso" id="peso_input" 
                                   class="form-control bg-primary bg-opacity-10 border-0 p-3 fw-bold fs-2 text-primary text-center" 
                                   value="<?= $vendaEditar['peso'] ?? '' ?>" 
                                   placeholder="0.000">
                        </div>

                        <div class="mb-3 p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 text-center">
                            <label class="d-block small fw-bold text-success mb-1">TOTAL ESTIMADO</label>
                            <span class="fs-3 fw-bold text-success" id="valor_preview">R$ 0,00</span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">QUEM ESTÁ CONSUMINDO? (CLIENTE)</label>
                            <select name="cliente_id" class="form-select bg-light border-0">
                                <?php foreach($listaClientes as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= ($c['nome'] == 'CLIENTE' || (isset($vendaEditar) && $vendaEditar['cliente_id'] == $c['id'])) ? 'selected' : '' ?>>
                                        <?= strtoupper($c['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">QUEM VAI PAGAR? (RESPONSÁVEL)</label>
                            <input type="text" name="responsavel" class="form-control bg-light border-0" 
                                   value="<?= $vendaEditar['responsavel'] ?? '' ?>" 
                                   placeholder="Ex: Nome do pai/amigo">
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">PAGAMENTO</label>
                            <select name="forma_pagamento" id="pagamento_input" class="form-select bg-light border-0">
                                <option value="Dinheiro" <?= (isset($vendaEditar) && $vendaEditar['forma_pagamento'] == 'Dinheiro') ? 'selected' : '' ?>>DINHEIRO</option>
                                <option value="Pix" <?= (isset($vendaEditar) && $vendaEditar['forma_pagamento'] == 'Pix') ? 'selected' : '' ?>>PIX</option>
                                <option value="Cartão" <?= (isset($vendaEditar) && $vendaEditar['forma_pagamento'] == 'Cartão') ? 'selected' : '' ?>>CARTÃO</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-3 shadow-sm">
                            <i class="bi bi-check-lg me-2"></i>FINALIZAR VENDA
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 text-muted text-uppercase">Pesquisar e Fechar Conta</h6>
                    
                    <form method="GET" action="index.php" class="row g-2 mb-4">
                        <input type="hidden" name="route" value="vendas">
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted">RESPONSÁVEL</label>
                            <input type="text" name="busca_responsavel" class="form-control form-control-sm bg-light border-0" 
                                   placeholder="Nome de quem paga..." value="<?= $_GET['busca_responsavel'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-muted">DATA INÍCIO</label>
                            <input type="date" name="data_inicio" class="form-control form-control-sm bg-light border-0" 
                                   value="<?= $_GET['data_inicio'] ?? date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-muted">DATA FIM</label>
                            <input type="date" name="data_fim" class="form-control form-control-sm bg-light border-0" 
                                   value="<?= $_GET['data_fim'] ?? date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th>HORA</th>
                                    <th>PRODUTO</th>
                                    <th>CLIENTE / RESP.</th>
                                    <th class="text-center">PESO/QTY</th>
                                    <th class="text-end">VALOR</th>
                                    <th class="text-center">AÇÕES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalFiltrado = 0;
                                if(empty($vendasFiltradas)): 
                                ?>
                                    <tr><td colspan="6" class="text-center text-muted p-4">Nenhuma venda encontrada no filtro.</td></tr>
                                <?php 
                                else:
                                    foreach($vendasFiltradas as $v): 
                                        $totalFiltrado += $v['valor_total'];
                                ?>
                                <tr class="border-bottom">
                                    <td class="small text-muted"><?= date('H:i', strtotime($v['data_venda'])) ?></td>
                                    <td>
                                        <div class="fw-bold text-uppercase"><?= $v['produto'] ?></div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-uppercase text-primary"><?= $v['cliente'] ?></div>
                                        <?php if(!empty($v['responsavel'])): ?>
                                            <div class="small text-muted" style="font-size: 0.75rem;">RESP: <?= strtoupper($v['responsavel']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center fw-bold"><?= number_format($v['peso'], 3, ',', '.') ?></td>
                                    <td class="text-end fw-bold text-success">R$ <?= number_format($v['valor_total'], 2, ',', '.') ?></td>
                                    <td class="text-center">
                                        <a href="index.php?route=vendas&id=<?= $v['id'] ?>" class="text-primary me-2"><i class="bi bi-pencil"></i></a>
                                        <a href="index.php?route=excluir-venda&id=<?= $v['id'] ?>" class="text-danger" onclick="return confirm('Excluir?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach; 
                                endif;
                                ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold py-3 text-muted">SOMA DOS RESULTADOS:</td>
                                    <td class="text-end fw-bold text-primary fs-5 py-3">R$ <?= number_format($totalFiltrado, 2, ',', '.') ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Lista de produtos para o JavaScript (gerada pelo PHP)
const produtos = <?= json_encode($listaProdutos) ?>;

const inputCod = document.getElementById('produto_id');
const inputPeso = document.getElementById('peso_input');
const previewNome = document.getElementById('nome_produto_preview');
const previewValor = document.getElementById('valor_preview');

function calcularPrevia() {
    const cod = inputCod.value;
    const pesoRaw = inputPeso.value.replace(',', '.');
    const pesoNum = parseFloat(pesoRaw) || 0;
    
    const produto = produtos.find(p => p.id == cod);

    if (produto) {
        let nome = produto.nome.toUpperCase();
        let preco = parseFloat(produto.preco);
        let total = 0;

        // Se for Açaí (Balança)
        if (nome.includes("AÇAÍ") || nome.includes("ACAI")) {
            previewNome.innerText = "PRODUTO: " + nome + " (BALANÇA)";
            total = preco * pesoNum;
        } else {
            // Se for Unidade (Água, Refri, etc)
            previewNome.innerText = "PRODUTO: " + nome + " (UNIDADE)";
            // Se o campo peso/qtd for 0, considera 1 unidade
            total = pesoNum > 0 ? (preco * pesoNum) : preco;
        }

        previewValor.innerText = "R$ " + total.toLocaleString('pt-br', {minimumFractionDigits: 2});
    } else {
        previewNome.innerText = "PRODUTO NÃO ENCONTRADO";
        previewValor.innerText = "R$ 0,00";
    }
}

// Eventos de entrada
inputCod.addEventListener('input', calcularPrevia);
inputPeso.addEventListener('input', calcularPrevia);

// Atalhos de Enter para facilitar a operação
inputCod.addEventListener('keydown', (e) => { 
    if(e.key === 'Enter') { 
        e.preventDefault(); 
        inputPeso.focus(); 
    } 
});
inputPeso.addEventListener('keydown', (e) => { 
    if(e.key === 'Enter') { 
        e.preventDefault(); 
        document.getElementById('pagamento_input').focus(); 
    } 
});

// Garante o cálculo se houver dados ao carregar (edição)
window.onload = calcularPrevia;
</script>