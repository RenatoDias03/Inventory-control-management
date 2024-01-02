<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$customerDetailsSearchSql = 'SELECT * FROM customer';
	$customerDetailsSearchStatement = $conn->prepare($customerDetailsSearchSql);
	$customerDetailsSearchStatement->execute();

	$output = '<table id="customerDetailsTable" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Tecnico ID</th>
						<th>Nome Completo</th>
						<th>Telemovel</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>';
	
	// Criar linhas de tabela a partir dos dados selecionados
	while($row = $customerDetailsSearchStatement->fetch(PDO::FETCH_ASSOC)){
		$output .= '<tr>' .
						'<td>' . $row['customerID'] . '</td>' .
						'<td>' . $row['fullName'] . '</td>' .
						'<td>' . $row['mobile'] . '</td>' .
						'<td>' . $row['status'] . '</td>' .
					'</tr>';
	}
	
	$customerDetailsSearchStatement->closeCursor();
	
	$output .= '</tbody>
					<tfoot>
						<tr>
							<th>Tecnico ID</th>
						<th>Nome Completo</th>
						<th>Telemovel</th>
						<th>Status</th>
						</tr>
					</tfoot>
				</table>';
	echo $output;
?>