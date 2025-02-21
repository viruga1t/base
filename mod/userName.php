<?php

function userName (int $idUser, $conn)
{
    $query_sv = "SELECT * FROM users WHERE id = $idUser";
    $stmt_sv = $conn->prepare($query_sv);
    if ($stmt_sv->execute()) {
        $result_sv = $stmt_sv->get_result();
        while ($row_sv = $result_sv->fetch_assoc()) {
            $user = ['login' => $row_sv['login'],
                'firstName' => $row_sv['firsname'],
                'lastName' => $row_sv['lastname'],
            ];
        }
        return $user;
    }
}