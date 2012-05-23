<?php

namespace li3_datatables\controllers;

class DataTableController extends \lithium\action\Controller
{           
    public function index()
    {		
        $sEcho = isset( $this->request->query['sEcho'] ) ? intval( $this->request->query['sEcho'] ) : 1;
        
        $model = $this->model;
        
        $columns = array_keys( $model::schema() );
        
        $records = $model::all( $this->_getDatagridQuery( $columns ) );
        
        $iTotalDisplayRecords = $records ? $records->count() : 0 ;
        
        $aaData = array();
        
        foreach( $records as $record )
        {            
            $aaData[] = array_values( $record->data() );
        }
        
        $iTotalRecords = $model::count();
        
        return compact( 'aaData', 'iTotalRecords', 'iTotalDisplayRecords', 'sEcho' );
    }
    
    protected function _getDatagridQuery( )
    {
        $findOptions = (array)json_decode( $this->request->query['findOptions'] );
        
        $options = $findOptions + array(
            'conditions' => array(),
        );
        
        //deslugify the fully qualified model class
        $model = str_replace( "-", "\\", $this->request->model );
        
        /*
         * Fields to select
         */
        $options['fields'] = array_keys( (array)json_decode( $this->request->query['columns'] ) );
        
        /*
         * Paging
         */
        if ( isset( $this->request->query['iDisplayStart'] ) && $this->request->query['iDisplayLength'] != '-1' )
        {
            $options['limit'] = $this->request->query['iDisplayStart'] . ", " . $this->request->query['iDisplayLength'];
        }
    	
    	
    	/*
    	 * Ordering
    	 */
    	if ( isset( $this->request->query['iSortCol_0'] ) )
    	{    		
    	    $order = array();
    	    
    		for ( $i=0 ; $i<intval( $this->request->query['iSortingCols'] ) ; $i++ )
    		{
    			if ( $this->request->query[ 'bSortable_'.intval($this->request->query['iSortCol_'.$i]) ] == "true" )
    			{
    				$order[] = $columns[ intval( $this->request->query['iSortCol_'.$i] ) ]." ". $this->request->query['sSortDir_'.$i];
    			}
    		}
    		
    		if( !empty( $order ) )
    		    $options['order'] = implode( ", ", $order );
    	}
    	
    	
    	/* 
    	 * Filtering
    	 */
    	if ( isset( $this->request->query['sSearch'] ) && $this->request->query['sSearch'] != "" )
    	{    	    
    		for ( $i=0 ; $i<count($columns) ; $i++ )
    		{
    		    $options['conditions']['or'][ $columns[$i] ]['like'] = "%{$this->request->query['sSearch']}%";
    		}
    	}
    	
    	/* Individual column filtering */
    	for ( $i=0 ; $i<count($columns) ; $i++ )
    	{
    		if ( isset( $this->request->query['bSearchable_'.$i] ) && $this->request->query['bSearchable_'.$i] == "true" && $this->request->query['sSearch_'.$i] != '' )
    		{
    			$options['conditions'][ $columns[$i] ]['like'] = "%{$this->request->query['sSearch_'.$i]}%";
    		}
    	}
    	
        if( $model::schema('deleted') )
            $options['conditions']['deleted'] = 0;
            
        return $options;
    }
}