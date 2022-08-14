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
                                // compara o valor do campo busca com os dados do array, removendo acentos e lowercase
                                if(accents_supr(e[x]).toLowerCase().includes(NeutralizeAccent(this.value.toLowerCase()))){
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

            // essa é uma soluçao que apliquei num antigo projeto para normalizar as letras com áàÁ... etc
            function accents_supr(data) {
                return !data ? '' : typeof data === 'string' ? data.replace(/\n/g, ' ').replace(/[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g, 'a').replace(/[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g, 'e').replace(/[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g, 'i').replace(/[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g, 'o').replace(/[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g, 'u').replace(/[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g, 'A').replace(/[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g, 'E').replace(/[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g, 'I').replace(/[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g, 'O').replace(/[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g, 'U').replace(/ç/g, 'c').replace(/Ç/g, 'C') : data;
            }

            function NeutralizeAccent(data){
                return !data ? '': typeof data === 'string'? data.replace(/\n/g, ' ').replace(/[éÉěĚèêëÈÊË]/g, 'e').replace(/[šŠ]/g, 's').replace(/[čČçÇ]/g, 'c').replace(/[řŘ]/g, 'r').replace(/[žŽ]/g, 'z').replace(/[ýÝ]/g, 'y').replace(/[áÁâàÂÀãÃ]/g, 'a').replace(/[íÍîïÎÏ]/g, 'i').replace(/[ťŤ]/g, 't').replace(/[ďĎ]/g, 'd').replace(/[ňŇñÑ]/g, 'n').replace(/[óÓôÔõÕ]/g, 'o').replace(/[úÚůŮ]/g, 'u'): data
            }
        </script>
    </body>
</html>