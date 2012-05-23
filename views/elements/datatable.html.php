<?php
/**
 * @property string $id		 The css id for the table
 * @property string $model
 * @property array  $columns
 * 		$columns = array(
 * 			name => (array)options [title]
 * 		)
 * @property array $findOptions an array of options to be passed into find in teh controller
 */

if( !isset( $id ) )
    $id = \lithium\util\String::random( 4 );
    
if( !isset( $findOptions ) )
    $findOptions = array();
    
//slugify that class for injecting into the route
$slugModel = str_replace( "\\", "-", $model );

$source = "/data-table/{$slugModel}/.json?&columns=" . json_encode( $columns ) . "&findOptions=" . json_encode($findOptions) . "&";

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