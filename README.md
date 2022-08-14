# quality-teste
Teste crud usuario com api viacep


# Criação do banco 

CREATE DATABASE IF NOT EXISTS `quality` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;<br>
USE `quality`;

CREATE TABLE `usuarios` (<br>
  `id` bigint(20) NOT NULL,<br>
  `idUsuario` bigint(20) DEFAULT NULL,<br>
  `dataHoraCadastro` datetime DEFAULT current_timestamp(),<br>
  `codigo` varchar(15) DEFAULT NULL,<br>
  `nome` varchar(150) DEFAULT NULL,<br>
  `cpf_cnpj` varchar(20) DEFAULT NULL,<br>
  `cep` int(100) DEFAULT NULL,<br>
  `logradouro` varchar(100) DEFAULT NULL,<br>
  `endereco` varchar(120) DEFAULT NULL,<br>
  `numero` varchar(20) DEFAULT NULL,<br>
  `bairro` varchar(50) DEFAULT NULL,<br>
  `cidade` varchar(60) DEFAULT NULL,<br>
  `uf` varchar(2) DEFAULT NULL,<br>
  `complemento` varchar(150) DEFAULT NULL,<br>
  `fone` varchar(15) DEFAULT NULL,<br>
  `limiteCredito` float DEFAULT NULL,<br>
  `validade` date DEFAULT NULL<br>
) ENGINE=InnoDB DEFAULT CHARSET=utf8;<br>
<br><br>



# Dados de conexão com o banco
Os dados podem ser alterados, basta mudar os parametro na linha 11 do arquivo api/conn.php<br>

host = 'localhost'<br>
usuario = 'root'<br>
senha = ''<br>
banco = 'quality'<br>
