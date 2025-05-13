@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>Categories infomation</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{ route('admin.index') }}">
                                                <div class="text-tiny">Dashboard</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.category.create') }}">
                                                <div class="text-tiny">Categories</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">New Category</div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- new-category -->
                                <div class="wg-box">
                                    <form class="form-new-product form-style-1" action="{{route('admin.category.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <fieldset class="name">
                                            <div class="body-title">Category Name <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Category name" name="name"
                                                tabindex="0" value="{{old('name')}}" aria-required="true" required="">
                                        </fieldset>
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                        <fieldset>
                                            <div class="body-title">Upload images <span class="tf-color-1">*</span> <small>(Only one image)</small></div>
                                            <div class="upload-image flex-grow">
                                                <div class="item" id="imgpreview" style="display:none">
                                                    <img src="" class="effect8" alt="">
                                                </div>
                                                <div id="upload-file" class="item up-load">
                                                    <label class="uploadfile" for="myFile">
                                                        <span class="icon">
                                                            <i class="icon-upload-cloud"></i>
                                                        </span>
                                                        <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                                        <input type="file" id="myFile" name="image_name" accept="image/*">
                                                    </label>
                                                </div>
                                            </div>
                                        </fieldset>
                                        @error('image_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        <fieldset>
                                            <div class="body-title">Upload Gallery Images <span class="tf-color-1">*</span> <small>(You can select multiple images)</small></div>
                                            <div class="upload-image flex-grow">
                                                <div id="gallery-preview" class="flex" style="gap:10px; margin-bottom:10px;"></div>
                                                <div id="gal-upload-file" class="item up-load">
                                                    <label class="uploadfile" for="gFile">
                                                        <span class="icon">
                                                            <i class="icon-upload-cloud"></i>
                                                        </span>
                                                        <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                                        <input type="file" id="gFile" name="images[]" accept="image/*" multiple>
                                                    </label>
                                                </div>
                                            </div>
                                        </fieldset>
                                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                                        <div class="bot">
                                            <div></div>
                                            <button class="tf-button w208" type="submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
@endsection

@push('scripts')
<script>
    $(function(){
        $('#myFile').change(function(e) {
            const [file] = this.files;
            if (file) {
                $('#imgpreview img').attr('src', URL.createObjectURL(file));
                $('#imgpreview').show();
            }
        });

        $('#gFile').change(function(e) {
            $('#gallery-preview').empty();
            const files = this.files;
            if (files.length) {
                $.each(files, function(i, file) {
                    const img = $('<img>').attr('src', URL.createObjectURL(file)).css({width: '80px', margin: '5px'});
                    $('#gallery-preview').append(img);
                });
            }
        });
    });
</script>
@endpush