<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Services\CloudinaryMediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(Request $request): View
    {
        $items = Item::with('category')
            ->when($request->search, fn ($query, $search) => $query->where(fn ($nested) => $nested->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%")))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('items.index', compact('items'));
    }

    public function create(): View
    {
        return view('items.form', ['item' => new Item(), 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request, CloudinaryMediaService $cloudinary): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('media')) {
            $data = [...$data, ...$cloudinary->upload($request->file('media'))];
        }

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item): View
    {
        return view('items.form', ['item' => $item, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(Request $request, Item $item, CloudinaryMediaService $cloudinary): RedirectResponse
    {
        $data = $this->validated($request, $item);

        if ($request->boolean('remove_media')) {
            $cloudinary->delete($item->media_public_id, $item->media_type);
            $data = [...$data, 'media_url' => null, 'media_public_id' => null, 'media_type' => null];
        }

        if ($request->hasFile('media')) {
            $cloudinary->delete($item->media_public_id, $item->media_type);
            $data = [...$data, ...$cloudinary->upload($request->file('media'))];
        }

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item, CloudinaryMediaService $cloudinary): RedirectResponse
    {
        $cloudinary->delete($item->media_public_id, $item->media_type);
        $item->delete();

        return back()->with('success', 'Barang berhasil dihapus.');
    }

    private function validated(Request $request, ?Item $item = null): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'code' => ['required', 'string', 'max:100', Rule::unique('items', 'code')->ignore($item)],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:1000', 'max:999999999'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'media' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime', 'max:51200'],
            'remove_media' => ['nullable', 'boolean'],
        ], [
            'code.unique' => 'Kode barang sudah digunakan. Gunakan kode lain.',
            'media.mimetypes' => 'Media harus berupa gambar JPG, PNG, WEBP, GIF atau video MP4, WEBM, MOV.',
            'media.max' => 'Ukuran media maksimal 50 MB.',
        ], [
            'category_id' => 'kategori',
            'code' => 'kode',
            'name' => 'nama',
            'unit' => 'satuan',
            'price' => 'harga',
            'stock' => 'stok awal',
            'minimum_stock' => 'stok minimum',
            'description' => 'deskripsi',
            'media' => 'gambar atau video',
        ]);

        unset($data['media'], $data['remove_media']);

        return $data;
    }
}
