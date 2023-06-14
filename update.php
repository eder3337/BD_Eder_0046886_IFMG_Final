<?php

require 'banco.php';

$id = null;
if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: index.php");
}

if (!empty($_POST)) {

    $nomeProjetoErro = null;
    $gerenteProjetoErro = null;
    $montadoraErro = null;
    $responsavelMontadoraErro = null;
    $emailMontadoraErro = null;
	$telefoneMontadoraErro = null;

    $nomeProjeto = $_POST['nome_projeto'];
    $gerenteProjeto = $_POST['gerente_projeto'];
    $montadora = $_POST['montadora'];
    $responsavelMontadora = $_POST['responsavel_montadora'];
    $emailMontadora = $_POST['email_montadora'];
	$telefoneMontadora = $_POST['telefone_montadora'];
	$partNumberOem = $_POST['part_number_oem'];
    $partNumberFundido = $_POST['part_number_fundido'];
	$partNumberUsinado = $_POST['part_number_usinado'];

    //Validação
    $validacao = true;
	
    if (empty($nomeProjeto)) {
        $nomeProjetoErro = 'Por favor digite o nome do projeto!';
        $validacao = false;
    }

    if (empty($gerenteProjeto)) {
        $gerenteProjetoErro = 'Por favor digite o nome do gerente do projeto!';
        $validacao = false;
    }

    if (empty($montadora)) {
        $montadoraErro = 'Por favor digite o nome da montadora!';
        $validacao = false;
    }

    if (empty($responsavelMontadora)) {
        $responsavelMontadoraErro = 'Por favor digite o nome do responsavel da montadora!';
        $validacao = false;
    }
	
	if (empty($emailMontadora)) {
        $emailMontadoraErro = 'Por favor digite um endereço de e-mail!';
        $validacao = false;
    } else if (!filter_var($emailMontadora, FILTER_VALIDATE_EMAIL)) {
        $emailMontadoraErro = 'Por favor digite um endereço de e-mail válido!';
        $validacao = false;
    }
	
	if (empty($telefoneMontadora)) {
        $sexoErro = 'Por favor preenche o campo!';
        $validacao = false;
    }

    // update data
    if ($validacao) {
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE projeto set nome_projeto = ?, gerente_projeto = ?, id_montadora = ?, responsavel_montadora = ?, email_montadora = ?, telefone_montadora = ?, part_number_oem = ?, part_number_fundido = ?, part_number_usinado = ? WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($nomeProjeto, $gerenteProjeto, $montadora, $responsavelMontadora, $emailMontadora, $telefoneMontadora, $partNumberOem, $partNumberFundido, $partNumberUsinado, $id));
        Banco::desconectar();
        header("Location: index.php");
    }
} else {
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT p.id, p.nome_projeto, p.gerente_projeto, p.id_montadora, p.responsavel_montadora, p.email_montadora, p.telefone_montadora, p.part_number_oem, p.part_number_usinado, p.part_number_fundido, m.nome AS nome_montadora FROM projeto p LEFT JOIN montadora m ON(p.id_montadora = m.id) WHERE p.id = ? ORDER BY p.id ASC';
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $data = $q->fetch(PDO::FETCH_ASSOC);
	
	$nomeProjeto = $data['nome_projeto'];
    $gerenteProjeto = $data['gerente_projeto'];
    $montadora = $data['id_montadora'];
    $responsavelMontadora = $data['responsavel_montadora'];
    $emailMontadora = $data['email_montadora'];
	$telefoneMontadora = $data['telefone_montadora'];
	$partNumberOem = $data['part_number_oem'];
    $partNumberFundido = $data['part_number_fundido'];
	$partNumberUsinado = $data['part_number_usinado'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- using new bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Atualizar Projeto</title>
</head>

<body>
<div class="container">

    <div class="span10 offset1">
        <div class="card">
            <div class="card-header">
                <h3 class="well"> Atualizar Projeto </h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="update.php?id=<?php echo $id ?>" method="post">

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
                        $sql = 'SELECT * from montadora';
						$selected = "";
						
						echo '<select name="montadora" size="1">';
                        foreach($pdo->query($sql)as $row)
                        {
							if($row['id'] == $montadora){
								$selected = " selected";
							}else{
								$selected = "";
							}
                            echo '<option value="'. $row['id'] .'"'.$selected.'>'. $row['nome'] . '</option>';
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
					
					<!-- part numbers -->
					
					<div class="control-group">
                        <label class="control-label">Part Number OEM</label>
                        <div class="controls">
                            <input size="35" class="form-control" name="part_number_oem" type="text" placeholder="Part Number OEM"
                                   value="<?php echo !empty($partNumberOem) ? $partNumberOem : ''; ?>">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Part Number fundido</label>
                        <div class="controls">
                            <input size="35" class="form-control" name="part_number_fundido" type="text" placeholder="Part Number fundido"
                                   value="<?php echo !empty($partNumberFundido) ? $partNumberFundido : ''; ?>">
                        </div>
                    </div>

					<div class="control-group">
                        <label class="control-label">Part Number usinado</label>
                        <div class="controls">
                            <input size="35" class="form-control" name="part_number_usinado" type="text" placeholder="Part Number usinado"
                                   value="<?php echo !empty($partNumberUsinado) ? $partNumberUsinado : ''; ?>">
                        </div>
                    </div>
		 <!-- -->
                    <div class="form-actions">
                        <br/>
                        <button type="submit" class="btn btn-success">Atualizar</button>
                        <a href="index.php" type="btn" class="btn btn-default">Voltar</a>
                    </div>
                </form>
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
