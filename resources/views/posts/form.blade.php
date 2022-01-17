<form method="post" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if($type == 'edit')
    @method('PUT')
    @endif
    <div class="form-group">
        <label for="title">{{ __('site.title') }} :</label>
        <input type="text" name="title" value="{{ old("title", $post->title ?? '') }}" id="title" @class([
            'form-control',
            'form-control-md',
            'is-invalid' => $errors->has('title')
        ]) placeholder="Enter title" />
        @error('title')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group">
        {{ __('site.tags') }} :
        <div class="border rounded p-2 my-1">
            @forelse($tags as $tag)
            <div class="form-check my-2">
            <label class="form-check-label">
                <input type="checkbox" name="tags[{{ $tag->id }}]" class="form-check-input" value="{{ $tag->id }}" {{old('tags.' . $tag->id, (isset($post) ? optional($post->tags->where('name', $tag->name)->first())->id : '')) == $tag->id ? 'checked' : '' }} /> {{ $tag->name }}
            </label>
            </div>
            @empty
            <div class="text-center">No tags</div>
            @endforelse
        </div>
    </div>
    <div class="form-group my-1">
        <label for="image">{{ __('site.image') }} :</label>
        @if($type == 'edit' && $post->image)
        <div class="my-2">
            <a href="{{ $post->image->url() ?? '' }}" target="_blank">
                <img class="rounded" style="width: 60px; height: 60px" src="{{ $post->image->url() ?? '' }}" />
            </a>
        </div>
        @endif
        <input type="file" id="image" @class([
            "form-control",
            "is-invalid" => $errors->has('image')
        ]) name="image" />
        @error('image')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <label for="content" class="form-check-label">{{ __('site.content') }} :</label>
        <textarea name="content" placeholder="Enter content" id="content" rows="3" @class([
            'form-control',
            'is-invalid' => $errors->has('content')
        ])>{{ old("content", $post->content ?? '') }}</textarea>
        @error('content')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="d-grid gap-2 my-2">
        <button class="btn btn-primary" type="submit">{{ __('site.submit') }}</button>
      </div>
</form>