<div class="form-group row">
    <div class="label col-md-4 col-sm-4 text-right">
        <label for="{{ $id }}">{{ $label }}</label>
    </div>
    <div class="col-md-8 col-sm-8">
        <select id="{{ $id  }}" name="{{ $id }}" class="form-control {{ $errors->has($id) ? 'invalid':'' }}">
            <option selected disabled value="0">{{ $placeholder }}</option>
            @foreach($collection as $item)
                <option @isset($object) @if($object->$id == $item->id) selected @endif @endisset
                value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    @include("includes/validationErrors", ['errFieldName' => $id])
</div>