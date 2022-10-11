<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\ResourceFeatureTestCase;

class FollowFeatureTest extends TestCase
{
  protected $endpoint = '/api/v1/follows';
  protected $postData = [
    'foreign_table' => 'users',
    'foreign_id' => 'e1625fb5-d615-4adc-9212-ad28fff6cdd4'
  ];

  protected $putDataInvalid = [
    "foreign_table" => 12344,
  ];

  protected $putDataValid = [
    'foreign_table' => 'users',
    'foreign_id' => 'e1625fb5-d615-4adc-9212-ad28fff6cdd4',
    'scope' => 'channel'
  ];

  use ResourceFeatureTestCase;
}
