<?php
include_once "connect.php";
function MinOpt ($conn, $brand, $model) {
    $minPrice = 0;
    $query = "SELECT * FROM bd WHERE oboz = '$model' and brend = '$brand' LIMIT 0,1";
    $stmt = $conn->prepare($query);
    if ($stmt->execute())
    {
        $result = $stmt->get_result();
        while ($row=$result->fetch_assoc()) {
            $newModel = $row['onliner'];
            $query = "SELECT * FROM bd_svodni WHERE brend = '$brand' and model = '$newModel' ORDER BY opt DESC";
            $stmt = $conn->prepare($query);
            if ($stmt->execute())
            {
                $result = $stmt->get_result();
                while ($row=$result->fetch_assoc()) {
                    $minPrice = $row['opt'];
                }
            }
        }
    }
        $minPrice = "(".$minPrice.")";
        return $minPrice;
}




?>
