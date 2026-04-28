<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminMovieController extends Controller
{
    public function index() {
        return Movie::all();
    }

    public function store(Request $request) {
        $movie = new Movie();
        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->genre = $request->genre;

      if ($request->hasFile('poster')) {
    $path = $request->file('poster')->store('posters', 'public');
    $movie->poster = asset('storage/' . $path);
}


        $movie->save();
        return response()->json($movie);
    }

    public function update(Request $request, $id) {
        $movie = Movie::findOrFail($id);
        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->genre = $request->genre;
 Log::info($request->all());
        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $movie->poster = $path;
        }
 
        
        $movie->save();
        return response()->json($movie);
    }
     

    
    public function show($id) {
        return response()->json(Movie::findOrFail($id));
    }

    public function destroy($id) {
        $movie = Movie::findOrFail($id);
        $movie->delete();
        return response()->json(["message" => "Movie deleted"]);
    }
}
