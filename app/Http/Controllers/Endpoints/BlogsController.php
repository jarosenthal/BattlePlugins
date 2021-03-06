<?php

namespace App\Http\Controllers\Endpoints;

use App\API\StatusCodes\ApiStatusCode;
use App\API\Transformers\BlogTransformer;
use App\API\Webhooks;
use App\Models\Blog;
use App\Repositories\BlogRepository;
use App\Tools\UserSettings;
use Auth;
use Illuminate\Http\Request;

/**
 * Class BlogsController
 * @package App\Http\Controllers\Endpoints
 */
class BlogsController extends ApiController {
    /**
     * @var BlogTransformer
     */
    protected $blogTransformer, $statusCode, $webhooks, $request;
    /**
     * @var int
     */
    private $limit = 5;

    /**
     * @param BlogTransformer $blogTransformer
     * @param ApiStatusCode $statusCode
     * @param Webhooks $webhooks
     * @param Request $request
     */
    function __construct(BlogTransformer $blogTransformer, ApiStatusCode $statusCode, Webhooks $webhooks, Request $request) {
        $this->middleware('auth.api', ['except' => ['show', 'index']]);
        $this->blogTransformer = $blogTransformer;
        $this->statusCode = $statusCode;
        $this->webhooks = $webhooks;
        $this->request = $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index() {
        $limit = $this->request->input('limit', $this->limit);
        $limit = $limit > $this->limit ? $this->limit : $limit;

        $blogs = Blog::paginate($limit);
        return $this->returnWithPagination($blogs, [
            'data' => $this->blogTransformer->transformCollection($blogs->all())
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($id) {
        $blog = Blog::find($id);

        if (!$blog)
            return $this->statusCode->respondNotFound("Blog does not exist!");

        return $this->statusCode->respond([
            'data' => $this->blogTransformer->transform($blog)
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store() {
        if (UserSettings::hasNode(auth()->user(), UserSettings::CREATE_BLOG)) {
            $title = $this->request->input('title');
            $content = $this->request->input('content');

            if (!$title || !$content)
                return $this->statusCode->respondWithError("A required field has been left blank.");

            BlogRepository::create($title, $content, auth()->user());
            return $this->statusCode->respondCreated('Blog successfully created.');
        } else
            return $this->statusCode->respondValidationFailed();
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id) {
        if (UserSettings::hasNode(auth()->user(), UserSettings::DELETE_BLOG)) {
            BlogRepository::delete($id);
            return $this->statusCode->respondWithSuccess("Blog has been deleted.");
        } else
            return $this->statusCode->respondValidationFailed();
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id) {
        if (UserSettings::hasNode(auth()->user(), UserSettings::MODIFY_BLOG)) {
            $blog = Blog::find($id);

            if (!$blog)
                return $this->statusCode->respondNotFound("Blog does not exist!");

            BlogRepository::update($blog, $this->request->all());
            return $this->statusCode->respondWithSuccess("Blog has been modified.");
        } else
            return $this->statusCode->respondValidationFailed();
    }
}