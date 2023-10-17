<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostUpsertRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Uuid;

class PostController extends Controller
{

    public function index()
    {
        // $posts = Post::all();
        $posts = Post::paginate(3);

        return view("admin.posts.index", compact("posts"));
    }

    public function show($slug)
    {
        $post = Post::where("slug", $slug)->first(); // $post[0]

        return view("admin.posts.show", compact("post"));
    }

    public function create()
    {
        if (Auth::user()->role->name !== "admin") {
            return redirect()->route("admin.posts.index");
        }

        $categories = Category::all();
        $tags = Tag::all();

        return view("admin.posts.create", [
            "categories" => $categories,
            "tags" => $tags
        ]);
    }

    public function store(PostUpsertRequest $request)
    {
        $data = $request->validated();

        $data["slug"] = $this->generateSlug($data["title"]);

        // $post = new Post();
        // $post->title = $data["title"];
        // $post->fill($data);
        // $post->save()

        // salvo il file nel filesystem
        $data["image"] = Storage::put("posts", $data["image"]);
        $data["user_id"] = Auth::id();

        // Il ::create esegue il fill e il save in un unico comando
        $post = Post::create($data);

        // Siccome l'attach per funzionare ha bisogno dell'id del post,
        // e siccome questo viene generato SOLO dopo il save(),
        // siamo costretti ad eseguire l'attach SOLO DOPO aver eseguito il save/create
        if (key_exists("tags", $data)) {
            $post->tags()->attach($data["tags"]);
        }

        return redirect()->route("admin.posts.show", $post->slug);
    }

    public function edit($slug)
    {
        $post = Post::where("slug", $slug)->firstOrFail();
        $categories = Category::all();
        $tags = Tag::all();

        return view("admin.posts.edit", compact("post", "categories", "tags"));
    }

    public  function update(PostUpsertRequest $request, $slug)
    {
        $data = $request->validated();

        $post = Post::where("slug", $slug)->firstOrFail();

        // controllo se il titolo è cambiato. Solo in quel caso rigenero lo slug
        if ($data["title"] !== $post->title) {
            $data["slug"] = $this->generateSlug($data["title"]);
        }

        $data["is_published"] = boolval($data["is_published"]);

        // dd($data);

        // se la checkbox è spuntata, il server riceve il valore di "is_published"
        // se la checkbox non è spuntata, il server non riceve il valore di "is_published"
        // if (isset($data["is_published"])) {
        if ($data["is_published"]) {
            $post->is_published = true;
            $post->published_at = now();
            // $post->save();
        } else {
            $post->is_published = false;
            $post->published_at = null;
            // $post->save();
        }

        if (isset($data["image_link"])) {
            // Recupero il codice binario dell'immagine dal link
            $imgLink = file_get_contents($data["image_link"]);

            // creo un nome per il file
            $path = "posts/" . uniqid();

            // creo il file inserendo il codice binario come contenuto
            File::put(storage_path("app/public/" . $path), $imgLink);

            // salvo il path nel database
            $data["image"] = $path;
        } else if (isset($data["image"])) {
            // siccome non caricherò un immagine ad ogni update, se quella esistente mi va bene
            // prevedo il caso in cui non ci sarà l'immagine e quindi non faccio lo Storage::put
            // se esiste già un'immagine, prima la cancello
            if ($post->image) {
                Storage::delete($post->image);
            }

            // salvo il file nel filesystem
            $image_path = Storage::put("posts", $data["image"]);

            $data["image"] = $image_path;
        }

        // assegnazione tags
        // prima di assegnare i nuovi tag, cancello quelli precedenti
        // $post->tags()->detach();

        // assegno i nuovi tag
        // $post->tags()->attach($data["tags"]);

        // esegue il detach SOLO dei tag non presenti nel nuovo array
        // esegue l'attach SOLO dei tag non presenti nel vecchio array
        $post->tags()->sync($data["tags"]);

        $post->update($data);

        return redirect()->route("admin.posts.show", $post->slug);
    }

    public function destroy($slug)
    {
        if (Auth::user()->email !== "florian.leica@gmail.com") {
            return abort(403);
        }

        $post = Post::where("slug", $slug)->firstOrFail();

        // se il posta ha un immagine, cancellando il post l'immagine rimande nel limbo
        if ($post->image) {
            Storage::delete($post->image);
        }

        $post->tags()->detach();
        $post->delete();

        return redirect()->route("admin.posts.index");
    }

    protected function generateSlug($title)
    {
        // contatore da usare per avere un numero incrementale
        $counter = 0;

        do {
            // creo uno slug e se il counter è maggiore di 0, concateno il counter
            $slug = Str::slug($title) . ($counter > 0 ? "-" . $counter : "");

            // cerco se esiste già un elemento con questo slug
            $alreadyExists = Post::where("slug", $slug)->first();

            $counter++;
        } while ($alreadyExists); // finché esiste già un elemento con questo slug, ripeto il ciclo per creare uno slug nuovo

        return $slug;
    }
}
