{{-- $certification: existing Certification (edit) or null (add); $issuers: suggestion list --}}
@php $c = $certification ?? null; @endphp

<label for="title">Certification name</label>
<input type="text" id="title" name="title" maxlength="150" required
       value="{{ old('title', $c?->title) }}" placeholder="e.g. NC II – Plumbing">
@error('title')<div class="err">{{ $message }}</div>@enderror

<label for="issuer">Issued by</label>
<input type="text" id="issuer" name="issuer" maxlength="150" required list="issuer-list"
       value="{{ old('issuer', $c?->issuer) }}" placeholder="e.g. TESDA">
<datalist id="issuer-list">
    @foreach ($issuers as $i)<option value="{{ $i }}">@endforeach
</datalist>
@error('issuer')<div class="err">{{ $message }}</div>@enderror

<label for="credential_id">Certificate / credential number <span class="muted">(optional)</span></label>
<input type="text" id="credential_id" name="credential_id" maxlength="80" value="{{ old('credential_id', $c?->credential_id) }}">
@error('credential_id')<div class="err">{{ $message }}</div>@enderror

<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
    <div>
        <label for="issued_on">Date issued</label>
        <input type="date" id="issued_on" name="issued_on" required max="{{ now()->toDateString() }}"
               value="{{ old('issued_on', $c?->issued_on?->toDateString()) }}"
               style="width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:8px;font-size:15px;font-family:inherit">
        @error('issued_on')<div class="err">{{ $message }}</div>@enderror
    </div>
    <div>
        <label for="expires_on">Expiry date <span class="muted">(if any)</span></label>
        <input type="date" id="expires_on" name="expires_on"
               value="{{ old('expires_on', $c?->expires_on?->toDateString()) }}"
               style="width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:8px;font-size:15px;font-family:inherit">
        @error('expires_on')<div class="err">{{ $message }}</div>@enderror
    </div>
</div>

<label for="file">Certificate file {{ $c ? '(leave empty to keep the current file)' : '' }}</label>
<input type="file" id="file" name="file" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
       {{ $c ? '' : 'required' }}
       style="width:100%;padding:10px;border:1px dashed var(--g2);border-radius:8px;background:#f9fff6;font-family:inherit">
<p class="muted" style="margin:6px 0 0">JPG, PNG or PDF, up to 5 MB. Only logged-in members can open your certificates.</p>
@error('file')<div class="err">{{ $message }}</div>@enderror
