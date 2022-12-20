<script type="text/javascript" src="js/jquery-2.1.0.js"></script>
 <script type="text/javascript">
  $(document).ready(function(){


    // aqui a função ajax que busca os dados em outra pagina do tipo html, não é json
    function load_dados(valores, page, div)
    {
        $.ajax
            ({
                type: 'POST',
                dataType: 'html',
                url: page,
                beforeSend: function(){//Chama o loading antes do carregamento

        },
                data: valores,
                success: function(msg)
                {

                    var data = msg;
              $(div).html(data).fadeIn();                       }
            });
    }

    //Aqui eu chamo o metodo de load pela primeira vez sem parametros para pode exibir todos
    load_dados(null, 'pesquisa.php', '#MostraPesq');


    //Aqui uso o evento key up para começar a pesquisar, se valor for maior q 0 ele faz a pesquisa
    $('#pesquisaCliente').keyup(function(){

        var valores = $('#form_pesquisa').serialize()//o serialize retorna uma string pronta para ser enviada

        //pegando o valor do campo #pesquisaCliente
        var $parametro = $(this).val();

        if($parametro.length >= 1)
        {
            load_dados(valores, 'pesquisa.php', '#MostraPesq');
        }else
        {
            load_dados(null, 'pesquisa.php', '#MostraPesq');
        }
    });

  });
  </script>


  <?php 
        $verificaSol = BD::conn()->prepare("SELECT * FROM `solicitacoes` INNER JOIN usuarios on usuarios.id=solicitacoes.id_usuario1 OR usuarios.ID=solicitacoes.id_usuario2 WHERE  usuarios.id != ? AND solicitacoes.id_usuario1 = ? or solicitacoes.id_usuario2 = ? and usuarios.id != ? LIMIT 3");
        $verificaSol->execute(array($_SESSION['id_user'], $_SESSION['id_user'], $_SESSION['id_user'], $_SESSION['id_user']));
        $numSol = $verificaSol->rowCount();
           

  ?>
<!-- Navbar -->
<nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#"><b><i class="fa fa-rocket"></i></b></a>
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
    </ul>
    <a href="#" class="w3-padding-large nav-link" title="Notificações"><i class="fa fa-bell"></i><span class="w3-badge w3-right w3-small w3-green"><?php echo $numSol; ?></span></a>
    <div class="w3-dropdown-content">
    <?php while ($row = $verificaSol->fetch()) {
            $id = $row['id_solicitacao'];
            $foto = ($row['foto'] == '') ? 'default.jpg' : $row['foto'];
            $id_amigo1 = $row['id_usuario1'];
            $id_amigo2 = $row['id_usuario2'];
            $nome = $row['nome'];
            echo  "<a href='#' ></a><img src='img/". $foto ."' alt='Avatar' class='w3-left w3-circle w3-margin-right' style='width:30px'>";
            echo $nome." enviou uma solicitação";
            echo "<div class='w3-half'><form method='post'> <button class='w3-btn w3-green w3-btn-block w3-section' name='botao' value='1' type='submit'><i class='fa fa-check'></i></button></div>". "<div class='w3-half'><button class='w3-btn w3-red w3-btn-block w3-section' name='botao' value='0' type='submit'><i class='fa fa-remove'></i></button></div></form>";
            if (isset($_POST['botao']) && $_POST['botao'] == "0" && !empty($verificaSol) && isset($id)){
                  $solicitacao = BD::conn()->prepare("DELETE FROM solicitacoes WHERE id_solicitacao=?");
                  $solicitacao->execute(array($id));
              }
       if (isset($_POST['botao']) && $_POST['botao'] == "1" && !empty($verificaSol)){
                  $solicitacao = BD::conn()->prepare("DELETE FROM solicitacoes WHERE id_solicitacao=?");
                  $solicitacao->execute(array($id));
                  $solicitacao3 = BD::conn()->prepare("INSERT INTO amigos(id_amigo1,id_amigo2) VALUES(?,?)");
                  $solicitacao3->execute(array($id_amigo1, $id_amigo2));
                  $solicitacao = BD::conn()->prepare("DELETE FROM solicitacoes WHERE id_usuario1=? AND id_usuario2=? or id_usuario2=? AND id_usuario1=? or id_usuario2=? and id_usuario2=? or id_usuario1=? and id_usuario1=?");
                  $solicitacao->execute(array($id_amigo1,$id_amigo2,$id_amigo2,$id_amigo1,$id_amigo1,$id_amigo1,$id_amigo2,$id_amigo2));
                  echo "<<script>window.location='chat.php';</script>";
              }
    }
      

    ?>
    </div>
  </li>
  <form name="form_pesquisa" id="form_pesquisa" method="post" action="">
              <input type="text" name="pesquisaCliente" id="pesquisaCliente"  placeholder="Pesquisar usuario..."  class="w3-border w3-padding"/>
        </form>
        <button class="btn btn-outline-danger my-2 my-sm-0"><a href="?acao=sair" title="Sair da conta"><img src="img/sair 22.png" class="w3-circle" style="height:25px;width:25px" alt="Avatar"></a></li>
 </ul>
</div>
 </div>
        </nav>


<!-- Navbar on small screens -->





