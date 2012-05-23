<?php

use lithium\net\http\Router;

/**
 * Connect to the only route needed for rendering the table data
 */
Router::connect( "/data-table/{:model}/.json", "DataTable::index" );