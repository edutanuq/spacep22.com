<?php
header("Content-type: text/html; charset=utf-8");
    session_start();
    require_once('classes/BD.class.php');
    BD::conn();

    if(!isset($_SESSION['email_logado'], $_SESSION['id_user'])){
        header("Location: index.php");
    }

    $pegaUser = BD::conn()->prepare("SELECT * FROM `usuarios` WHERE `email` = ?");
    $solicitacao = BD::conn()->prepare("SELECT * FROM usuarios INNER JOIN amigos ON usuarios.id!=amigos.id_amigo1 or usuarios.id!=amigos.id_amigo2 WHERE usuarios.id !=? ORDER BY RAND() LIMIT 1
");
    $solicitacao->execute(array($_SESSION['id_user']));
    $dadosSol = $solicitacao->fetch();
    $pegaUser->execute(array($_SESSION['email_logado']));
    $dadosUser = $pegaUser->fetch();

    if(isset($_GET['acao']) && $_GET['acao'] == 'sair'){
        unset($_SESSION['email_logado']);
        unset($_SESSION['id_user']);
        session_destroy();
        header("Location: index.php");
    }


    if(isset($_POST['curtir']) && $_POST['curtir'] == 'curtir'){
        $id_post =$_POST['id_post'];
        $verifica = BD::conn()->prepare("SELECT * FROM `curtidas` WHERE `id_post` = ? and `id_usuario` = ?");
        $verifica->execute(array($id_post,$_SESSION['id_user']));
        $verificacao = $verifica->fetch();
        if(empty($verificacao)){
            $curti = BD::conn()->prepare("INSERT INTO curtidas(id_usuario,id_post) VALUES (?,?)");
            $curti->execute(array($_SESSION['id_user'],$_POST['id_post']));
            header("Location: #$id_post");
        }
    }

    $pegaFotos= BD::conn()->prepare("SELECT fotoPost FROM `usuarios` INNER JOIN postagens ON usuarios.id=postagens.id_usuario inner join amigos ON postagens.id_usuario=amigos.id_amigo1 or postagens.id_usuario=amigos.id_amigo2 WHERE usuarios.id=? ORDER BY data ASC LIMIT 6
");

    $pegaFotos->execute(array($dadosUser['id']));
?>


<!DOCTYPE html>
<html>
<title>Editar meu Perfil</title>
<link rel="icon" type="image/png" href="images/favicon.png"/>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
        crossorigin="anonymous">

        <nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#"><b>SPACE.CP2</b></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="chat.php">
          <i class="fa fa-home"></i>
          Página Inicial
          </a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="perfil.php">
          <i class="fa fa-globe">
          </i>
          Perfil
          <span class="sr-only">(current)</span>

        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fa fa-envelope-o"></i>
          Mensagens
        </a>
      </li>
    </ul>
    <ul class="navbar-nav ">
    <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fa fa-bell"></i>
        </a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Pesquisar usuário" aria-label="Pesquisar usuário">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Pesquisar</button>
    </form>
  </div>
</nav>

<div class="container-fluid gedf-wrapper">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a href="perfil.php?user=<?php echo $dadosUser['id'];?>"><h4 class="w3-center"><?php echo $dadosUser['nome'];?></h4></a>
                        <p class="w3-center"><img src="img/<?php echo ($dadosUser['foto'] == '') ? 'default.jpg' : $dadosUser['foto'];?>" class="w3-circle" style="height:106px;width:106px" alt="Avatar"></p>
                        <hr>
                        <p><i class="fa fa-pencil fa-fw w3-margin-right w3-text-theme"></i> <?php echo ($dadosUser['profissao'] != '') ? $dadosUser['profissao'] : "Não informado";?></p>
                        <p><i class="fa fa-home fa-fw w3-margin-right w3-text-theme"></i> <?php echo ($dadosUser['cidade'] != '') ? $dadosUser['cidade'] : "Não informado";?></p>
                        <p><i class="fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme"></i> <?php echo $data=date("d F Y", strtotime($dadosUser['nascimento']));?></p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <a href="editar.php?user=<?php echo $dadosUser['id'];?>"><li class="list-group-item">Editar perfil</li><a>
                    </ul>
                </div>
            </div>

            <div class="col-md-6 gedf-main">  
<!------ Form ---------->
<div class="card gedf-card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <center>
                            <li class="nav-item">
                              
                                <a class="nav-link active" id="posts-tab" data-toggle="tab" roles="posts" aria-selected="true">Alterar seus dados</a>
                              </center>
                            </li>
                            </center>
                        </ul>
                    </div>
                    <div class="card-body">
<form method="post" enctype="multipart/form-data" action="editarUser.php">
	<div class="form-group input-group">
		<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
		 </div>
        <input name="nome" class="form-control" placeholder="Nome" type="text">

    <div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
		</div>
        <input name="snome" class="form-control" placeholder="Sobrenome" type="text">
  </div> <!-- form-group// -->
    <div class="form-group input-group">
    	<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-pencil"></i> </span>
		  </div>
        <input name="profissao" class="form-control" placeholder="Profissão" type="text">
    </div> <!-- form-group// -->
    <div class="form-group input-group">
    	<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-building"></i> </span>
		  </div>
		    <input name="cidade" class="form-control" placeholder="Cidade" type="text">
	</div> <!-- form-group// -->                                      
    <div class="form-group">
    <span class="input-group-btn">
        <input class="btn btn-default" type="file" value="Publicar" name="arquivo"></input>
    </span>
        <input type="submit" class="btn btn-primary btn-block" value="Publicar" id="botaoPost"></button>
  </div>
</form>


  <script>
// Accordion
function myFunction(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-theme-d1";
    } else { 
        x.className = x.className.replace("w3-show", "");
        x.previousElementSibling.className = 
        x.previousElementSibling.className.replace(" w3-theme-d1", "");
    }
}

// Used to toggle the menu on smaller screens when clicking on the menu button
function openNav() {
    var x = document.getElementById("navDemo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
}
</script>
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery_play.js"></script>


</body>
</html> 
