<?php
/**
 * @package Admin
 */
 
/**
 * Class that lists the currently available stylesheets
 */
class JGCSS_List_Table extends WP_List_Table_3_9_3 {
	
	/**
	 * Constructor, we override the parent to pass our own arguments
	 * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
	 */
	 function __construct() {
		 parent::__construct(
		 	array(
				'singular'=> 'jgcss_stylesheets', // Singular label
				'plural' => 'jgcss_stylesheet', // plural label, also this well be one of the table css class
				'ajax'	=> false, // We won't support Ajax for this table
			) // end array
		); // end parent::__construct()

	 } // end __construct()
	 
	/**
	 * Add extra markup in the toolbars before or after the list
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	function extra_tablenav( $which ) {
		if ( $which == "top" ){
			// The code that goes before the table is here
		}
		if ( $which == "bottom" ){
			// The code that goes after the table is there
		}
	}

	function get_bulk_actions() {
	  $actions = array(
		'delete'    => 'Delete'
	  );
	  return $actions;
	}
	
	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'col_stylesheet_name' => 'Name',
			'col_stylesheet_author' => 'Author',
			'col_stylesheet_date' => 'Created',
			'col_stylesheet_modified' => 'Last Modified'
		);
		
		return  $columns;
	}
	

	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	public function get_sortable_columns() {
		$sortable = array();
		return $sortable;
	}
		
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		global $wpdb, $_column_headers;
		$screen = get_current_screen();
	
		/* -- Preparing your query -- */
			$table_name = $wpdb->prefix . "jgcss_stylesheets";
			$query = "
				SELECT *
				FROM $table_name
				ORDER BY stylesheet_id ASC";
	
		/* -- Register the Columns -- */
			// Force the heading items into the appropriate variable
			$this->_column_headers[0] = $this->get_columns();
			$this->_column_headers[1] = array(0 => '');
			$this->_column_headers[2] = $this->get_sortable_columns();
	
		/* -- Fetch the items -- */
			$this->items = $wpdb->get_results($query);
	}

	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display_rows() {
	
		// Get the records registered in the prepare_items method
		$records = $this->items;
	
		// Loop for each record
		if(!empty($records)) {
			foreach( $records as $record ) {
				
				$column_info = $this->get_column_info();
				list( $columns, $hidden ) = $column_info;
		
				// Open the line
				echo '<tr id="stylesheet_' . $record->stylesheet_id . '">';
				foreach ( $columns as $column_name => $column_display_name ) {
					
					// Style attributes for each col
					$class = "class='$column_name column-$column_name'";
					$style = "";
					if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
					$attributes = $class . $style;
		
					// edit link
					$editlink = site_url() . '/wp-admin/admin.php?page=jgcss_stylesheet&stylesheet_id=' . (int)$record->stylesheet_id;
					
					$row_actions = array(
						'Edit' => '#',
						'Delete' => '#'
					);
					
					// Get the user data
					$user_data = get_userdata( $record->stylesheet_author );
					
					// Display the cell
					switch ( $column_name ) {
						case "cb":
							echo '<th scope="row" class="check-column"><input type="checkbox" name="stylesheet[]" value="' . $record->stylesheet_id . '" /></th>';
							break;
						case "col_stylesheet_name":
							echo '<td ' . $attributes.'><strong><a href="' . $editlink . '">' . stripslashes($record->stylesheet_name) . '</a></strong>';
							echo '<div class="row-actions">';
							echo '<span class="edit">';
							echo '<a href="' . site_url()  . '/wp-admin/admin.php?page=jgcss_stylesheet&stylesheet_id=' . (int)$record->stylesheet_id . '" title="Edit this item">Edit</a>';
							echo ' | ';
							echo '</span>';
							echo '<span class="trash">';
							echo '<a href="' . site_url()  . '/wp-admin/admin.php?page=jgcss_dashboard&stylesheet_id=' . (int)$record->stylesheet_id . '&amp;action=trash" title="Delete this item">Trash</a>';
							echo '</span>';
							echo '</div></td>';
							break;
						case "col_stylesheet_author":
							echo '<td ' . $attributes . '>' . stripslashes($user_data->data->display_name) . '</td>';
							break;
						case "col_stylesheet_date":
							echo '<td ' . $attributes . '>' . mysql2date('Y-m-d', $record->stylesheet_date) . '<br />' . mysql2date('g:i:s A', $record->stylesheet_date) . '</td>';
							break;
						case "col_stylesheet_modified":
							echo '<td ' . $attributes . '>' . mysql2date('Y-m-d', $record->stylesheet_modified) . '<br />' . mysql2date('g:i:s A', $record->stylesheet_modified) . '</td>';
							break;
					} // end switch()
				} // end foreach()
		
				// Close the line
				echo '</tr>';
				
			} // end foreach()
			
		} // end if()
		
	}

}
