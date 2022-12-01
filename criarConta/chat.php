
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

<title>Página Inicial</title>
<link rel="icon" type="image/png" href="images/favicon.png"/>
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
      <li class="nav-item active">
        <a class="nav-link" href="#">
          <i class="fa fa-home"></i>
          Página Inicial
          <span class="sr-only">(current)</span>
          </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="perfil.php?user=<?php echo  $_SESSION['id_user']; ?>">
          <i class="fa fa-globe">
          </i>
          Perfil
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fa fa-envelope-o">
          </i>
          Notificações
        </a>
      </li>
    </ul>
    <ul class="navbar-nav ">
    <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fa fa-bell">
          </i>
        </a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0" name="form_pesquisa" id="form_pesquisa" method="post" action="">
      <input class="form-control mr-sm-2" type="text" placeholder="Pesquisar usuário" aria-label="Pesquisar usuário" name="pesquisaCliente" id="pesquisaCliente">
      <button class="btn btn-outline-success mr-sm-2" type="submit">Pesquisar</button>
    </form>
  <button class="btn btn-outline-danger my-2 my-sm-0"><a href="?acao=sair" title="Sair da conta"><img src="img/sair 22.png" class="w3-circle" style="height:25px;width:25px" alt="Avatar"></a></li>
 </ul>
</div>
 </div>
        </nav>


<div class="container-fluid gedf-wrapper">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a href="perfil.php?user=<?php echo $dadosUser['id'];?>"><h4 class="w3-center"><?php echo $dadosUser['nome'];?></h4></a>
                        <p class="w3-center"><img src="img/<?php echo ($dadosUser['foto'] == '') ? 'default.jpg' : $dadosUser['foto'];?>" class="w3-circle" style="height:106px;width:106px;border-radius:50%" alt="Avatar"></p>
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
            
                <!--- \\\\\\\Post-->
                <div class="card gedf-card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="posts-tab" data-toggle="tab" roles="posts" aria-selected="true">Prepare-se para decolar</a>
                            </li>
                           
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
                                <div class="form-group">
                                <form method="post" enctype="multipart/form-data" action="recebeUpload.php">
                                    <textarea class="form-control" id="FormPost" maxlength="144" name="post" rows="3" placeholder="Lá vaaaamos nós!"></textarea>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="images-tab">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile">
                                        <span class="input-group-btn">
                                        <label class="custom-file-label" for="customFile" type="file" value="Publicar" name="arquivo" name="arquivo">Carregar imagem</label>
                                        </span>
                                    </div>
                                </div>
                                <div class="py-4"></div>
                            </div>
                        </div>
                        <div class="btn-toolbar justify-content-between">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary" id="botaoPost" type="submit" value="Publicar" id="botaoPost">Publicar</button>
                                <span class="input-group-btn">
                            <input class="btn btn-default" type="file" value="Publicar" name="arquivo"
                                </span>

                            </div>
                        </div>
                        </form>
                    </div>
                </div>

                <!-- Post /////-->


                
                <?php
                    $pegaUsuarios = BD::conn()->prepare("SELECT DISTINCT usuarios.id, nome,conteudo,fotoPost,data,foto,id_post FROM `usuarios` INNER JOIN postagens ON usuarios.id=postagens.id_usuario LEFT join amigos ON postagens.id_usuario=amigos.id_amigo1 or postagens.id_usuario=amigos.id_amigo2 WHERE id_amigo1=? or id_amigo2=? OR usuarios.id=? ORDER BY data asc");
                    $pegaCurtidas = BD::conn()->prepare("SELECT count(*) AS curtidas FROM curtidas WHERE id_post=?
");
                    $pegaUsuarios->execute(array($_SESSION['id_user'],$_SESSION['id_user'],$_SESSION['id_user']));
                    while($row = $pegaUsuarios->fetch()){
                        $foto = ($row['foto'] == '') ? 'default.jpg' : $row['foto'];
                        $id_post = $row['id_post'];
                        $fotopost =  $row['fotoPost'];
                        $conteudo = $row['conteudo'];
                        $nome = $row['nome'];
                        $data = $row['data'];
                        $pegaCurtidas->execute(array($id_post));
                        while($coluna = $pegaCurtidas->fetch()){
                 ?>

        <div id="<?php echo $id_post;?>">

        <br><div class="card gedf-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mr-2">
                                    <img width="45" style="border-radius:50%" src="img/<?php echo ($dadosUser['foto'] == '') ? 'default.jpg' : $dadosUser['foto'];?>"" alt="">
                                </div>
                                <div class="ml-2">
                                    <div class="h5 m-0"><?php echo $dadosUser['nome'];?></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i> <?php echo $data;?></div>

                        <p class="card-text">
                            <?php echo $conteudo = $row['conteudo'];?>
                        </p>
                    </div>
                    <?php if($fotopost != '')  echo  "<img src='img/". $fotopost ."' style='width:50%;' class='w3-margin-bottom'>"; ?>
                    <form id="form_pesquisa" method="post" action="">
                    <div class="card-footer">
                        <p class="w3-margin-bottom" ><?php echo $coluna['curtidas'] ?> Curtidas</p>
                        <input type="hidden" name="id_post" value="<?php echo $id_post ?>" placeholder="">
                        <button type="submit" class="btn btn-primary" name="curtir" value="curtir"><i class="fa fa-thumbs-up"></i> Curtir</button>
                       
                    </div>
                    </form>

                </div>
                <!-- Post /////-->
        </div>
      <?php }} ?>

                        <!--- \\\\\\\Post-->
    
                    </div>
                    
                   
                <!-- Post /////-->
            </div>
        </div>
    </div>
</html>