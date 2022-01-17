<form method="post" action="{{ $action }}">
    @csrf
    @if($type == 'edit')
    @method('PUT')
    @endif
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" value="{{ old("name", $tag->name ?? '') }}" id="name" @class([
            'form-control',
            'form-control-md',
            'is-invalid' => $errors->has('name')
        ]) placeholder="Enter Tag name" />
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="d-grid gap-2 my-2">
        <button class="btn btn-primary" type="submit">Submit</button>
      </div>
</form>