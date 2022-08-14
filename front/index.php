<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quality</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">      
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <style>
            td {
                padding-top: 2px!important;
                padding-bottom: 2px!important;
                vertical-align: middle!important;
            }
        </style>
    </head>

    <body class="container">
        <div class="row mt-5">
            <div class="col-8">
                <h1>Lista de usuários</h1>
            </div>
            <div class="col-4 text-right">
                <button class="btn btn-info" data-toggle="modal" data-target="#modalNovoUsuario">Novo usuário</button>
            </div>
        </div>

        <div id="divTable">
            <div class="form-group mt-4">
                <label for="">Buscar</label>
                <input type="text" id="buscar" class="form-control" placeholder="Ex: Felipe">
            </div>

            <table class="table table-striped mt-4" id="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Usuário</th>
                        <th>Cidade</th>
                        <th>Cep</th>
                        <th class='text-center'>Opções</th>
                    </tr>
                </thead>
                <tbody id="tbody"></tbody>
            </table>
        </div>

        <p class='alert alert-danger'>Nenhum usuário cadastrado</p>

        <div class="modal fade" id="modalNovoUsuario" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo usuário</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body py-4">
                        <input type="hidden" id="id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="novoUsuario()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
        <script>
            const divTable = $('#divTable');
            const pAlert = $('.alert-danger');
            const table = $('#table');
            const tbody = $('#tbody');
            let linhas = []; // array com os dados para realizar a busca

            /*
                a busca/filtro é feita buscando dentro do array "linhas", onde sempre que um usuario é cadastrado/atualizado/deletado 
                essa lista é atualizada, e o usuario ao digitar algo no input de Busca, chama a funçao para buscar nesse array

                o controle do front da tabela é feito atravez de classes nas <tr> e <td>, ocultando ou exibindo com base nas IDs encontradas no array "linhas"
            */

            // usado para preencher o html da modal
            const inputs = [
                { id: 'idUsuario', label: 'Id Usuário', tipo: 'number' },
                { id: 'nome', label: 'Nome', tipo: 'text' },
                { id: 'codigo', label: 'Código', tipo: 'text' },
                { id: 'cpf_cnpj', label: 'CPF/CNPJ', tipo: 'text' },
                { id: 'cep', label: 'CEP', tipo: 'text' },
                { id: 'logradouro', label: 'Logradouro', tipo: 'text' },
                { id: 'endereco', label: 'Endereço', tipo: 'text' },
                { id: 'numero', label: 'Número', tipo: 'text' },
                { id: 'bairro', label: 'Bairro', tipo: 'text' },
                { id: 'cidade', label: 'Cidade', tipo: 'text' },
                { id: 'uf', label: 'UF', tipo: 'text' },
                { id: 'complemento', label: 'Complemento', tipo: 'text' },
                { id: 'fone', label: 'Fone', tipo: 'text' },
                { id: 'limiteCredito', label: 'Limite de crédito', tipo: 'number' },
                { id: 'validade', label: 'Validade', tipo: 'date' }
            ];

            pAlert.hide();
            divTable.hide();
            buscaUsuariosCadastrados();

            // monta formulario dentro da modal
            inputs.map( input => modalBody(input.id, input.label, input.tipo) );

            function buscaUsuariosCadastrados(){
                fetch(`../api/usuarios/lista_usuarios.php`)
                .then(res => res.json())
                .then(({ data, status }) => {
                    if(status){
                        data.map( linha => addLinhaTabela(linha)); // para cada dado cadastrado, chama a funçao para preencher o front da tabela
                        divTable.show();
                    } else {
                        pAlert.show();
                    }
                }).catch((err) => console.error(err) );
            }

            // preenche o front da tabela e o array com dados das linhas para busca posterior
            function addLinhaTabela(valor){
                linhas.push({
                    id: valor.id.toString(),
                    codigo: valor.codigo ? valor.codigo : '',
                    nome: valor.nome ? valor.nome : '',
                    cidade: valor.cidade ? valor.cidade : '',
                    cep: valor.cep ? valor.cep : ''
                });

                tbody.append(`
                    <tr id="tr_${valor.id}" class="trBody">
                        <td class="tdBuscar" id="codigo_${valor.id}">${valor.codigo}</td>
                        <td class="tdBuscar" id="nome_${valor.id}">${valor.nome}</td>
                        <td class="tdBuscar" id="cidade_${valor.id}">${valor.cidade}</td>
                        <td class="tdBuscar" id="cep_${valor.id}">${valor.cep}</td>
                        <td class='text-center'>
                            <div class="dropdown">
                                <button class='btn btn-light btn-sm' data-toggle="dropdown"><i class='fa fa-cog' style='color: #828689'></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="javascript:modalEditarUsuario(${valor.id})"><i class='fa fa-pencil'></i> Editar</a>
                                    <a class="dropdown-item" href="javascript:deletaUsuario(${valor.id})"><i class='fa fa-trash'></i> Deletar</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                `);
            }

            // preeche a modal com o html dos inputs
            function modalBody(id, label, tipo, required){
                $('.modal-body').append(`<div class="form-group"><label for="${id}">${label}</label><input type="${tipo}" class="form-control" id="${id}" ${required}></div>`);
            }

            // monta objeto com os dados dos inputs da modal para cadastrar ou atualizar o usuario
            function dadosDosInputs(){
                return {
                    "idUsuario": $('#idUsuario').val(),
                    "nome": $('#nome').val(),
                    "codigo": $('#codigo').val(),
                    "cpf_cnpj": $('#cpf_cnpj').val(),
                    "cep": $('#cep').val(),
                    "logradouro": $('#logradouro').val(),
                    "endereco": $('#endereco').val(),
                    "numero": $('#numero').val(),
                    "bairro": $('#bairro').val(),
                    "cidade": $('#cidade').val(),
                    "uf": $('#uf').val(),
                    "complemento": $('#complemento').val(),
                    "fone": $('#fone').val(),
                    "limiteCredito": $('#limiteCredito').val(),
                    "validade": $('#validade').val()
                };
            }

            function novoUsuario(){

                // se input id tiver valor, chama a funçao de atualizar
                if($('#id').val() !== ''){
                    atualizarUsuario();
                    return
                }
                // se nao roda a funçao para novo cadastro

                let dados = dadosDosInputs();

                fetch(`../api/usuarios/novo_usuario.php`, { method: 'post', body: JSON.stringify(dados) })
                .then(res => res.json())
                .then(({ data, status }) => {
                    $('#modalNovoUsuario').modal('hide');

                    if(status){
                        pAlert.hide();
                        divTable.show();
                        addLinhaTabela(data);
                        linhas.map( e => $(`#tr_${e.id}`).show() ); // garante que todas linhas da tabela estejam visiveis apos cadastrar um novo usuario
                        Swal.fire({ icon: 'success', title: 'Usuário cadastrado com sucesso!' });
                    } else {
                        Swal.fire({icon: 'error', title: 'Não foi possível cadastrar o usuário'});
                    }
                }).catch((err) => console.error(err) );
            }

            function modalEditarUsuario(id){
                fetch(`../api/usuarios/busca_usuario.php?id=${id}`)
                .then(res => res.json())
                .then(({ data, status }) => {
                    if(status){
                        data = data[0];
                        for(let campo in data){
                            if(data[campo]){
                                $(`#${campo}`).val(data[campo]); // preenche os dados cadastrados nos inputs da modal
                            }
                        }

                        $('.modal-title').html('Editar usuário');
                        $('#modalNovoUsuario').modal('show');
                    } else {
                        Swal.fire({ icon: 'error', title: 'Usuário não encontrado'});
                    }
                }).catch((err) => console.error(err) );
            }

            function atualizarUsuario(){
                let dados = dadosDosInputs();
                dados.id = $('#id').val();

                fetch(`../api/usuarios/atualiza_usuario.php`, { method: 'post', body: JSON.stringify(dados) })
                .then(res => res.json())
                .then(({ data, status }) => {
                    $('#modalNovoUsuario').modal('hide');

                    // atualiza o front da tabela
                    if(status){
                        $(`#codigo_${data.id}`).html(data.codigo);
                        $(`#nome_${data.id}`).html(data.nome);
                        $(`#cidade_${data.id}`).html(data.cidade);
                        $(`#cep_${data.id}`).html(data.cep);

                        // atualiza dados das linhas da tabela
                        linhas.map( obj => {
                            if(obj.id == data.id){
                                obj.codigo = data.codigo ? data.codigo : '';
                                obj.nome = data.nome ? data.nome : '';
                                obj.cidade = data.cidade ? data.cidade : '';
                                obj.cep = data.cep ? data.cep : '';
                            }
                        });

                        linhas.map( e => $(`#tr_${e.id}`).show() ); // garante que todas linhas da tabela estejam visiveis apos atualizar um usuario
                        Swal.fire({ icon: 'success', title: 'Usuário atualizado com sucesso!' });
                    } else {
                        Swal.fire({icon: 'error', title: 'Não foi possível atualizar o usuário'});
                    }
                }).catch((err) => console.error(err) );
            }

            function deletaUsuario(id){
                Swal.fire({
                    icon: 'question',
                    title: 'Deseja deletar esse usuário?',
                    showDenyButton: true,
                    confirmButtonText: 'Sim',
                    denyButtonText: `Não`,
                    confirmButtonColor: '#dd3333',
                    denyButtonColor: '#8d8d8d',
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`../api/usuarios/deleta_usuario.php?id=${id}`)
                        .then(res => res.json())
                        .then(({ data, status }) => {
                            if(status){
                                $(`#tr_${id}`).remove(); // remove linha da tabela

                                // remove do array de dados de busca
                                linhas.map( (obj, index) => {
                                    if(obj.id == id)
                                        linhas.splice(index, 1);
                                });

                                if(linhas.length === 0){
                                    divTable.hide();
                                    pAlert.show();
                                }

                            } else {
                                Swal.fire({ icon: 'error', title: 'Não foi possível deletar o usuário'});
                            }
                        }).catch((err) => console.error(err) );
                    }
                })
            }

            // busca no array de dados da tabela se o valor digitado existe
            $(document).on('keyup', '#buscar', function(){
                if(this.value){
                    linhas.map(e => {
                        $(`#tr_${e.id}`).hide();

                        for(let x in e){
                            if(e[x]){
                                if(e[x].includes(this.value)){
                                    $(`#tr_${e.id}`).show();
                                }
                            }
                        }
                    });
                } else {
                    linhas.map( e => $(`#tr_${e.id}`).show() );
                } 
            });

            // busca o cep na api viacep
            $(document).on('blur', '#cep', function(){
                pesquisacep(this.value);
            });

            // limpa inputs da modal ao fechar
            $(document).on('hide.bs.modal', '#modalNovoUsuario', function(){
                $('.modal-title').html('Novo usuário');
                $('input').val('');
            });

            // viacep api ---------------------
            function limpa_formulário_cep() {
                $('#logradouro').val('');
                $('#bairro').val('');
                $('#cidade').val('');
                $('#uf').val('');
                $('#complemento').val('');
            }

            function meu_callback(conteudo) {
                if (!("erro" in conteudo)) {
                    $('#logradouro').val(conteudo.logradouro);
                    $('#bairro').val(conteudo.bairro);
                    $('#cidade').val(conteudo.localidade);
                    $('#uf').val(conteudo.uf);
                    $('#complemento').val(conteudo.complemento);
                } else {
                    limpa_formulário_cep();
                    Swal.fire({ icon: 'warning', title: "CEP não encontrado" });
                }
            }
        
            function pesquisacep(valor) {
                var cep = valor.replace(/\D/g, '');
                if (cep != "") {
                    var validacep = /^[0-9]{8}$/;

                    if(validacep.test(cep)) {
                        var script = document.createElement('script');
                        script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
                        document.body.appendChild(script);
                    } else {
                        limpa_formulário_cep();
                        Swal.fire({ icon: 'warning', title: "Formato de CEP inválido" });
                    }
                } else {
                    limpa_formulário_cep();
                }
            }
        </script>
    </body>
</html>