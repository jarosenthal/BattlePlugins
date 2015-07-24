<?php namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\User;
use App\Tools\Misc\Jenkins;
use App\Tools\Queries\ServerSetting;
use App\Tools\URL\Domain;

class PageController extends Controller {

    public function index($id = null) {
        if ($id)
            $blog = Blog::find($id);
        else
            $blog = Blog::latest()->first();

        $obj = [
            'jenkins'         => ServerSetting::get('jenkins') ? Jenkins::getStableBuilds(null, 4) : null,
            'download_server' => Domain::remoteFileExists('http://ci.battleplugins.com'),
            'comment_feed'    => ServerSetting::get('comment_feed')
        ];

        if (!$blog)
            return view('blog.index', $obj);

        return view('blog.index', static::retrieve($blog, $obj));
    }

    private static function retrieve($blog, $obj = []) {
        if ($blog instanceof Blog) {
            $users = User::all();
            $displaynames = [];

            foreach ($users as $user)
                $displaynames[$user->id] = $user->displayname;

            return array_merge([
                'blog'  => $blog,
                'list'  => Blog::latest()->take(4)->get(),
                'users' => $displaynames,
            ], $obj);
        }
    }
}