<?php namespace App\Http\Controllers\ShortUrls;

use App\Http\Controllers\Controller;
use App\Tools\Models\ShortUrl;
use Auth;
use Illuminate\Http\Request;

class UrlController extends Controller
{

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function redirect($path)
    {
        $url = ShortUrl::where('path', $path)->first();

        if ($url)
            return redirect($url->url);

        return redirect('/');
    }

    public function create(Request $request)
    {
        $req = $request->get('url');

        if (!(starts_with($req, 'http://') || starts_with($req, 'https://')))
            return redirect('/')->with('error', 'Please make sure your URL has http:// or https:// defined.');

        $url = ShortUrl::where('url', $req)->first();

        if (!$url) {
            $path = str_random(6);

            while (ShortUrl::where('path', $path)->first())
                $path = str_random(6);

            ShortUrl::create([
                'url' => $req,
                'path' => $path
            ]);
        } else
            $path = $url->path;

        return redirect('/')->with('url_path', $path);
    }

}