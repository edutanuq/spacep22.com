<?php 
session_start();
    require_once('BD.class.php');
    BD::conn();
?>

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

<!DOCTYPE html>
<html lang="en">
<head>
	<title>SPACE.CP2</title>
</head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
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
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/astro.gif');">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
            <form name="form_pesquisa" id="form_pesquisa" method="post" action="">
					<span class="login100-form-title p-b-49">
						Entre na Nave!
					</span>

					
						<span class="label-input100">Email</span>
						<div class="wrap-input100 validate-input m-b-23" data-validate = "Digite um email válido: abc@xyz.com">
						<input type="text" name="email" id="pesquisaCliente"  placeholder="Digite seu email" class="input100" />
						<span class="focus-input100" data-symbol="&#xf206;"></span>
					</div>

					
					<div class="wrap-input100 validate-input" data-validate = "Uma senha é requerida">
              <input type="password" name="senha" id="pesquisaCliente"  placeholder="Senha"  class="input100"/>
						<span class="focus-input100" data-symbol="&#xf190;"></span>
					</div>
					
					<div class="text-right p-t-8 p-b-31">
						<a href="esqueceusenha.html">
							Esqueceu sua senha?
						</a>
					</div>
					
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<input type="hidden" name="acao" value="logar" /> 
                            <input type="submit" name="Entrar" value="PREPARAR PARA DECOLAR!" class="input100" id="bottom" class="botao right">
						</div>
					</div>
                    </form>
					<div class="txt1 text-center p-t-54 p-b-20">
						<span>
	

						<div class="text-center p-t-136">
						<a class="txt2" href="criarConta.php">
							Crie sua conta!
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                            
                            </div>
                            
                            </a>
                
						
					
				</form>
			</div>
		</div>
	</div><a href="./portfolio/portfolio.html">
	<small> Conheça os criadores </small>

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>