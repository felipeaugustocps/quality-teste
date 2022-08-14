# quality-teste
Teste crud usuario com api viacep


Criação do banco 

CREATE DATABASE IF NOT EXISTS `quality` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `quality`;

CREATE TABLE `usuarios` (
  `id` bigint(20) NOT NULL,
  `idUsuario` bigint(20) DEFAULT NULL,
  `dataHoraCadastro` datetime DEFAULT current_timestamp(),
  `codigo` varchar(15) DEFAULT NULL,
  `nome` varchar(150) DEFAULT NULL,
  `cpf_cnpj` varchar(20) DEFAULT NULL,
  `cep` int(100) DEFAULT NULL,
  `logradouro` varchar(100) DEFAULT NULL,
  `endereco` varchar(120) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `complemento` varchar(150) DEFAULT NULL,
  `fone` varchar(15) DEFAULT NULL,
  `limiteCredito` float DEFAULT NULL,
  `validade` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




Dados de conexão com o banco
Os dados podem ser alterados, basta mudar os parametro na linha 11 do arquivo api/conn.php

host = 'localhost'
usuario = 'root'
senha = ''
banco = 'quality'
