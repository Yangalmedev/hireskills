{{-- $item: existing PortfolioItem (edit) or null (add) --}}
@php $p = $item ?? null; @endphp

<label for="title">Title</label>
<input type="text" id="title" name="title" maxlength="120" required
       value="{{ old('title', $p?->title) }}" placeholder="e.g. Tiled bathroom, Brgy. Bito">
@error('title')<div class="err">{{ $message }}</div>@enderror

<label for="description">Description <span class="muted">(optional)</span></label>
<textarea id="description" name="description" rows="3" maxlength="500"
          placeholder="What did you do? Materials, time it took, the result...">{{ old('description', $p?->description) }}</textarea>
@error('description')<div class="err">{{ $message }}</div>@enderror

<label for="file">Photo {{ $p ? '(leave empty to keep the current photo)' : '' }}</label>
<input type="file" id="file" name="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
       {{ $p ? '' : 'required' }}
       style="width:100%;padding:10px;border:1px dashed var(--g2);border-radius:8px;background:#f9fff6;font-family:inherit">
<p class="muted" style="margin:6px 0 0">JPG, PNG or WebP, up to 5 MB. Only logged-in members can see your portfolio.</p>
@error('file')<div class="err">{{ $message }}</div>@enderror
