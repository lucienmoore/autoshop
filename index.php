<?php 
require "pdo_connection.php";

if (!isset($_SESSION['user_id'])) {
    session_start();
}

$itemsPerPage = 6;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

$offset = ($page - 1) * $itemsPerPage;

if (!empty($selectedCategory)) {
    $totalItems = $conn->query("SELECT COUNT(*) FROM cards WHERE category_id = '$selectedCategory'")->fetchColumn();
} else {
    $totalItems = $conn->query("SELECT COUNT(*) FROM cards")->fetchColumn();
}

$totalPages = ceil($totalItems / $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Магазин</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<link rel="stylesheet" href="../autoshop/css/main.css">
</head>
<body>
	<?php require "UI/navbar.php";?>
	<div class="container features d-flex gap-5">
	<?php
		if (!empty($selectedCategory)) {
			$currentCategory = $selectedCategory;
			$one = $conn->query("SELECT cards.id, cards.name, cards.description, cards.image, cards.category_id, cards.price, cards.qty, category.title FROM cards INNER JOIN category ON cards.category_id = category.id WHERE category_id = '$currentCategory' LIMIT $itemsPerPage OFFSET $offset");
		} else {
			$one = $conn->query("SELECT cards.id, cards.name, cards.description, cards.image, cards.category_id, cards.price, cards.qty, category.title FROM cards INNER JOIN category ON cards.category_id = category.id LIMIT $itemsPerPage OFFSET $offset");
		}
	
		$categories = $conn->query("SELECT * FROM category");

		echo '<div class="col-3 mb-4">
				<h3>Категория:</h3>
				<select class="form-select" name="category" onchange="submitCategoryForm()">
				<option value="">Все</option>';

		foreach ($categories as $row) {
			$selected = "";
			if (isset($currentCategory) && $currentCategory == $row["id"]) {
				$selected = "selected";
			}
			echo '<option value="'.$row["id"].'" '.$selected.'>'.$row["title"].'</option>';
		}

		echo '</select></div>';
		echo '<div class="row gap-2 w-100">';
			foreach ($one as $row) {
				if (!isset($row['id'])) {
					echo '<h3>Товара данной категории сейчас нет!</h3>';
				}
				else {
					echo '
						<div class="card p-3" style="width: 18rem;">
							<img src="images/'.$row["image"].'" class="card-img-top" alt="...">
							<div class="card-body">
								<h5 class="card-title">'.$row["name"].'</h5>
								<p class="card-text">Цена: '.number_format($row["price"]).' руб.</p>
								<p class="card-text">Категория: '.$row["title"].'</p>
								<p class="card-text">В наличии: '.$row["qty"].'</p>
								<div class="btns">
									<a href="more.php?id='.$row["id"].'" class="btn btn-custom">Подробнее</a>
								</div>
							</div>
						</div>
					';
				}
			}
			echo '<div class="pagination justify-content-center"><ul class="pagination">';
			if ($page > 1) {
				echo '<li class="page-item"><a class="page-link btn-pagination" href="?category=' . $selectedCategory . '&page=' . ($page - 1) . '">Предыдущая</a></li>';
			}
			for ($i = 1; $i <= $totalPages; $i++) {
				$activeClass = ($i == $page) ? 'active' : '';
				echo '<li class="page-item ' . $activeClass . '"><a class="page-link btn-pagination" href="?category=' . $selectedCategory . '&page=' . $i . '">' . $i . '</a></li>';
			}
			if ($page < $totalPages) {
				echo '<li class="page-item"><a class="page-link btn-pagination" href="?category=' . $selectedCategory . '&page=' . ($page + 1) . '">Следующая</a></li>';
			}
			echo '</ul></div>';
			echo '</div>';
		?>
	</div>
	<?php require "UI/footer.php";?>

	<script>
		function submitCategoryForm() {
			var categorySelect = document.querySelector("select[name='category']");
			var categoryValue = categorySelect.value;

			var baseUrl = window.location.origin + window.location.pathname;
			var newUrl = baseUrl + "?category=" + categoryValue;
			window.location.href = newUrl; 
		}
	</script>
</body>
</html>
