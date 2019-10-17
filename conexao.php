<?php

  ini_set('date.timezone', 'America/Sao_Paulo');

  /* PRODUÇÃO */
  $CAMINHO = "http://localhost/veterinaria";

  $CAMINHOSITE = "http://www.caminhosite.com.br/site";

  /*PRODUÇÃO
  $root = substr($_SERVER["DOCUMENT_ROOT"], 0, -3);
  */

  $root = "/home/augustoi/public_html";

  $idCliente = ( isset($_COOKIE["id_cliente"] ) ? $_COOKIE["id_cliente"] : 0);

  $idUsuario = ( $_COOKIE["id_usuario"] ) ? $_COOKIE["id_usuario"] : 0;

  $CAMINHOANEXO = $CAMINHO . "/arquivos/" . $idCliente;

  /*

  include "$root/privado/sistema/classes/ImageManager.php";

  include "$root/privado/sistema/classes/Uploader.php";

  include "$root/privado/sistema/classes/funcoesPHP.php";
  */

  include "classes/ImageManager.php";

  include "classes/Uploader.php";

  include "classes/funcoesPHP.php";

  $CAMINHOCSS = $CAMINHO . "/configuracao/css";

  $CAMINHOJS = $CAMINHO . "/configuracao/js";

  $caminhoImg = $CAMINHO . "/configuracao/images";

  $caminhoUploadImagem  = "$root/www/sistema/imagens";

  $caminhoUploadArquivo = "$root/www/sistema/arquivos";

  foreach ($_REQUEST as $campo => $valor) {

      //$$campo = secure($valor);

      $$campo = $valor;

  }

  $stCliente = Conexao::chamar()->prepare("SELECT cliente.*,

                                                  municipio.nome municipio,

                                                  estado.nome estado

      FROM cliente

      LEFT JOIN municipio ON cliente.id_municipio = municipio.id

      LEFT JOIN estado ON municipio.id_estado = estado.id

      WHERE cliente.id = :id_cliente");



$stCliente->execute(array("id_cliente" => $idCliente));

$buscaCliente = $stCliente->fetch(PDO::FETCH_ASSOC);



$stAdministrador = Conexao::chamar()->prepare("SELECT   usuario.id, 
                                                                      usuario.id_cliente, 
                                                                      usuario.usuario, 
                                                                      usuario.email,
                                                                      usuario.permissao_log,
                                                                      usuario.data_validade,
                                                                      usuario.status_registro,
                                                                      usuario.master,   
                                                                      cliente.nome_fantasia,
                                                                      cliente.cnpj,
                                                                      cliente.razao_social
                                                                      FROM usuario
                                                                      INNER JOIN  cliente
                                                                      ON usuario.id_cliente = cliente.id
                                                                      WHERE usuario.id = :id_usuario");



$stAdministrador->execute(array("id_usuario" => $idUsuario));



$buscaAdministrador = $stAdministrador->fetch(PDO::FETCH_ASSOC);

//$_COOKIE["id_cliente"] = $buscaAdministrador['id_cliente'];

if($buscaAdministrador["master"] == "S") $idUsuarioMaster = true;



$stPermissao = Conexao::chamar()->prepare("SELECT controle_menu_usuario.*

     FROM controle_menu, controle_menu_usuario

     WHERE (controle_menu.id = controle_menu_usuario.id_menu OR controle_menu.id_menu = controle_menu_usuario.id_menu)

     AND controle_menu_usuario.id_usuario = :id_usuario

     AND controle_menu.id = :tela");


if(!(isset($t)))$t = '0';



$stPermissao->execute(array("id_usuario" => $idUsuario, "tela" => $t));

$permissao = $stPermissao->fetch(PDO::FETCH_ASSOC);



$cadastrar = $permissao['cadastrar'] == "S" || $buscaAdministrador['master'] == "S" ? true : false;

$excluir   = $permissao['excluir'] == "S" || $buscaAdministrador['master'] == "S" ? true : false;



class Conexao {



     private static $conexao = NULL;

     private static $destruir = false;



     
     
     /* localhost   
     
      private static $DB_HOST = "192.168.200.49";

     private static $DB_NAME = "iic_cis";

    private static $DB_USER = "root";

     private static $DB_PASS = "1553bb7b4a8712761c334cb357de5fd2";
     */


    const DB_HOST = "br1010.hostgator.com.br";

    const DB_NAME = "hidigi88_veterinaria";

    const DB_USER = "hidigi88_felipe";

    const DB_PASS = "jvmczfjv123";
     


     private static function conectar(){



          if(empty(self::$conexao) && self::$destruir === false){



               try {



                    self::$conexao = new PDO('mysql:host='.self::DB_HOST.';dbname='.self::DB_NAME. ';charset=utf8', self::DB_USER, self::DB_PASS);

                    self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // self::$conexao->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');

                    if(!self::$conexao){



                         echo "Houve um erro ao efetuar a conex&atilde;o!";

                         return NULL;

                    }



               } catch(PDOException $e){



                    echo "N&atilde;o foi poss&iacute;vel conectar ao banco de dados! ".$e->getMessage();

               }



          } else if(self::$destruir === true && isset(self::$conexao)){



               self::$conexao = NULL;

          }



          return self::$conexao instanceof PDO ? self::$conexao : NULL;

     }



     public static function chamar(){



          return self::conectar();

     }



     public static function desconectar(){



          self::$destruir = true;

          return self::conectar();

     }



     public static function reconectar(){



          self::$destruir = false;

          return self::conectar();

     }

 }