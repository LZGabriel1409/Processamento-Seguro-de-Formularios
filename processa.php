<?php
declare(strict_types=1);

$erros = [];
$sucesso = false;

$nome = $email = $idade = $website = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nome  = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS) ?? "";
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "E-mail inválido.";
    }

    $opcoes_idade = [
        "options" => [
            "min_range" => 18,
            "max_range" => 60
        ]
    ];

    $idade_validada = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_INT, $opcoes_idade);

    if ($idade_validada === false || $idade_validada === null) {
        $erros[] = "A idade deve estar entre 18 e 60 anos.";
    } else {
        $idade = (string)$idade_validada;
    }

    $website_input = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL);

    if (!empty($website_input)) {
        if (!filter_var($website_input, FILTER_VALIDATE_URL)) {
            $erros[] = "URL inválida.";
        } else {
            $website = $website_input;
        }
    }

    if (empty($erros)) {
        $sucesso = true;
    }

} else {
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado do Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            
            <?php if ($sucesso): ?>
                <div class="alert alert-success shadow border-0 p-4">
                    <h4 class="alert-heading fw-bold">Cadastro Realizado!</h4>
                    <hr>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Nome:</strong> <?= htmlspecialchars($nome) ?></li>
                        <li><strong>E-mail:</strong> <?= htmlspecialchars($email) ?></li>
                        <li><strong>Idade:</strong> <?= htmlspecialchars($idade) ?></li>
                        <?php if (!empty($website)): ?>
                            <li><strong>Website:</strong> 
                                <a href="<?= htmlspecialchars($website) ?>" target="_blank">
                                    <?= htmlspecialchars($website) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <a href="index.html" class="btn btn-primary mt-3">Novo Cadastro</a>

            <?php else: ?>
                <div class="alert alert-danger shadow border-0 p-4">
                    <h4 class="alert-heading fw-bold">Erros Encontrados</h4>
                    <ul class="mt-2">
                        <?php foreach ($erros as $erro): ?>
                            <li><?= htmlspecialchars($erro) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <hr>
                    <a href="javascript:history.back()" class="btn btn-outline-danger">Voltar ao Formulário</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>