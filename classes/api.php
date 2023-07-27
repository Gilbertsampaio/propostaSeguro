<?php

class Beneficiario
{
    public $nome;
    public $idade;

    public function __construct($nome, $idade)
    {
        $this->nome = $nome;
        $this->idade = $idade;
    }
}

class Plano
{
    public $registro;
    public $nome;
    public $codigo;

    public function __construct($registro, $nome, $codigo)
    {
        $this->registro = $registro;
        $this->nome = $nome;
        $this->codigo = $codigo;
    }
}

class Preco
{
    public $plano_codigo;
    public $faixa1;
    public $faixa2;
    public $faixa3;

    public function __construct($plano_codigo, $faixa1, $faixa2, $faixa3)
    {
        $this->plano_codigo = $plano_codigo;
        $this->faixa1 = $faixa1;
        $this->faixa2 = $faixa2;
        $this->faixa3 = $faixa3;
    }
}

function lerArquivoJson($nomeArquivo)
{
    $jsonString = file_get_contents($nomeArquivo);
    return json_decode($jsonString, true);
}

function encontrarPlanoPorRegistro($planos, $registro)
{
    foreach ($planos as $plano) {
        if ($plano['registro'] === $registro) {
            return new Plano($plano['registro'], $plano['nome'], $plano['codigo']);
        }
    }
    return null;
}

function encontrarPrecoPorCodigoPlano($precos, $plano_codigo)
{
    foreach ($precos as $preco) {
        if ($preco['codigo'] === $plano_codigo) {
            return new Preco($preco['codigo'], $preco['faixa1'], $preco['faixa2'], $preco['faixa3']);
        }
    }
    return null;
}

function calcularPrecoBeneficiario($beneficiario, $plano, $precos, $numeroBeneficiarios)
{
    $preco = null;
    $encontrarPreco = false;

    foreach ($precos as $precoData) {
        if ($precoData['codigo'] === $plano->codigo && $beneficiario->idade >= 0) {
            if ($beneficiario->idade <= 17) {
                $preco = $precoData['faixa1'];
                $encontrarPreco = true;
            } elseif ($beneficiario->idade <= 40) {
                $preco = $precoData['faixa2'];
                $encontrarPreco = true;
            } else {
                $preco = $precoData['faixa3'];
                $encontrarPreco = true;
            }
        }

        // Check for variations in prices based on the number of beneficiarios (minimo_vidas)
        if ($encontrarPreco && $precoData['minimo_vidas'] <= $numeroBeneficiarios) {
            $preco = $precoData['faixa1'];
            if ($beneficiario->idade <= 40) {
                $preco = $precoData['faixa2'];
            } elseif ($beneficiario->idade > 40) {
                $preco = $precoData['faixa3'];
            }
            break;
        }
    }

    return $preco;
}

function calcularTotalPlanoPreco($beneficiarios, $plano, $precos, $numeroBeneficiarios)
{
    $totalPreco = 0;

    foreach ($beneficiarios as $beneficiario) {
        $preco = calcularPrecoBeneficiario($beneficiario, $plano, $precos, $numeroBeneficiarios);
        if ($preco !== null) {
            $totalPreco += $preco;
        }
    }

    return $totalPreco;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read data from JSON files
    $planos = lerArquivoJson('../json/plans.json');
    $precos = lerArquivoJson('../json/prices.json');

    // Retrieve data from the form
    $numeroBeneficiarios = (int)$_POST['numeroBeneficiarios'];
    $beneficiarios = [];

    for ($i = 0; $i < $numeroBeneficiarios; $i++) {
        $nome = $_POST['nome'][$i];
        $idade = (int)$_POST['idade'][$i];
        $beneficiarios[] = new Beneficiario($nome, $idade);
    }

    $registroPlanoSelecionado = $_POST['registroPlano'];

    // Find the selected plan
    $planoSelecionado = encontrarPlanoPorRegistro($planos, $registroPlanoSelecionado);

    if (!$planoSelecionado) {
        die("Plano selecionado não encontrado.");
    }

    // Calculate individual prices and total plan price
    $individualPrecos = [];
    foreach ($beneficiarios as $beneficiario) {
        $individualPrecos[] = [
            'nome' => $beneficiario->nome,
            'idade' => $beneficiario->idade,
            'preco' => calcularPrecoBeneficiario($beneficiario, $planoSelecionado, $precos, $numeroBeneficiarios),
        ];
    }

    $totalPlanoPreco = calcularTotalPlanoPreco($beneficiarios, $planoSelecionado, $precos, $numeroBeneficiarios);

    // Prepare proposal data
    $proposalData = [
        'planoSelecionado' => [
            'registro' => $planoSelecionado->registro,
            'nome' => $planoSelecionado->nome,
            'codigo' => $planoSelecionado->codigo,
        ],
        'beneficiarios' => $individualPrecos,
        'totalPlanoPreco' => $totalPlanoPreco,
    ];

    // Save proposal data to "proposta.json"
    file_put_contents('../json/proposta.json', json_encode($proposalData, JSON_PRETTY_PRINT));

    // Respond with JSON indicating success (if needed)
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}
?>