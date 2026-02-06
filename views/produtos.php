<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-4">
                <h4 class="fw-bold mb-3"><?= isset($produtoEditar) ? 'Editar Produto' : 'Novo Produto' ?></h4>
                <form action="index.php?route=salvar-produto" method="POST">
                    <input type="hidden" name="id" value="<?= $produtoEditar['id'] ?? '' ?>">

                    <div class="mb-3">
                        <label class="form-label">Nome do Produto</label>
                        <input type="text" name="nome" class="form-control" value="<?= $produtoEditar['nome'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Preço (R$)</label>
                        <input type="number" step="0.01" name="preco" class="form-control" value="<?= $produtoEditar['preco'] ?? '' ?>" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><?= isset($produtoEditar) ? 'Salvar Alterações' : 'Cadastrar' ?></button>
                        <?php if (isset($produtoEditar)): ?>
                            <a href="index.php?route=produtos" class="btn btn-light">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <h4 class="fw-bold mb-3">Lista de Produtos</h4>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaProdutos as $p): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($p['nome']) ?></td>
                                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                                <td class="text-end">
                                    <a href="index.php?route=produtos&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="index.php?route=excluir-produto&id=<?= $p['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Deseja excluir este produto?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
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