<?php

class comment__Test extends \PHPUnit\Framework\TestCase {
	public function test___clear_modified_cache_on_transition_comment_status() {
		wp_cache_set( 'lastcommentmodified:gmt', 'value', 'timeinfo' );
		_clear_modified_cache_on_transition_comment_status( 'approved', 'hold' );
		$this->assertFalse( wp_cache_get( 'lastcommentmodified:gmt', 'timeinfo' ) );
	}

	public function test__wp_filter_comment() {
		$data = wp_filter_comment( [ 'comment_author' => 'A', 'comment_content' => 'C', 'comment_author_IP' => '', 'comment_author_url' => '', 'comment_author_email' => '' ] );
		$this->assertTrue( $data['filtered'] );
	}

	public function test__wp_throttle_comment_flood() {
		$this->assertTrue( wp_throttle_comment_flood( false, 10, 20 ) );
		$this->assertFalse( wp_throttle_comment_flood( false, 10, 30 ) );
	}

	public function test__clean_comment_cache() {
		wp_cache_set( 7, 'comment', 'comment' );
		clean_comment_cache( 7 );
		$this->assertFalse( wp_cache_get( 7, 'comment' ) );
	}

	public function test__get_comment_statuses() {
		$this->assertSame( [ 'hold', 'approve', 'spam', 'trash' ], array_keys( get_comment_statuses() ) );
	}

	public function test__separate_comments() {
		$comments = [ (object) [ 'comment_type' => '' ], (object) [ 'comment_type' => 'pingback' ] ];
		$separated = separate_comments( $comments );
		$this->assertCount( 1, $separated['comment'] );
		$this->assertCount( 1, $separated['pingback'] );
		$this->assertCount( 1, $separated['pings'] );
	}

	public function test__wp_register_comment_personal_data_exporter() {
		$result = wp_register_comment_personal_data_exporter( [] );
		$this->assertSame( 'wp_comments_personal_data_exporter', $result['wordpress-comments']['callback'] );
	}

	public function test__wp_register_comment_personal_data_eraser() {
		$result = wp_register_comment_personal_data_eraser( [] );
		$this->assertSame( 'wp_comments_personal_data_eraser', $result['wordpress-comments']['callback'] );
	}

	public function test__wp_cache_set_comments_last_changed() {
		wp_cache_set_comments_last_changed();
		$this->assertNotFalse( wp_cache_get( 'last_changed', 'comment' ) );
	}
}
