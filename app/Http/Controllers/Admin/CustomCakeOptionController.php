<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomCakeOption;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomCakeOptionController extends Controller
{
    public function index()
    {
        $options = CustomCakeOption::orderBy('option_type')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.custom-cake-options.index', compact('options'));
    }

    public function create()
    {
        return view('admin.custom-cake-options.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'option_type' => [
                'required',
                Rule::in(['flavor', 'size', 'decoration']),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('custom_cake_options')
                    ->where('option_type', $request->option_type),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'additional_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        CustomCakeOption::create($validated);

        return redirect()
            ->route('admin.custom-cake-options.index')
            ->with('success', 'Pilihan custom cake berhasil ditambahkan.');
    }

    public function edit(CustomCakeOption $customCakeOption)
    {
        return view(
            'admin.custom-cake-options.edit',
            compact('customCakeOption')
        );
    }

    public function update(
        Request $request,
        CustomCakeOption $customCakeOption
    ) {
        $validated = $request->validate([
            'option_type' => [
                'required',
                Rule::in(['flavor', 'size', 'decoration']),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('custom_cake_options')
                    ->where('option_type', $request->option_type)
                    ->ignore($customCakeOption->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'additional_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],

        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $customCakeOption->update($validated);

        return redirect()
            ->route('admin.custom-cake-options.index')
            ->with('success', 'Pilihan custom cake berhasil diperbarui.');
    }

    public function destroy(CustomCakeOption $customCakeOption)
    {
        $customCakeOption->delete();

        return redirect()
            ->route('admin.custom-cake-options.index')
            ->with('success', 'Pilihan custom cake berhasil dihapus.');
    }
}
