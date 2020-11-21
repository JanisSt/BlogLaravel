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
        $this->assertDatabaseMissing('articles', [
            'user_id' => $user->id,
            'title' => $article->title,
            'content' => $article->content
        ]);
    }

//TODO add show and hope this works;

    public function testShowArticle():void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id
        ]);

$this->get(route('articles.show',[
    'article'=>$article
]))
    ->assertStatus(200)
    ->assertSee($article->title)
    ->assertSee($article->content);

    }

    public function testEditArticle():void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id
        ]);


        $this->get(route('articles.edit', [
            'article' => $article
        ]))
            ->assertStatus(200)
            ->assertSee($article->title)
            ->assertSee($article->content);



    }

    public function testIndexArticle():void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id
        ]);

        $this->get(route('articles.index',[
            'article'=>$article
        ]))
            ->assertStatus(200)
            ->assertSee($article->title)
            ->assertSee('Title');


    }

    public function testCreateViewArticle():void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id
        ]);

        $this->get(route('articles.create',[
            'article'=>$article
        ]))
            ->assertStatus(200)
            ->assertSee('Title')
            ->assertSee('3487');

    }
//TODO make update test;

//    public function testUpdateArticle()
//    {
//
//
////            $article->update($request->all());
////
////            return redirect()->route('articles.edit', $article);
//
//
//        $user = User::factory()->create();
//        $this->actingAs($user);
//
//        $article = Article::factory()->create([
//            'user_id' => $user->id
//        ]);
//        $this->followingRedirects();
//
//
//        $response = $this->post(route('articles.update'), [
//            'title' => 'Example title',
//            'content' => 'Example content'
//        ]);
//
//        $response->assertStatus(200);
//    }

}
