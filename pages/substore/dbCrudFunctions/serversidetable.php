<?php
 
// DB table to use
$table = 'mc_materialmaster';
 
// Table's primary key
$primaryKey = 'que';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'material', 'dt' => 0 ),
    array( 'db' => 'description',  'dt' => 1 ),
    array( 'db' => 'spq_pallet',   'dt' => 2 ),
    array( 'db' => 'spq_box',     'dt' => 3 ),
    array( 'db' => 'spq_inner',     'dt' => 4 ),
    array( 'db' => 'standardissue',     'dt' => 5 ),
    array( 'db' => 'cd',     'dt' => 6 )
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'superadmin',
    'pass' => 'Superman@2021!',
    'db'   => 'vCMS',
    'host' => 'sncto01.satnusa.com'
);
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>