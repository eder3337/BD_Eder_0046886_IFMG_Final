<?php
require 'banco.php';
//Acompanha os erros de validação

// Processar so quando tenha uma chamada post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
    $nomeProjetoErro = null;
    $gerenteProjetoErro = null;
    $montadoraErro = null;
    $responsavelMontadoraErro = null;
    $emailMontadoraErro = null;
	$telefoneMontadoraErro = null;

    if (!empty($_POST)) {
        $validacao = True;
        $novoUsuario = False;
		
        if (!empty($_POST['nome_projeto'])) {
            $nomeProjeto = $_POST['nome_projeto'];
        } else {
            $nomeProjetoErro = 'Por favor digite o nome do projeto!';
            $validacao = False;
        }


        if (!empty($_POST['gerente_projeto'])) {
            $gerenteProjeto = $_POST['gerente_projeto'];
        } else {
            $gerenteProjetoErro = 'Por favor digite o nome do gerente do projeto!';
            $validacao = False;
        }


        if (!empty($_POST['montadora'])) {
            $montadora = $_POST['montadora'];
        } else {
            $montadoraErro = 'Por favor digite o nome da montadora!';
            $validacao = False;
        }

		if (!empty($_POST['responsavel_montadora'])) {
            $responsavelMontadora = $_POST['responsavel_montadora'];
        } else {
            $responsavelMontadoraErro = 'Por favor digite o nome do responsavel da montadora!';
            $validacao = False;
        }

        if (!empty($_POST['email_montadora'])) {
            $emailMontadora = $_POST['email_montadora'];
            if (!filter_var($_POST['email_montadora'], FILTER_VALIDATE_EMAIL)) {
                $emailMontadoraErro = 'Por favor digite um endereço de e-mail válido!';
                $validacao = False;
            }
        } else {
            $emailMontadoraErro = 'Por favor digite um endereço de e-mail!';
            $validacao = False;
        }
		
		if (!empty($_POST['telefone_montadora'])) {
            $telefoneMontadora = $_POST['telefone_montadora'];
        } else {
            $telefoneMontadoraErro = 'Por favor digite o telefone da montadora!';
            $validacao = False;
        }
    }

//Inserindo no Banco:
    if ($validacao) {
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO projeto(nome_projeto, gerente_projeto, id_montadora, responsavel_montadora, email_montadora, telefone_montadora) VALUES(?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($nomeProjeto, $gerenteProjeto, $montadora, $responsavelMontadora, $emailMontadora, $telefoneMontadora));
        Banco::desconectar();
        header("Location: index.php");
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>Adicionar Projeto</title>
</head>

<body>
<div class="container">
    <div clas="span10 offset1">
        <div class="card">
            <div class="card-header">
                <h3 class="well"> Adicionar Projeto </h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="create.php" method="post">

                    <div class="control-group  <?php echo !empty($nomeProjetErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Nome projeto*</label>
                        <div class="controls">
                            <input size="50" class="form-control" name="nome_projeto" type="text" placeholder="Nome projeto"
                                   value="<?php echo !empty($nomeProjeto) ? $nomeProjeto : ''; ?>">
                            <?php if (!empty($nomeProjetoErro)): ?>
                                <span class="text-danger"><?php echo $nomeErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php echo !empty($gerenteProjetoErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Gerente projeto*</label>
                        <div class="controls">
                            <input size="80" class="form-control" name="gerente_projeto" type="text" placeholder="Gerente projeto"
                                   value="<?php echo !empty($gerenteProjeto) ? $gerenteProjeto : ''; ?>">
                            <?php if (!empty($gerenteProjetoErro)): ?>
                                <span class="text-danger"><?php echo $gerenteProjetoErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php echo !empty($montadoraErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Montadora*</label>
                        <div class="controls">
                            
					<?php
                        $pdo = Banco::conectar();
                        $sql = 'SELECT * from montadora';

						echo '<select name="montadora" size="1">';
                        foreach($pdo->query($sql)as $row)
                        {
                            echo '<option value="'. $row['id'] .'">'. $row['nome'] . '</option>';
                        }
						
						echo '</select>';
                        Banco::desconectar();
                     ?>
							
                            <?php if (!empty($montadoraErro)): ?>
                                <span class="text-danger"><?php echo $montadoraErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
					
					<div class="control-group <?php echo !empty($responsavelMontadoraErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Responsavel montadora*</label>
                        <div class="controls">
                            <input size="35" class="form-control" name="responsavel_montadora" type="text" placeholder="Responsavel montadora"
                                   value="<?php echo !empty($responsavelMontadora) ? $responsavelMontadora : ''; ?>">
                            <?php if (!empty($responsavelMontadoraErro)): ?>
                                <span class="text-danger"><?php echo $responsavelMontadoraErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php !empty($emailMontadoraErro) ? '$emailMontadoraErro ' : ''; ?>">
                        <label class="control-label">E-mail montadora*</label>
                        <div class="controls">
                            <input size="40" class="form-control" name="email_montadora" type="text" placeholder="E-mail Montadora"
                                   value="<?php echo !empty($emailMontadora) ? $emailMontadora : ''; ?>">
                            <?php if (!empty($emailMontadoraErro)): ?>
                                <span class="text-danger"><?php echo $emailMontadoraErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

					<div class="control-group <?php !empty($telefoneMontadoraErro) ? '$telefoneMontadoraErro ' : ''; ?>">
                        <label class="control-label">Telefone montadora*</label>
                        <div class="controls">
                            <input size="40" class="form-control" name="telefone_montadora" type="text" placeholder="Telefone Montadora"
                                   value="<?php echo !empty($telefoneMontadora) ? $telefoneMontadora : ''; ?>">
                            <?php if (!empty($telefoneMontadoraErro)): ?>
                                <span class="text-danger"><?php echo $telefoneMontadoraErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
					
                    <div class="form-actions">
                        <br/>
                        <button type="submit" class="btn btn-success">Adicionar</button>
                        <a href="index.php" type="btn" class="btn btn-default">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="assets/js/bootstrap.min.js"></script>
</body>

</html>

