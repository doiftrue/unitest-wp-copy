<?php

class rest_api_Test extends \PHPUnit\Framework\TestCase {

	public function test__rest_default_additional_properties_to_false() {
		$schema = [
			'type' => 'object',
			'properties' => [
				'meta' => [
					'type' => 'object',
					'properties' => [
						'items' => [
							'type' => 'array',
							'items' => [ 'type' => 'object' ],
						],
					],
				],
			],
		];

		$result = rest_default_additional_properties_to_false( $schema );

		$this->assertFalse( $result['additionalProperties'] );
		$this->assertFalse( $result['properties']['meta']['additionalProperties'] );
		$this->assertFalse( $result['properties']['meta']['properties']['items']['items']['additionalProperties'] );
	}

}
