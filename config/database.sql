
CREATE TABLE tipo_utilizador (
  id_tipo_utilizador int(11) NOT NULL UNSIGNED PRIMARY key AUTO_INCREMENT,
  descricao varchar(30) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO tipo_utilizador (id_tipo_utilizador, descricao) VALUES
(1, 'Administrador'),
(2, 'Editor'),
(3, 'Subscritor');


CREATE TABLE utilizador (
  id bigint(20) NOT NULL UNSIGNED primary key AUTO_INCREMENT,
  username varchar(34) NOT NULL,
  email varchar(100) NOT NULL,
  nome varchar(140) NOT NULL,
  senha varchar(40) NOT NULL,
  id_tipo_utilizador int(11) NOT NULL,
  FOREIGN key (id_tipo_utilizador) REFERENCES tipo_utilizador(id_tipo_utilizador)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO utilizador (id, username, email, nome, senha, id_tipo_utilizador) VALUES
(1, 'admin', 'admin@example.com', 'Admin Name', 'hashed_password', 1),
(2, 'moderator', 'moderator@example.com', 'Moderator Name', 'hashed_password', 2),
(3, 'user', 'user@example.com', 'User Name', 'hashed_password', 3);

CREATE TABLE categoria (
  id_categoria bigint(20) NOT NULL UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  descricao varchar(100) DEFAULT NULL,
  slug varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categoria` (`descricao`, `slug`) VALUES
('Engenharia Civil', 'engenharia-civil'),
('Sustentabilidade', 'sustentabilidade'),
('Construcoes','construcoes'),
('Economia Verde','economia-verde');

CREATE TABLE artigo (
  id bigint(20) NOT NULL UNSIGNED PRIMARY key AUTO_INCREMENT,
  imagem varchar(255) DEFAULT 'assets/img/logo.png',
  titulo varchar(255) NOT NULL,
  conteudo text NOT NULL,
  autor bigint(20) NOT NULL,
  data_publicacao timestamp NOT NULL DEFAULT current_timestamp(),
  id_categoria bigint(20) not NULL,
  FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
