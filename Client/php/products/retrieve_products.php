<?php
include_once __DIR__ . "/../config.php";

$sql = "
select p.*, c.category_name
from products p
inner join categories c
on p.category_id = c.category_id
order by p.product_name;
";

$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row; // Add each row (assoc array) to the array
    }
}


echo json_encode($products);

?>



    
