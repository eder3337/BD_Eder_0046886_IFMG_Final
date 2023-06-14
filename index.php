<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>Página Inicial</title>
</head>

<body>
        <div class="container">
          <div class="jumbotron">
            <div class="row">
                <h2>Cadastro de projetos</h2>
            </div>
          </div>
            </br>
            <div class="row">
                <p>
                    <a href="create.php" class="btn btn-success">Novo projeto</a>
                </p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <!--<th scope="col">Id</th>-->
                            <th scope="col">Nome</th>
                            <th scope="col">Gerente</th>
                            <th scope="col">Montadora</th>
                           <!-- <th scope="col">Resp. montadora</th> -->
                           <!-- <th scope="col">E-mail montadora</th> -->
						   <!-- <th scope="col">Tel. montadora</th> -->
                            <th scope="col">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'banco.php';
                        $pdo = Banco::conectar();
                        $sql = 'SELECT p.id, p.nome_projeto, p.gerente_projeto, p.id_montadora, p.responsavel_montadora, p.email_montadora, p.telefone_montadora, m.nome AS nome_montadora FROM projeto p LEFT JOIN montadora m ON(p.id_montadora = m.id) ORDER BY p.id ASC';

                        foreach($pdo->query($sql)as $row)
                        {
                            echo '<tr>';
			                //echo '<th scope="row">'. $row['id'] . '</th>';
                            echo '<td>'. $row['nome_projeto'] . '</td>';
                            echo '<td>'. $row['gerente_projeto'] . '</td>';
                            echo '<td>'. $row['nome_montadora'] . '</td>';
                            //echo '<td>'. $row['responsavel_montadora'] . '</td>';
                            //echo '<td>'. $row['email_montadora'] . '</td>';
							//echo '<td>'. $row['telefone_montadora'] . '</td>';
                            echo '<td width=250>';
                            echo '<a class="btn btn-primary" href="read.php?id='.$row['id'].'">Info</a>';
                            echo ' ';
                            echo '<a class="btn btn-warning" href="update.php?id='.$row['id'].'">Editar</a>';
                            echo ' ';
                            echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Excluir</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        Banco::desconectar();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>
