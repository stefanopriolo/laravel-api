<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = User::all();
        return view("admin.posts.index", compact("posts"));
    }

    public function show($slug)
    {
        $post = User::where("slug", $slug)->first();
        return view("admin.posts.show", compact("post"));
    }

    public function create()
    {
        return view("admin.posts.create");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "title" => "required|max:255",
            "body" => "required",
            "image" => "required|max:255",
        ]);

        $counter = 0;
        do {
            $slug = Str::slug($data["title"]) . ($counter > 0 ? "-" . $counter : "");
            $alreadyExists = User::where("slug", $slug)->exists();
            $counter++;
        } while ($alreadyExists);

        $data["slug"] = $slug;

        $post = User::create($data);

        return redirect()->route("admin.posts.index", $post->id);
    }
}
