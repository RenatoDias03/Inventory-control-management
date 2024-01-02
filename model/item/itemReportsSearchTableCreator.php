<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$itemDetailsSearchSql = 'SELECT * FROM item';
	$itemDetailsSearchStatement = $conn->prepare($itemDetailsSearchSql);
	$itemDetailsSearchStatement->execute();

	$output = '<table id="itemReportsTable" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Equipamento ID</th>
						<th>Item Numero</th>
						<th>Item Nome</th>
						<th>Stock</th>
						<th>Status</th>
						<th>Descricao</th>
					</tr>
				</thead>
				<tbody>';
	
	// Criar linhas de tabela a partir dos dados selecionados
	while($row = $itemDetailsSearchStatement->fetch(PDO::FETCH_ASSOC)){
		$output .= '<tr>' .
						'<td>' . $row['productID'] . '</td>' .
						'<td>' . $row['itemNumber'] . '</td>' .
						//'<td>' . $row['itemName'] . '</td>' .
						'<td><a href="#" class="itemDetailsHover" data-toggle="popover" id="' . $row['productID'] . '">' . $row['itemName'] . '</a></td>' .
						
						'<td>' . $row['stock'] . '</td>' .
						'<td>' . $row['status'] . '</td>' .
						'<td>' . $row['description'] . '</td>' .
					'</tr>';
	}
	
	$itemDetailsSearchStatement->closeCursor();
	
	$output .= '</tbody>
					<tfoot>
						<tr>
							<th>Equipamento ID</th>
						<th>Item Numero</th>
						<th>Item Nome</th>
						<th>Stock</th>
						<th>Status</th>
						<th>Descricao</th>
						</tr>
					</tfoot>
				</table>';
	echo $output;
?>