<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\FreelancerProfile;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    private const MAX_ITEMS = 12;

    private function profile(Request $request): FreelancerProfile
    {
        return $request->user()->freelancerProfile()->firstOrCreate([]);
    }

    /** A freelancer may only touch their own samples. */
    private function owned(Request $request, PortfolioItem $portfolioItem): PortfolioItem
    {
        abort_unless((int) $portfolioItem->freelancer_profile_id === (int) $this->profile($request)->id, 404);

        return $portfolioItem;
    }

    private function rules(bool $creating): array
    {
        return [
            'title'       => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            // images only (no SVG/GIF), so nothing executable can be uploaded
            'file'        => [$creating ? 'required' : 'nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    private function messages(): array
    {
        return [
            'file.required' => 'Please choose a photo of your work.',
            'file.mimes'    => 'Only JPG, PNG or WebP photos are allowed.',
            'file.max'      => 'The photo must be 5 MB or smaller.',
            'file.uploaded' => 'The upload failed. The photo may be too large (maximum 5 MB).',
        ];
    }

    public function index(Request $request)
    {
        $profile = $this->profile($request);

        return view('freelancer.portfolio.index', [
            'items' => $profile->portfolioItems()->latest()->get(),
            'limit' => self::MAX_ITEMS,
        ]);
    }

    public function store(Request $request)
    {
        $profile = $this->profile($request);

        if ($profile->portfolioItems()->count() >= self::MAX_ITEMS) {
            return back()->withInput()->withErrors([
                'title' => 'You can add up to '.self::MAX_ITEMS.' samples. Delete one to add another.',
            ]);
        }

        $data = $request->validate($this->rules(true), $this->messages());

        $file = $request->file('file');
        $data['file_path'] = $file->store('portfolio', 'local'); // private, random file name
        $data['file_mime'] = $file->getMimeType();
        unset($data['file']);

        $profile->portfolioItems()->create($data);

        return redirect()->route('freelancer.portfolio.index')->with('success', 'Sample added to your portfolio.');
    }

    public function edit(Request $request, PortfolioItem $portfolioItem)
    {
        return view('freelancer.portfolio.edit', [
            'item' => $this->owned($request, $portfolioItem),
        ]);
    }

    public function update(Request $request, PortfolioItem $portfolioItem)
    {
        $this->owned($request, $portfolioItem);

        $data = $request->validate($this->rules(false), $this->messages());

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $newPath = $file->store('portfolio', 'local');
            $newMime = $file->getMimeType();

            Storage::disk('local')->delete($portfolioItem->file_path);   // replace the old photo
            $data['file_path'] = $newPath;
            $data['file_mime'] = $newMime;
        }
        unset($data['file']);

        $portfolioItem->update($data);

        return redirect()->route('freelancer.portfolio.index')->with('success', 'Sample updated.');
    }

    public function destroy(Request $request, PortfolioItem $portfolioItem)
    {
        $this->owned($request, $portfolioItem)->delete(); // model event also deletes the image

        return redirect()->route('freelancer.portfolio.index')->with('success', 'Sample deleted.');
    }

    /** Any logged-in member can view portfolio photos (the same people who can open profiles). */
    public function file(PortfolioItem $portfolioItem)
    {
        $disk = Storage::disk('local');
        abort_unless($disk->exists($portfolioItem->file_path), 404);

        return $disk->response($portfolioItem->file_path, null, [
            'Content-Type'           => $portfolioItem->file_mime,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'          => 'private, max-age=3600',
        ]);
    }
}
