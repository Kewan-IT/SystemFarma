<?php if(isset($_GET['erro'])): ?>
<div class="alert alert-danger">
    Email ou senha incorretos!
</div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Kewan_Farma</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            height: 100vh;
        }

        .login-card {
            border-radius: 15px;
        }

        .logo {
            font-size: 40px;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card login-card shadow p-4">
                
                <div class="text-center mb-4">
                    <div class="logo">💊</div>
                    <h4>Kewan_Farma</h4>
                    <p class="text-muted">Faça login para continuar</p>
                </div>

                <form method="POST" action="autenticar.php">

                    <div class="mb-3">
                        <label class="form-label">Email/Usuario</label>
                        <input type="email" name="email" class="form-control" placeholder="Digite seu email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Entrar
                        </button>
                    </div>

                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">© 2026 Farmácia System</small>
                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>