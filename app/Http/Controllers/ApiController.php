<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Article;

class ApiController extends Controller
{
    public function login()
    {
        $passed = \Auth::attempt(request()->only("email", "password"));
        if ($passed === true) {
            $auth = auth()->user();
            $auth->api_token = str_random(25);
            $auth->save();

            $token = $auth->api_token;
            return $this->app_response($token);

        } else {
            return $this->app_response("Authentication failed!", 400);
        }
    }

    public function logout()
    {
        $auth = auth()->user();
        $auth->api_token = null;
        $auth->save();
        return $this->app_response("Logged out!");
    }

    protected function app_response($data, $code = 200)
    {
        $id = uniqid();
        return response()->json([
            "code" => $code,
            "data" => $data
        ], $code)->header("Request-Id", $id);
    }

    public function categories() 
    {
        $cats = auth()->user()->categories;
        return $this->app_response($cats);
    }

    public function getCategory($id)
    {
        $cat = Category::find($id);
        return $this->app_response($cat);
    }

    public function postCategory($id = null) 
    {
        $cat = Category::findOrNew($id);
        $cat->name = request("name");
        // $cat->user_id = auth()->user()->id;
        $cat->user_id = auth()->id();
        $cat->save();

        return $this->app_response($cat);
    }

    public function deleteCategory($id) 
    {
        $cat = Category::findOrFail($id);
        $cat->delete();

        return $this->app_response("Article deleted!");
    }

    public function articles() 
    {
    	$articles = Article::get();
        return $this->app_response($articles);
    }

    public function getArticle($id)
    {
    	$article = Article::find($id);
    	return $this->app_response($article);
    }

    public function postArticle($id = null) 
    {
    	$article = Article::findOrNew($id);
    	$article->title = request("title", $article->title);
    	$article->category_id = request("category_id", $article->category_id);
    	$article->user_id = request("user_id", auth()->id());
    	$article->description = request("description");
    	$article->save();

    	return $this->app_response($article);
    }

    public function deleteArticle($id) 
    {
    	$article = Article::findOrFail($id);
    	$article->delete();

        return $this->app_response("Article deleted!");
    }
}
