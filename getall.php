<?php

require('../config.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);

if($method === 'get') {

    $sql = $pdo->query("SELECT * FROM notes"); // fiz a requisição
    if($sql->rowCount() > 0) {   // verifiquei a requisição
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $item) {
            $array['result'][] = [
                'id' => $item['id'],
                'title' => $item['title']
            ];
        }
    }

} else {
    $array['error'] = 'Método não permitido (apenas GET)';
}

require('../return.php');
