<?php

namespace Tests\Feature;

use App\Models\Forbidden;
use App\Models\Url;
use App\Models\User;
use App\Models\View;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlShortnerTest extends TestCase
{
    use RefreshDatabase;

    private function makeUrl(string $url, string $short, array $attributes = []): Url
    {
        // clicks is not mass assignable, so set everything explicitly
        $model = new Url();
        $model->forceFill(array_merge(['url' => $url, 'short_url' => $short, 'ip' => '10.0.0.1', 'private' => false], $attributes))->save();

        return $model;
    }

    public function test_redirect_counts_the_click(): void
    {
        $url = $this->makeUrl('https://laravel.com/docs', 'abc12');

        $this->get('/abc12')->assertRedirect('https://laravel.com/docs');
        $this->get('/abc12')->assertRedirect('https://laravel.com/docs');

        $this->assertSame(2, $url->fresh()->clicks);
        $this->assertSame(2, View::where('url_id', $url->id)->count());
    }

    public function test_unknown_short_url_goes_home(): void
    {
        $this->get('/zzzzz')->assertRedirect('/');
    }

    public function test_dashboard_orders_top_urls_by_clicks(): void
    {
        $this->makeUrl('https://example.com/few', 'few01', ['clicks' => 3]);
        $this->makeUrl('https://spam.example/many', 'many1', ['clicks' => 500]);
        $this->makeUrl('https://example.com/private', 'priv1', ['clicks' => 9000, 'private' => true]);

        $response = $this->actingAs(User::factory()->create())->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSeeInOrder(['spam.example/many', 'example.com/few']);
        $response->assertSee('data-host="spam.example"', false);
        $this->assertSame(3, $response->viewData('stats')['urls']);
        $this->assertSame(9503, $response->viewData('stats')['clicks']);
        // Private urls stay out of the Top 50
        $this->assertNotContains('https://example.com/private', array_column($response->viewData('topUrl'), 'url'));
    }

    public function test_blocking_a_domain_removes_and_refuses_its_urls(): void
    {
        $spam = $this->makeUrl('https://psee.io/87hx33', 'spam1');
        $other = $this->makeUrl('https://example.com/page', 'okay1');

        $this->actingAs(User::factory()->create())
            ->postJson('/admin/api/forbidden', ['keyword' => Url::hostOf($spam->url)])
            ->assertOk()
            ->assertJson(['error' => false]);

        $this->assertTrue(Forbidden::where('contains', 'psee.io')->exists());
        $this->assertSoftDeleted($spam);
        $this->assertNotSoftDeleted($other);

        // New urls on that domain are refused
        $this->postJson('/api', ['url' => 'https://psee.io/another'])->assertForbidden();
    }

    public function test_host_of_strips_www_and_lowercases(): void
    {
        $this->assertSame('example.com', Url::hostOf('https://WWW.Example.com/path?x=1'));
        $this->assertSame('4ty.me', Url::hostOf('http://4ty.me/r8ds09'));
        $this->assertNull(Url::hostOf('not a url'));
    }
}
