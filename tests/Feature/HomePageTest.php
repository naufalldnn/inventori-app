<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_render_with_ndn_branding(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('NDN');
    }
}
