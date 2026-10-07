<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\FreelancerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificationController extends Controller
{
    private const MAX_PER_FREELANCER = 10;

    /** Handy suggestions for the "issued by" box (the field still accepts anything). */
    private const ISSUERS = ['TESDA', 'PRC', 'DepEd', 'CHED', 'DICT', 'DOLE', 'LTO', 'Red Cross'];

    private function profile(Request $request): FreelancerProfile
    {
        return $request->user()->freelancerProfile()->firstOrCreate([]);
    }

    /** A freelancer may only touch their own certifications. */
    private function owned(Request $request, Certification $certification): Certification
    {
        abort_unless((int) $certification->freelancer_profile_id === (int) $this->profile($request)->id, 404);

        return $certification;
    }

    private function rules(bool $creating): array
    {
        return [
            'title'         => ['required', 'string', 'max:150'],
            'issuer'        => ['required', 'string', 'max:150'],
            'credential_id' => ['nullable', 'string', 'max:80'],
            'issued_on'     => ['required', 'date', 'before_or_equal:today'],
            'expires_on'    => ['nullable', 'date', 'after_or_equal:issued_on'],
            'file'          => [$creating ? 'required' : 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    private function messages(): array
    {
        return [
            'file.required'            => 'Please attach a photo or PDF of your certificate.',
            'file.mimes'               => 'Only JPG, PNG or PDF files are allowed.',
            'file.max'                 => 'The file must be 5 MB or smaller.',
            'file.uploaded'            => 'The upload failed. The file may be too large (maximum 5 MB).',
            'issued_on.before_or_equal' => 'The issue date cannot be in the future.',
            'expires_on.after_or_equal' => 'The expiry date must be on or after the issue date.',
        ];
    }

    public function index(Request $request)
    {
        $profile = $this->profile($request);

        return view('freelancer.certifications.index', [
            'certifications' => $profile->certifications()->orderByDesc('issued_on')->get(),
            'limit'          => self::MAX_PER_FREELANCER,
            'issuers'        => self::ISSUERS,
        ]);
    }

    public function store(Request $request)
    {
        $profile = $this->profile($request);

        if ($profile->certifications()->count() >= self::MAX_PER_FREELANCER) {
            return back()->withInput()->withErrors([
                'title' => 'You can add up to '.self::MAX_PER_FREELANCER.' certifications. Delete one to add another.',
            ]);
        }

        $data = $request->validate($this->rules(true), $this->messages());

        $file = $request->file('file');
        $data['file_path'] = $file->store('certifications', 'local'); // private, random file name
        $data['file_mime'] = $file->getMimeType();
        unset($data['file']);

        $profile->certifications()->create($data);

        return redirect()->route('freelancer.certifications.index')->with('success', 'Certification added.');
    }

    public function edit(Request $request, Certification $certification)
    {
        return view('freelancer.certifications.edit', [
            'certification' => $this->owned($request, $certification),
            'issuers'       => self::ISSUERS,
        ]);
    }

    public function update(Request $request, Certification $certification)
    {
        $this->owned($request, $certification);

        $data = $request->validate($this->rules(false), $this->messages());

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $newPath = $file->store('certifications', 'local');
            $newMime = $file->getMimeType();

            Storage::disk('local')->delete($certification->file_path);   // replace the old file
            $data['file_path'] = $newPath;
            $data['file_mime'] = $newMime;
        }
        unset($data['file']);

        $certification->update($data);

        return redirect()->route('freelancer.certifications.index')->with('success', 'Certification updated.');
    }

    public function destroy(Request $request, Certification $certification)
    {
        $this->owned($request, $certification)->delete(); // model event also deletes the file

        return redirect()->route('freelancer.certifications.index')->with('success', 'Certification deleted.');
    }

    /** Any logged-in member can view a certificate (the same people who can open profiles). */
    public function file(Certification $certification)
    {
        $disk = Storage::disk('local');
        abort_unless($disk->exists($certification->file_path), 404);

        return $disk->response($certification->file_path, null, [
            'Content-Type'           => $certification->file_mime,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'          => 'private, max-age=0, must-revalidate',
        ]);
    }
}
