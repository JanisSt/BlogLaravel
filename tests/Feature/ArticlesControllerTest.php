<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticlesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateNewArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->followingRedirects();

        $response = $this->post(route('articles.store'), [
            'title' => 'Example title',
            'content' => 'Example content'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => 'Example title',
            'content' => 'Example content'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'articles_count' => 1
        ]);

        var_dump($user->articles_count);

        $this->assertEquals(1, $user->articles_count);
    }

    public function testUpdateExistingArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id
        ]);

        $this->followingRedirects();

        $response = $this->put(route('articles.update', $article), [
            'title' => 'Example title',
            'content' => 'Example content'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'user_id' => $user->id,
            'title' => 'Example title',
            'content' => 'Example content'
        ]);
    }

    public function testDeleteArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => $article->title,
            'content' => $article->content
        ]);

        $this->followingRedirects();

        $response = $this->delete(route('articles.destroy', $article));

        $response->assertStatus(200);
        $this->assertSoftDeleted('articles', [
            'user_id' => $user->id,
            'title' => $article->title,
            'content' => $article->content
        ]);
    }

    public function testRedirectWhenUnauthorizedTriesToSeeCreateForm(): void
    {
        $response = $this->get(route('articles.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testRedirectWhenUnauthorizedTriesToCreateArticle(): void
    {
        $response = $this->post(route('articles.store'), [
            'title' => 'Example title',
            'content' => 'Example content'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testRedirectWhenUnauthorizedTriesToSeeUpdateForm(): void
    {
        $article = Article::factory()->create();

        $response = $this->get(route('articles.edit', $article));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testRedirectWhenUnauthorizedTriesToUpdateArticle(): void
    {
        $article = Article::factory()->create();

        $response = $this->put(route('articles.update', $article), [
            'title' => 'Example title',
            'content' => 'Example content'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testOtherUserCannotEditMyArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create();

        $response = $this->put(route('articles.update', $article), [
            'title' => 'Example title',
            'content' => 'Example content'
        ]);

        $response->assertStatus(403);
    }

    public function testOtherUserCannotDeleteMyArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create();

        $response = $this->delete(route('articles.destroy', $article));

        $response->assertStatus(403);
    }

    public function testCannotCreateMoreThan3Articles(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Article::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $response = $this->post(route('articles.store'), [
            'title' => 'Example title',
            'content' => 'Example content'
        ]);

        $response->assertStatus(403);
    }
}
