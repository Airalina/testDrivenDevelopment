<?php

namespace Tests\Unit\Models;

use App\Models\Repository;
use App\Models\User;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_belongs_to_user()
    {
       $repository = Repository::factory()->create();   
       $this->assertInstanceOf(User::class, $repository->user);

    }
}
