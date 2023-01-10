<?php
    session_start();
    require_once ("Controller/UsuarioController.php");
    require_once ("Model/Usuario.php");
    //txtEmailRegistro txtNomeRegistro txtSenhaRegistro
    
    $usuarioController = new UsuarioController(); //instancia de UsuarioController
    
    $msg = "";
    
    if(filter_input(INPUT_GET, "msg",FILTER_SANITIZE_NUMBER_INT)){
        if(filter_input(INPUT_GET, "msg",FILTER_SANITIZE_NUMBER_INT) == 1){
            $msg = "<div class='alert alert-warning'>Faça o login para acessar o painel!</dic>";
        } else {
           $msg = "<div class='alert alert-info'>Você fez o logout!</dic>"; 
        }
    }
    
    if(filter_input(INPUT_POST,"txtEmailRegistro", FILTER_SANITIZE_STRING)){
        
        $usuario = new Usuario(); // Instancia de Usuario
        
        
        $usuario->setNome(filter_input(INPUT_POST,"txtNomeRegistro", FILTER_SANITIZE_STRING));
        $usuario->setEmail(filter_input(INPUT_POST,"txtEmailRegistro", FILTER_SANITIZE_STRING));
        $usuario->setSenha(md5(filter_input(INPUT_POST, "txtSenhaRegistro", FILTER_SANITIZE_STRING)));
        $usuario->setData(date("Y-m-d H:i:s"));
       
        $result = $usuarioController->Cadastrar($usuario);
        
        switch($result){
            case 1:
                $msg = "<div class='alert alert-succes'>Usuário cadastrado com sucesso!</dic>";
                break;
            
            case -1:
                $msg = "<div class='alert alert-warning'>Usuário já está cadastrado!</dic>";
                break;
            
            case -2:
                $msg = "<div class='alert alert-warning'>Dados Invalidos!</dic>";
                break;
            
            case -10:
                $msg = "<div class='alert alert-danger'>Houve um erro aou tentar cadastrar, tente novamente depois.</dic>";
                break;
        }
        
    }
    
    if(filter_input(INPUT_POST,"txtEmailLogin", FILTER_SANITIZE_STRING)){
        $usuario = $usuarioController->Autenticar(filter_input(INPUT_POST, "txtEmailLogin", FILTER_SANITIZE_STRING), filter_input(INPUT_POST, "txtSenhaLogin", FILTER_SANITIZE_STRING));
        
        if($usuario != null){
            
            $_SESSION["nomeusuario"] = $usuario->getNome();
            $_SESSION["email"] = $usuario->getEmail();
            $_SESSION["data"] = $usuario->getData();
                    
            header("Location: painel.php");
           
        } else {
            $msg = "<div class='alert alert-warning'>Usuário ou Invalidos!</dic>";
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu">
    </head>
    <body>
                <!--LOGIN
        https://bootsnipp.com/snippets/featured/login-amp-signup-forms-in-panel
        -->
        <div class="container">    
            <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
                <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Entrar</div>
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

                        <form id="frmEntrar" class="form-horizontal" role="form" method="post">

                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input id="login-username" type="text" class="form-control" name="txtEmailLogin" value="" placeholder="E-mail">                                        
                            </div>

                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input id="login-password" type="password" class="form-control" name="txtSenhaLogin" placeholder="password">
                            </div>

                            <div style="margin-top:10px" class="form-group">
                                <!-- Button -->
                                <div class="col-sm-12 controls">
                                    <input type="submit" name="btnEntrar" value="Entrar" class="btn btn-success" />
                                    <a href="#" onClick="$('#loginbox').hide();
                                            $('#signupbox').show()" class="btn btn-info">
                                        Registrar-se
                                    </a>
                                </div>
                            </div> 

                            <br>
                            <div>
                                <?= $msg; ?>
                            </div>

                        </form>     
                    </div>                     
                </div>  
            </div>
            <div id="signupbox" style="display:none; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Registrar-se</div>
                    </div>  
                    <div class="panel-body" >
                        <form id="frmRegistrar" method="POST" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label for="txtEmailRegistro" class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="txtEmailRegistro" placeholder="E-mail principal">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txtNomeRegistro" class="col-md-3 control-label">Nome</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="txtNomeRegistro" placeholder="Nome completo">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtSenhaRegistro" class="col-md-3 control-label">Senha</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="txtSenhaRegistro" placeholder="*******">
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- Button -->                                        
                                <div class="col-md-offset-3 col-md-9">
                                    <button id="btn-signup" type="submit" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Cadastrar</button>
                                    <a id="signinlink" href="#" onclick="$('#signupbox').hide();
                                            $('#loginbox').show()" class="btn btn-default">Voltar</a>
                                </div>
                            </div>

                            <div style="border-top: 1px solid #999; padding-top:20px"  class="form-group">                                        
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
        <!---LOGIN-->
        <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
        <script src="bootstrap/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd"></script>
    </body>
    
</html>
