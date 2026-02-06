<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Açaí Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #4b0082, #8a2be2); height: 100vh; display: flex; align-items: center; font-family: 'Segoe UI', sans-serif; }
        .login-card { border: none; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); overflow: hidden; background: #fff; }
        .btn-purple { background: #6a1b9a; color: white; border-radius: 10px; padding: 12px; transition: 0.3s; }
        .btn-purple:hover { background: #4b0082; color: white; transform: translateY(-2px); }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-cup-straw fs-1 text-purple" style="color: #6a1b9a;"></i>
                        <h2 class="fw-bold mt-2" style="color: #4b0082;">Açaí Manager</h2>
                        <p class="text-muted">Entre com suas credenciais</p>
                    </div>

                    <?php if(isset($_GET['erro'])): ?>
                        <div class="alert alert-danger py-2 text-center" style="font-size: 0.9rem;">
                            Usuário ou senha inválidos!
                        </div>
                    <?php endif; ?>

                    <form action="?route=login" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">USUÁRIO</label>
                            <input type="text" name="usuario" class="form-control" placeholder="Seu usuário" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-bold">SENHA</label>
                            <input type="password" name="senha" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-purple w-100 fw-bold shadow-sm">ENTRAR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>