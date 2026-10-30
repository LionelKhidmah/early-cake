<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::query();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where(function ($q) {
                    $q->where('is_active', false)
                        ->orWhere('starts_at', '>', now())
                        ->orWhere('ends_at', '<', now());
                });
            }
        }

        $promos = $query->latest()->paginate(10)->withQueryString();

        $totalPromos = Promo::count();
        $activePromos = Promo::active()->count();
        $inactivePromos = $totalPromos - $activePromos;

        return view('admin.promos.index', compact(
            'promos',
            'totalPromos',
            'activePromos',
            'inactivePromos'
        ));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatePromo($request);

        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');
        $data['first_order_only'] = $request->boolean('first_order_only');
        $data['terms'] = $this->parseTerms($data['terms_text'] ?? null);

        unset($data['terms_text']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('promos', 'public');
        }

        Promo::create($data);

        return redirect()
            ->route('admin.promos.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    public function show(Promo $promo)
    {
    return view('admin.promos.show', compact('promo'));
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $data = $this->validatePromo($request, $promo);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');
        $data['first_order_only'] = $request->boolean('first_order_only');
        $data['terms'] = $this->parseTerms($data['terms_text'] ?? null);

        unset($data['terms_text']);

        if ($request->hasFile('image')) {
            $newImage = $request->file('image')
                ->store('promos', 'public');

            if ($promo->image) {
                Storage::disk('public')->delete($promo->image);
            }

            $data['image'] = $newImage;
        }

        $promo->update($data);

        return redirect()
            ->route('admin.promos.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $image = $promo->image;

        $promo->delete();

        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return redirect()
            ->route('admin.promos.index')
            ->with('success', 'Promo berhasil dihapus.');
    }

    private function validatePromo(
        Request $request,
        ?Promo $promo = null
    ): array {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'max:2048'],

            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('promos', 'code')->ignore($promo?->id),
            ],

            'discount_type' => [
                'required',
                Rule::in(['percentage', 'fixed']),
            ],

            'discount_value' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'max_discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'min_purchase' => [
                'required',
                'numeric',
                'min:0',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],

            'terms_text' => ['nullable', 'string', 'max:10000'],
            'is_active' => ['nullable', 'boolean'],
            'first_order_only' => ['nullable', 'boolean'],
        ]);

        if (
            $data['discount_type'] === 'percentage'
            && $data['discount_value'] > 100
        ) {
            throw ValidationException::withMessages([
                'discount_value' => 'Diskon persentase tidak boleh melebihi 100%.',
            ]);
        }

        return $data;
    }

    private function parseTerms(?string $terms): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $terms ?? ''))
            ->map(fn ($term) => trim($term))
            ->filter()
            ->values()
            ->all();
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'promo';
        $slug = $base;
        $number = 2;

        while (Promo::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$number}";
            $number++;
        }

        return $slug;
    }
}
