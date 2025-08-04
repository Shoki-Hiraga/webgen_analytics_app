<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SetSlug;
use App\Http\Controllers\Ga4Controller;
use App\Http\Controllers\GscController;

class DynamicCmsController extends Controller
{
    public function handle(Request $request)
    {
        $slug = $request->path();
        $page = SetSlug::where('slug', $slug)->where('active', true)->firstOrFail();

        switch ($page->type) {
            case 'ga4':
                return $this->handleGa4($request, $page);
            case 'gsc':
                return $this->handleGsc($request, $page);
            default:
                abort(404);
        }
    }

    private function handleGa4(Request $request, SetSlug $page)
    {
        $controller = new Ga4Controller();

        return match ($page->handler) {
            'yoy'   => $controller->yoy($request),
            'mom'   => $controller->mom($request),
            'index' => $controller->index($request),
            default => $controller->showByDirectory($request, basename($page->slug)),
        };
    }

    private function handleGsc(Request $request, SetSlug $page)
    {
        $controller = new GscController();

        return match ($page->handler) {
            'yoy'   => $controller->yoy($request),
            'mom'   => $controller->mom($request),
            'index' => $controller->index($request),
            default => $controller->showByDirectory($request, basename($page->slug)),
        };
    }
}
