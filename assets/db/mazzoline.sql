create database mazzoline;
use mazzoline;

create table logins
(
idLogin int auto_increment primary key,
usuario varchar(255),
senha varchar(255)
);

select* from logins;


create table cadTipoUsuarios
(
idTipoUsuario int auto_increment primary key,
descUsuario varchar(30)
);

insert into cadTipoUsuarios (descUsuario) values("Super Usuario"), ("Usuario Comum"), ("Cliente");

create table cadpessoas
(
idLogin int,
idPessoa int auto_increment primary key,
rua varchar(255),
numero varchar(255),
bairro varchar(255),
cidade varchar(255),
estado varchar(255),
cep int(8),
tipoUsuario int(1) default 3,
pessoa enum ("Pessoa Física", "Pessoa Jurídica") default "Pessoa Fisica",
constraint fk_idLogin_cadPessoa foreign key (idLogin) references logins(idLogin),
constraint fk_idTipoU_cadPessoa foreign key (tipoUsuario) references cadTipoUsuarios(idTipoUsuario)
);

create table fisico
(
idPessoa int,
nome varchar(255),
cpf int(11) unique,
data_nasc date,
constraint fk_idPessoa_cadPF foreign key(idPessoa) references cadpessoas(idPessoa)
);

create table juridico
(
idPessoa int,
CNPJ int(14) unique,
razaoSocial varchar(255),
nomeFantasia varchar(255),
inscEstadual int(12),
data_Fundacao date,
constraint fk_idPessoa_cadPJ foreign key(idPessoa) references cadpessoas(idPessoa)
);

create table telefones
(
idFone int auto_increment primary key,
idPessoa int,
foneNum int(13),
constraint fk_FonePessoa foreign key(idPessoa) references cadPessoas(idPessoa)
);

create table emails
(
idEmail int auto_increment primary key,
idPessoa int,
email varchar(255),
constraint fk_EmailPessoa foreign key(idPessoa) references cadPessoas(idPessoa)
);


select * from logins;

select * from cadTipoUsuarios;

select * from cadPessoas;