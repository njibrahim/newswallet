<?php

namespace App\Http\Controllers;

use App\User;
use App\Pocket;
use App\Article;
use App\Category;
use GuzzleHttp\Client;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except("index", "reports", "article");
    }
    public function do()
    {
        $token = $this->getToken();
        // $this->registerUrls($token);
        // $response = $this->simulate($token);
        $response = $this->exprtess($token);
        dd($response);
      
        return view('mpesa');
    }

    private function getToken()
    {
        if (cache()->has('token')) {
            return cache('token');
        }

        $client = new Client();
        $base = env('MPESA_BASE_URL');

        $key = env("MPESA_CONSUMER_KEY");
        $secret = env("MPESA_SECRET_KEY");

        $base64 = base64_encode("$key:$secret");

        $response = $client->request('GET', "$base/oauth/v1/generate?grant_type=client_credentials", [
            'headers' => [
                'Authorization' => "Basic $base64",
            ]
        ]);
        $token = json_decode($response->getBody()->getContents())->access_token;

        cache()->put('token', $token);
        return $token;
    }

    private function registerUrls($token)
    {
        $client = new Client();
        $base = env('MPESA_BASE_URL');

        $response = $client->request('POST', "$base/mpesa/c2b/v1/registerurl", [
            'json' => [
                "ShortCode" => 600000,
                "ResponseType" => 'completed',
                "ConfirmationURL" => 'https://6f674c52.ngrok.io',
                "ValidationURL" => 'https://6f674c52.ngrok.io'
            ],
            'headers' => [
                'Authorization' => "Bearer $token",
            ]
        ]);
        return json_decode($response->getBody()->getContents());
    }

    private function simulate($token)
    {
        $client = new Client();
        $base = env('MPESA_BASE_URL');

        $response = $client->request('POST', "$base/mpesa/c2b/v1/simulate", [
            'json' => [
                "ShortCode" => 600000,
                "CommandID" => 'CustomerPayBillOnline',
                "Amount" => '120',
                "Msisdn" => '254708374149',
                "BillRefNumber" => "FRG011"
            ],
            'headers' => [
                'Authorization' => "Bearer $token",
            ]
        ]);
        return json_decode($response->getBody()->getContents());
    }

    private function express($token)
    {
        $client = new Client();
        $base = env('MPESA_BASE_URL');

        $response = $client->request('POST', "$base/mpesa/stkpush/v1/processrequest", [
            'json' => [
                "BusinessShortCode" => "174379",
                "Password" => "",
                "Timestamp" => "",
                "TransactionType" => "CustomerPayBillOnline",
                "Amount" => 0,
                "PartyA" => 0,
                "PartyB" => "174379",
                "PhoneNumber" => 0,
                "CallBackURL" => "",
                "AccountReference" => "",
                "TransactionDesc" => "MPESA Online Payment"
            ],
            'headers' => [
                'Authorization' => "Bearer $token",
            ]
        ]);
        return json_decode($response->getBody()->getContents());
    }

    public function about()
    { 
        // $names = [
        //     'project1' => [
        //         'supervisor' => 'Jane Doe',
        //         "budget" => '1.2M'
        //     ], 
        //     'project2' => [
        //         'supervisor' => 'Geff Doe',
        //         "budget" => '2.2M'
        //     ],
        //     'projectx' => [
        //     ]
        // ];
        // dd($names);

        // $users = User::get();

        // $data['my_x'] = 'This is x';
        // $data['y'] = 'This is y';
        // $data['users'] = $users;

        $data = [];
        return view('about', $data);
    }

    public function index()
    {
        if (request()->has('cat')) {
            $data['articles'] = Article::latest()->where('category_id', request('cat'))->paginate(10);
        } else {
            $data['articles'] = Article::latest()->paginate(10);
        }

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
            $a->image = request('image');
            $a->category_id = request("category_id");
            $a->description = request("description");

            $a->save();
            return back()->with("ok", "Saved!");
        }

        if (request()->has("delete")) {
            Article::destroy(request("delete"));
            return back();
        }

        $data['categories'] = Category::get();
        $data['article'] = auth()->user()->articles()->find(request("id"));
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
