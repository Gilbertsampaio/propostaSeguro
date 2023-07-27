<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de proposta de seguro de saúde</title>
    <!-- Incluir Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Histórico de proposta de seguro de saúde</h1>
        <?php
        // Leia os dados da proposta de proposta.json
        $propostaJson = file_get_contents('json/proposta.json');
        $propostaData = json_decode($propostaJson, true);

        if (!$propostaData) {
            echo '<p>Nenhuma proposta encontrada.</p>';
        } else {
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Registro do Plano</th>
                        <th>Nome do Plano</th>
                        <th>Número de Beneficiários</th>
                        <th>Preço Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $propostaData['planoSelecionado']['registro']; ?></td>
                        <td><?php echo $propostaData['planoSelecionado']['nome']; ?></td>
                        <td><?php echo count($propostaData['beneficiarios']); ?></td>
                        <td><?php echo 'R$ ' . number_format($propostaData['totalPlanoPreco'], 2, ',', '.'); ?></td>
                    </tr>
                </tbody>
            </table>
            <h3>Preços Individuais:</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Idade</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($propostaData['beneficiarios'] as $beneficiario) : ?>
                        <tr>
                            <td><?php echo $beneficiario['nome']; ?></td>
                            <td><?php echo $beneficiario['idade']; ?></td>
                            <td><?php echo 'R$ ' . number_format($beneficiario['preco'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Botão para retornar à página do formulário -->
            <a href="index.php" class="btn btn-primary">Voltar ao Formulário</a>
        <?php } ?>
    </div>

    <!-- Incluir Bootstrap JS e jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>

</html>