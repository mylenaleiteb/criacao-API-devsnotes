<?php

require('../config.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);

if($method === 'put') {    // não existe verificação para o PUT (filter_input) tem que usar uma verificação raiz
    
    parse_str(file_get_contents('php://input'), $input);

    //$id = (!empty($input['id'])) ? $input['id'] : null;
    //$title = (!empty($input['title'])) ? $input['title'] : null;
    //$body = (!empty($input['body'])) ? $input['body'] : null;
    
    $id = ($input['id'] ?? null);
    $title = ($input['title'] ?? null);
    $body = ($input['body'] ?? null);

    $id = filter_var($id);
    $title = filter_var($title);
    $body = filter_var($body);

    if($id && $title && $body) {     // Primeiro precisamos verificar se o id existe no nosso sistema para que de fato possamos atualizá-lo
        
        $sql = $pdo->prepare("SELECT * FROM notes WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            
            $sql = $pdo->prepare("UPDATE notes SET title = :title, body = :body WHERE id = :id");
            $sql->bindValue(':id', $id);
            $sql->bindValue(':title', $title);
            $sql->bindValue(':body', $body);
            $sql->execute();

            $array['result'] = [
                'id' => $id,
                'title' => $title,
                'body'=> $body               
            ];

        } else {
            $array['error'] = 'ID não encontrado';
        }

    } else {
        $array['error'] = 'Campos não preenchidos';
    }

} else {
    $array['error'] = 'Método não permitido (apenas PUT)';
}

require('../return.php');
?>