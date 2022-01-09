<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    /**
     * Creates method.
     */
    public function create(string $url)
    {
        $baseUrl = config('app.url') . '/';
        $res = Url::createSlug($url, $baseUrl);

        // On success it returns a string with the shortened url and the code
        if (in_array($res->getCode(), ['200', '201']) !== false) {
            return response($res->getResult(), $res->getCode());
        }

        return response($res->getMessage(), $res->getCode());
    }

    /**
     * Show method.
     */
    public function show(string $slug, Request $request)
    {
        $res = Url::showUrl($slug, $request->ip());

        // On success it returns the result
        if ($res->getCode() == 200) {
            return redirect($res->getResult());
        }

        return response($res->getMessage(), $res->getCode());
    }
}
