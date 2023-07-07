<?php

require('../config.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);

if($method === 'delete') {    // não existe verificação para o DELETE (filter_input) tem que usar uma verificação raiz
    
    parse_str(file_get_contents('php://input'), $input);

    $id = ($input['id'] ?? null);

    $id = filter_var($id);

    if($id) {     // Nesse caso não é necessário verificar se o id existe no nosso sistema, mas pode deixar, não tem problema
        
        $sql = $pdo->prepare("SELECT * FROM notes WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            
            $sql = $pdo->prepare("DELETE FROM notes WHERE id = :id");
            $sql->bindValue(':id', $id);
            $sql->execute();

            $array['result'] = [   // o retorno que vai ser dado é a mensagem de exclusão
                'Item excluído com sucesso'
            ];

        } else {
            $array['error'] = 'ID não encontrado';
        }

    } else {
        $array['error'] = 'Campo não preenchido';
    }

} else {
    $array['error'] = 'Método não permitido (apenas DELETE)';
}

require('../return.php');
?>