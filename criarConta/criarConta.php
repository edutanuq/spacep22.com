<?php 
session_start();
    require_once('BD.class.php');
    BD::conn();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
    <title>spacecpII | Criar conta</title>
    <style>
        body {
background-image:url("images/spaceman.gif");
background-attachment:fixed;
background-size:100%;
background-repeat:no-repeat;
}
  </style>
</head>

         <?php
                if(isset($_POST['acao']) && $_POST['acao'] == 'logar'){
                    $email = strip_tags(trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING)));
                    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
                    if($email == ''){
                        header("Location: #openModal");
                    }if($senha == ''){
                        header("Location: #openModal1");
                    }else{
                        $pegaUser = BD::conn()->prepare("SELECT * FROM `usuarios` WHERE `email` = ? AND `senha` = ?");
                        $pegaUser->execute(array($email, $senha));

                        if($pegaUser->rowCount() == 0){
                            echo 'Não encontramos este login!';
                        }else{
                            $agora = date('Y-m-d H:i:s');
                            $limite = date('Y-m-d H:i:s', strtotime('+2 min'));
                            $update = BD::conn()->prepare("UPDATE `usuarios` SET `horario` = ?, `limite` = ? WHERE `email` = ? AND `senha` = ?");
                            if( $update->execute(array($agora, $limite, $email, $senha)) ){
                                while($row = $pegaUser->fetchObject()){
                                    $_SESSION['email_logado'] = $email;
                                    $_SESSION['id_user'] = $row->id;
                                    header("Location: chat.php");
                                }
                            }//se atualizou
                        }
                    }
                }
                 if(isset($_POST['action']) && $_POST['action'] == 'registrar'){
                      $snome = $_POST['snome'];
                      $name = $_POST['nome'];
                      $nome = $name . ' '. $snome;
                      $email = $_POST['email'];
                      $senha = base64_encode($_POST['senha']);
                      $sexo = $_POST['sexo'];
                      $nascimento = $_POST['nascimento'];
                      if($email == ''){
                          header("Location: #openModal");
                      }if($senha == ''){
                          header("Location: #openModal1");
                      }else{
                          $pegaUser = BD::conn()->prepare("SELECT * FROM `usuarios` WHERE `email` = ?");
                          $pegaUser->execute(array($email));

                          if($pegaUser->rowCount() >= 1){
                              echo 'Email já registrado!';
                          }else{
                              $agora = date('Y-m-d H:i:s');
                              $limite = date('Y-m-d H:i:s', strtotime('+2 min'));
                              $update = BD::conn()->prepare("INSERT INTO usuarios(nome, email, senha, nascimento, horario, limite, sexo) VALUES(?, ?, ?, ?, ?, ?, ?)");
                              $update->execute(array($nome, $email, $senha, $nascimento, $agora, $limite, $sexo));
                              $pegaUser->execute(array($email));
                                  while($row = $pegaUser->fetch()){
                                      $_SESSION['email_logado'] = $email;
                                      $_SESSION['id_user'] = $row['id'];
                                      echo "<script>alert('$senha')</script>";
                                      header("Location: chat.php");
                                  }
                              }//se atualizou
                          }
                    }
                  
            ?>

<div class="formulario">
<!-- Navbar -->

  
</div>
    <form name="form_pesquisa" id="register" method="post" action="">
    <center>
    <div class="container-login100" >
			<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
            <span class="login100-form-title p-b-49">
            
            <h1 style="font-size:60%;">Pronto para voar ao seu lado!</h1>
      
                  <br>
                  <input type="text" name="nome" style="width: 400px" placeholder="Nome"  class="input100" id="inputp" required />
                  <input type="text" name="snome" style="width: 400px" placeholder="Sobrenome"  class="input100" id="inputp" required />
                  <input type="email" name="email" style="width: 400px" placeholder="Email"  class="input100" id="inputp" required/>
                  <input type="password" name="senha" style="width: 400px" placeholder="Senha"  class="input100" id="inputp" required/>
                  <input type="date" name="nascimento" style="width: 400px"  class="input100" id="inputp" max="2023-01-01" required/>
                  <input type="radio" name="sexo" value="0" />
                  <h1 style="font-size:40%;">Feminino</h1>
                  <input type="radio" name="sexo" style="width: 50px height: 50px" value="1" />
                  <h1 style="font-size:40%;">Masculino</h1>
                  <input type="hidden" name="action" value="registrar" id="cadastro" /><br>
                  <input type="submit" name="cadastrar" value="Criar conta" id="bottom" class="input100" class="botao right">
                  </center>
                 
                  
                  </div>
    </center>
            </form> 
    </div>
    </div>
                  


  <!-- End Grid -->
  </div>
  
<!-- End Page Container -->
</div>
<br>


    <script src="myscript.js"></script>
    <script src="myscript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    
</body>
</html>


</body>
</html> 
