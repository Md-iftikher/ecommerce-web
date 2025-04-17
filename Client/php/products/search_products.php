<?php
include_once __DIR__ . "/../config.php";

$input = json_decode(file_get_contents("php://input"), true);
$pattern = $input['pattern'] ?? '';
$sort = $input['sort'] ?? '';

switch($sort) {
    case 'name_asc':
        {
            $order_by = "order by p.product_name asc";
            break;
        }
    case 'name_desc':
        {
            $order_by = "order by p.product_name desc";
            break;
        }
    case 'price_asc':
        {
            $order_by = "order by p.price asc";
            break;
        }
    case 'price_desc':
        {
            $order_by = "order by p.price desc";
            break;
        }
    case 'latest':
        {
            $order_by = "order by p.product_id desc";
            break;
        }
    case 'popular':
        {
            $order_by = "order by ordered_quantity desc";
            break;
        }
    default :
        {
            $order_by = "order by p.product_name asc";
            break;
        }
}


// using order_quantity to order by most popular 
// coalesce returns the next not null value
// group by works because product_id is the primary key so all other non-aggregated selected columns are functionally dependent on it (only works in mysql)
$sql = "
select p.*, c.category_name, COALESCE(sum(o.quantity), 0) as ordered_quantity
from products p
inner join categories c
on p.category_id = c.category_id
left join order_items o 
on o.product_id = p.product_id
WHERE p.product_name LIKE ? or c.category_name LIKE ?
group by p.product_id, c.category_name
$order_by;
";

$stmt = $conn->prepare($sql);
$likePattern = "%" . $pattern . "%";
$stmt->bind_param("ss", $likePattern, $likePattern);
$stmt->execute();

$result = $stmt->get_result();

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);
?>