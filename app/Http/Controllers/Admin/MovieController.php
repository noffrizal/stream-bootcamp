<?php

namespace App\Http\Controllers\Admin;

use App\Models\Movie;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movies', ['movies' => $movies]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.movie-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        $request->validate([
            'title' => 'required|string|min:5|max:255',
            'small_thumbnail' => 'required|image|mimes:png,jpg,jpeg,PNG,JPG,JPEG',
            'large_thumbnail' => 'required|image|mimes:png,jpg,jpeg,PNG,JPG,JPEG',
            'trailer' => 'required|url',
            'movie' => 'required|url',
            'casts' => 'required|string',
            'categories' => 'required|string',
            'release_date' => 'required|string',
            'about' => 'required|string',
            'short_about' => 'required|string',
            'duration' => 'required|string',
            'featured' => 'required',
        ]);

        $smallThumbnail = $request->small_thumbnail;
        $largeThumbnail = $request->large_thumbnail;

        $originalSmallThumbnailName = $smallThumbnail->getClientOriginalName();
        $originalLargeThumbnailName = $largeThumbnail->getClientOriginalName();

        $smallThumbnail->storeAs('public/thumbnail/', $originalSmallThumbnailName);
        $largeThumbnail->storeAs('public/thumbnail/', $originalLargeThumbnailName);

        $data['small_thumbnail'] = $originalSmallThumbnailName;
        $data['large_thumbnail'] = $originalLargeThumbnailName;

        Movie::create($data);

        return redirect()->route('movie.index')->with('success','Movie has been Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $movie = Movie::find($id);

        return view('admin.movie-edit', ['movie' => $movie]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->except('_token');
        $request->validate([
            'title' => 'required|string|min:5|max:255',
            'small_thumbnail' => 'image|mimes:png,jpg,jpeg,PNG,JPG,JPEG',
            'large_thumbnail' => 'image|mimes:png,jpg,jpeg,PNG,JPG,JPEG',
            'trailer' => 'required|url',
            'movie' => 'required|url',
            'casts' => 'required|string',
            'categories' => 'required|string',
            'release_date' => 'required|string',
            'about' => 'required|string',
            'short_about' => 'required|string',
            'duration' => 'required|string',
            'featured' => 'required',
        ]);

        $movie = Movie::find($id);

        if ($request->small_thumbnail) {
            // save image
            $smallThumbnail = $request->small_thumbnail;
            $originalSmallThumbnailName = $smallThumbnail->getClientOriginalName();
            $smallThumbnail->storeAs('public/thumbnail/', $originalSmallThumbnailName);
            $data['small_thumbnail'] = $originalSmallThumbnailName;

            // delete image
            Storage::delete('public/thumbnail/'.$movie->small_thumbnail);
        }

        if ($request->large_thumbnail) {
            // save image
            $largeThumbnail = $request->large_thumbnail;
            $originalLargeThumbnailName = $largeThumbnail->getClientOriginalName();
            $largeThumbnail->storeAs('public/thumbnail/', $originalLargeThumbnailName);
            $data['large_thumbnail'] = $originalLargeThumbnailName;

            // delete image
            Storage::delete('public/thumbnail/'.$movie->large_thumbnail);
        }

        $movie->update($data);

        return redirect()->route('movie.index')->with('success','Movie Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Movie::find($id)->delete();

        return redirect()->route('movie.index')->with('success','Movie Deleted');
    }
}
