<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->is_superadmin) {
            $urls = ShortUrl::with(['user', 'company'])
                ->latest()
                ->get();
        }

        elseif (currentUserRole() === 'admin') {

            $urls = ShortUrl::with(['user', 'company'])
                ->where('company_id', currentCompanyId())
                ->latest()
                ->get();
        }

        else {

            $urls = ShortUrl::with(['user', 'company'])
                ->where('company_id', currentCompanyId())
                ->where('user_id', auth()->id())
                ->latest()
                ->get();
        }

        return view('dashboard.shorturl', compact('urls'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->is_superadmin) {

            return response()->json([
                'status' => false,
                'message' => 'SuperAdmin cannot create short urls'
            ]);
        }

        $request->validate([
            'original_url' => 'required|url'
        ]);

        do {

            $shortCode = Str::random(6);

        } while (
            ShortUrl::where('short_code', $shortCode)->exists()
        );

        $shortUrl = ShortUrl::create([

            'user_id' => auth()->id(),

            'company_id' => currentCompanyId(),

            'original_url' => $request->original_url,

            'short_code' => $shortCode,
        ]);

        return response()->json([

            'status' => true,

            'message' => 'Short URL created successfully',

            'short_url' => url('/s/' . $shortCode),

            'data' => $shortUrl
        ]);
    }

    public function redirect($code)
    {
        $url = ShortUrl::where('short_code', $code)->first();

        if (!$url) {
            abort(404);
        }

        $url->increment('clicks');

        return redirect($url->original_url);
    }
}