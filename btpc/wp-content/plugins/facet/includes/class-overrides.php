<?php

class FacetWP_Overrides
{

    public $raw;


    function __construct() {
        add_filter( 'facetwp_index_row', array( $this, 'index_row' ), 5, 2 );
        add_filter( 'facetwp_index_row', array( $this, 'format_numbers' ), 15, 2 );
        add_filter( 'facetwp_store_unfiltered_post_ids', array( $this, 'store_unfiltered_post_ids' ) );
        add_filter( 'facetwp_facet_where', array( $this, 'show_only_children' ), 10, 2 );
    }


    /**
     * Indexer modifications
     */
    function index_row( $params, $class ) {
        if ( $class->is_overridden ) {
            return $params;
        }

        $facet = FWP()->helper->get_facet_by_name( $params['facet_name'] );

        // Support "Other data source" values
        if ( ! empty( $facet['source_other'] ) ) {
            $other_params = $params;
            $other_params['facet_source'] = $facet['source_other'];
            $rows = $class->get_row_data( $other_params );
            $params['facet_display_value'] = $rows[0]['facet_display_value'];
        }

        // Store raw numbers to format later, if needed
        if ( in_array( $facet['type'], array( 'number_range', 'slider' ) ) ) {
            $this->raw = array(
                'value' => $params['facet_value'],
                'label' => $params['facet_display_value']
            );
        }

        return $params;
    }


    /**
     * If on a category / tag / taxonomy archive, display only
     * child terms of the current term
     * @since 3.0.0
     */
    function show_only_children( $where_clause, $facet ) {
        $tax = isset( FWP()->ajax->taxonomy ) ? FWP()->ajax->taxonomy : false;

        if ( ! empty( $tax ) && "tax/$tax" == $facet['source'] ) {
            $term_id = FWP()->ajax->term_id;
            $children = (array) get_term_children( $term_id, $tax );
            $children[] = 0;
            $children = implode( ',', $children );
            $where_clause .= " AND f.term_id IN ($children)";
        }

        return $where_clause;
    }


    /**
     * Make sure that numbers are properly formatted
     */
    function format_numbers( $params, $class ) {

        $value = $params['facet_value'];
        $label = $params['facet_display_value'];

        if ( empty( $this->raw ) ) {
            return $params;
        }

        // Only format if un-altered
        if ( $this->raw['value'] === $value && $this->raw['label'] === $label ) {
            $params['facet_value'] = FWP()->helper->format_number( $this->raw['value'] );
            $params['facet_display_value'] = FWP()->helper->format_number( $this->raw['label'] );
        }

        $this->raw = null;

        return $params;
    }


    /**
     * Store unfiltered post IDs if needed
     */
    function store_unfiltered_post_ids( $boolean ) {
        if ( FWP()->helper->facet_setting_exists( 'type', 'dropdown' ) ) {
            return true;
        }

        if ( FWP()->helper->facet_setting_exists( 'type', 'fselect' ) ) {
            return true;
        }

        if ( FWP()->helper->facet_setting_exists( 'type', 'radio' ) ) {
            return true;
        }

        if ( FWP()->helper->facet_setting_exists( 'ghosts', 'yes' ) ) {
            return true;
        }

        if ( FWP()->helper->facet_setting_exists( 'operator', 'or' ) ) {
            return true;
        }

        return $boolean;
    }
}
