<?php

namespace App\Http\Controllers;

use App\User;
use App\Pocket;
use App\Article;
use App\Category;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except("index", "reports", "article");
    }

    public function index()
    {
        $data['articles'] = Article::orderBy("created_at", "desc")
            ->when(request()->has("cat"), function ($query) {
                $query->where("category_id", request("cat"));
            }, function($query) {

            })
            ->paginate(5);

        $data["cat"] = Category::findOrNew(request("cat"));
        return view('home', $data);
    }

    public function reports()
    {
        $data['articles'] = Article::has("views")->withCount("views")->get()
            ->sortByDesc(function($a) {
                return $a->views_count;
            })->take(10);

        $data['categories'] = Category::has("articles")->withCount("articles")->get()
            ->sortByDesc(function($c) {
                return $c->articles_count;
            })->take(10);
        
        return view("reports", $data);
    }

    public function category()
    {
        if (request()->method() == "POST") {
            $this->validate(request(),  [
                "name" => "required|unique:categories"
            ]);

            $name = request("name");
            $cat = Category::findOrNew(request("id"));
            $cat->name =  $name;
            $cat->user_id = auth()->id();
            $cat->save();
            return redirect("category")->with("ok", "Saved!");
        }

        if (request()->has("rm")) {
            Category::destroy(request("rm"));
            return back();
        }

        $data['cat'] = Category::find(request("id"));
        return view("category", $data);
    }


    public function articles()
    {
        if (request()->method() == "POST") {
            $a = Article::findOrNew(request("id"));
            $a->title = request("title");
            $a->user_id = auth()->id();
            $a->category_id = request("category_id");
            $a->description = request("description");

            $a->save();
            return back()->with("ok", "Saved!");
        }

        if (request()->has("rm")) {
            Article::destroy(request("rm"));
            return back();
        }

        $data['categories'] = Category::get();
        $data['article'] = Article::find(request("id"));
        return view("articles", $data);
    }

    public function article($id)
    {
        $article = Article::find($id);

        if (auth()->check()) {
            $auth = auth()->user();
            $pocket = $auth->pockets()->where("article_id", $id)->first();

            if (request()->has("pocket") && !$pocket) {
                    Pocket::create([
                        "user_id" => $auth->id,
                        "article_id" => $id,
                    ]);
                return back();
            }
        }

        \App\View::create([
            "user_id" => auth()->id() ?? 0,
            "article_id" => $id,
        ]);
  
        $data['isPocketed'] = (boolean)!@$pocket ? false : true;
        $data['article'] = $article;
        return view('article', $data);
    }
     
    public function pockets()
    {
        if (request()->has("rm")) {
            Pocket::destroy(request("rm"));
            return back();
        }

        $data['pockets'] = auth()->user()->pockets;
        return view('pockets', $data);
    }
}
