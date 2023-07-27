<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposta de Seguro de Saúde</title>
    <!-- Incluir Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Formulário de Proposta de Seguro de Saúde</h1>
        <span class="containerAlerta"></span>
        <form id="formularioProposta">
            <div class="form-group mt-3">
                <label for="numeroBeneficiarios">Número de Beneficiários:</label>
                <input type="number" class="form-control" id="numeroBeneficiarios" name="numeroBeneficiarios" value="1" min="1">
            </div>
            <div id="beneficiariosContainer"></div>

            <div class="form-group mt-3">
                <label for="registroPlano">Selecione um Plano:</label>
                <select class="form-control" id="registroPlano" name="registroPlano">
                    <option value="">Carregando...</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Cadastrar Proposta</button>
        </form>
    </div>

    <!-- Incluir Bootstrap JS e jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <!-- Incluir custom.js -->
    <script src="js/custom.js"></script>
    <script>
        // Função para criar campos de entrada de beneficiários dinamicamente
        function criarBeneficiariosInputs(numBeneficiarios) {
            const container = document.getElementById('beneficiariosContainer');
            container.innerHTML = '';

            for (let i = 1; i <= numBeneficiarios; i++) {
                const beneficiaryDiv = document.createElement('div');
                beneficiaryDiv.classList.add('form-group');
                beneficiaryDiv.classList.add('mt-3');

                const nameLabel = document.createElement('label');
                nameLabel.innerText = `Nome do Beneficiário ${i}:`;

                const nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.classList.add('form-control');
                nameInput.name = `nome[]`;

                const ageLabel = document.createElement('label');
                ageLabel.innerText = `Idade do Beneficiário ${i}:`;

                const ageInput = document.createElement('input');
                ageInput.type = 'number';
                ageInput.classList.add('form-control');
                ageInput.name = `idade[]`;
                ageInput.min = '0';

                beneficiaryDiv.appendChild(nameLabel);
                beneficiaryDiv.appendChild(nameInput);
                beneficiaryDiv.appendChild(ageLabel);
                beneficiaryDiv.appendChild(ageInput);

                container.appendChild(beneficiaryDiv);
            }
        }

        // Lidar com o envio do formulário
        $('#formularioProposta').on('submit', function(event) {
            event.preventDefault();
            const form = $(this);
            const divMsgAlert = document.querySelector('.containerAlerta');
            let divMsg = '';

            // Verifique se o número de beneficiários é válido (maior que 0)
            const numBeneficiarios = parseInt($('#numeroBeneficiarios').val()) || 0;
            if (numBeneficiarios <= 0) {
                divMsg = 'O número de beneficiários deve ser maior que zero.';
                divMsgAlert.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Erro!</strong> ${divMsg}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>`;
                return;
            }

            // Verifique se o nome e a idade de cada beneficiário estão preenchidos
            const beneficiaryInputs = $('input[name="nome[]"], input[name="idade[]"]');
            for (let i = 0; i < beneficiaryInputs.length; i++) {
                const input = beneficiaryInputs[i];
                if (input.value.trim() === '') {
                    divMsg = 'Por favor, preencha todos os campos de nome e idade dos beneficiários.';
                    divMsgAlert.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <strong>Erro!</strong> ${divMsg}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>`;
                    return;
                }
            }

            // Verifique se um plano foi selecionado
            const selectedPlan = $('#registroPlano').val();
            if (!selectedPlan) {
                divMsg = 'Por favor, selecione um plano antes de enviar o formulário.';
                divMsgAlert.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Erro!</strong> ${divMsg}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>`;
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'classes/api.php',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    window.location.href = 'retorno.php';
                },
                error: function(xhr, status, error) {
                    divMsg.innerHTML = 'Ocorreu um erro. Por favor, verifique o formulário e tente novamente.';
                    divMsgAlert.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Erro!</strong> ${divMsg}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>`;
                    console.error(error);
                }
            });
        });

        // Alterações no campo número de beneficiários
        $('#numeroBeneficiarios').on('change', function() {
            const numBeneficiarios = parseInt($(this).val()) || 0;
            criarBeneficiariosInputs(numBeneficiarios);
        });

        // Criar entradas de beneficiário no carregamento da p�áina
        criarBeneficiariosInputs(1);
    </script>
</body>

</html>