$(document).ready(function () {
    // Localizar os dados dos planos de plans.json
    $.ajax({
        url: 'json/plans.json',
        dataType: 'json',
        success: function (data) {
            // Preencha as opções selecionadas com os dados dos planos
            const registroPlanoSelect = $('#registroPlano');
            registroPlanoSelect.empty().append('<option value="">Selecione um Plano</option>');
            data.forEach(function (plano) {
                registroPlanoSelect.append(`<option value="${plano.registro}">${plano.nome}</option>`);
            });
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });

});