<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$uPrice = 0;
	$qty = 0;
	$totalPrice = 0;
	
	$saleDetailsSearchSql = 'SELECT * FROM sale';
	$saleDetailsSearchStatement = $conn->prepare($saleDetailsSearchSql);
	$saleDetailsSearchStatement->execute();

	$output = '<table id="saleDetailsTable" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
				<thead>
					<tr>
						<th>RecolhaID</th>
						<th>Item Numero</th>
						<th>Tecnico ID</th>
						<th>Tecnico Nome</th>
						<th>Item Nome</th>
						<th>Data Recolha</th>
						<th>Quantidade</th>
					</tr>
				</thead>
				<tbody>';
	
	// Criar linhas de tabela a partir dos dados selecionados
	while($row = $saleDetailsSearchStatement->fetch(PDO::FETCH_ASSOC)){
		$qty = $row['quantity'];
		$output .= '<tr>' .
						'<td>' . $row['saleID'] . '</td>' .
						'<td>' . $row['itemNumber'] . '</td>' .
						'<td>' . $row['customerID'] . '</td>' .
						'<td>' . $row['customerName'] . '</td>' .
						'<td>' . $row['itemName'] . '</td>' .
						'<td>' . $row['saleDate'] . '</td>' .
						'<td>' . $row['quantity'] . '</td>' .
					'</tr>';
	}
	
	$saleDetailsSearchStatement->closeCursor();
	
	$output .= '</tbody>
					<tfoot>
						<tr>
							<th>RecolhaID</th>
						<th>Item Numero</th>
						<th>Tecnico ID</th>
						<th>Tecnico Nome</th>
						<th>Item Nome</th>
						<th>Data Recolha</th>
						<th>Quantidade</th>
					</tfoot>
				</table>';
	echo $output;
?>


