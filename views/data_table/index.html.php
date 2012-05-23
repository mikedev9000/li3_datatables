<?php
/**
 * @property string $id		 The css id for the table
 * @property string $model
 * @property array  $columns
 * 		$columns = array(
 * 			name => (array)options [title]
 * 		)
 * 
 */

if( !isset( $id ) )
    $id = \lithium\util\String::random( 4 );

$source = "/data-table/.json?model={$model}&columns=" . json_encode( $columns ) . "&";

?>
<table id="<?=$id;?>" class="datagrid">
	<thead>
		<tr>
<?php foreach( $columns as $column => $options ):?>
			<th><?=$options['title'];?></th>
<?php endforeach;?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="<?=count($columns);?>" class="dataTables_empty">Loading data from server</td>
		</tr>
	</tbody>
</table>

<script type="text/javascript">
$(document).ready(function() {
    $('#<?=$id;?>').dataTable( {
        "bProcessing": true,
		"bServerSide": true,
        "sAjaxSource": '<?=$source;?>'
    } );
} );
</script>