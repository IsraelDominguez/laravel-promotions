<div class="col-12">
    <div class="tab-container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#seoshare" role="tab">Seo and Share</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#firstpage" role="tab">First Page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#resultpage" role="tab">Result Page</a>
            </li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane active fade show" id="seoshare" role="tabpanel" aria-expanded="true">
                @include("promotion::promotions.templates.seoshare")
            </div>

            <div class="tab-pane fade" id="firstpage" role="tabpanel" aria-expanded="true">
                <div id="image-background-preview" class="image-preview">
                    <label for="image-background" id="image-label">Background</label>
                    <input type="file" name="image-background" id="image-background" class="image-input" data-preview="image-background-preview"/>
                </div>

                <div id="image-header-preview" class="image-preview">
                    <label for="image-header" id="image-label-2">Header</label>
                    <input type="file" name="image-header" id="image-header" class="image-input" data-preview="image-header-preview"/>
                </div>

                <div class="form-group">
                    <label>Legal Link</label>
                    <input type="text" class="form-control" maxlength="100" name="legal" id="legal" value="{{ old('legal', isset($promotion) ? $promotion->legal : null) }}">
                    <i class="form-group__bar"></i>
                </div>

            </div>


            <div class="tab-pane fade" id="resultpage" role="tabpanel" aria-expanded="true">

    {{--            <select class="select2" name="type_id" id="promo_type">--}}
    {{--                <option value="">- Select -</option>--}}
    {{--                @foreach ($promo_types as $type)--}}
    {{--                    <option value="{{$type->id}}"--}}
    {{--                            @if ((old('type_id', isset($promotion) ? $promotion->type_id : null) == $type->id))--}}
    {{--                            selected--}}
    {{--                        @endif--}}
    {{--                    >{{$type->name}}</option>--}}
    {{--                @endforeach--}}
    {{--            </select>--}}

            </div>
        </div>
    </div>

</div>

<style>
    .image-preview {
        width: 400px;
        height: 400px;
        position: relative;
        overflow: hidden;
        background-color: #ffffff;
        color: #ecf0f1;
    }
    .image-preview input {
        line-height: 200px;
        font-size: 200px;
        position: absolute;
        opacity: 0;
        z-index: 10;
    }
    .image-preview label {
        position: absolute;
        z-index: 5;
        opacity: 0.8;
        cursor: pointer;
        background-color: #bdc3c7;
        width: 200px;
        height: 50px;
        font-size: 20px;
        line-height: 50px;
        text-transform: uppercase;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        text-align: center;
    }
</style>

@push('custom-js')
    <script>
        (function ($) {
            $.extend({
                uploadPreview : function (options) {

                    // Options + Defaults
                    var settings = $.extend({
                        input_field: ".image-input",
                        preview_box: ".image-preview",
                        label_field: ".image-label",
                        label_default: "Choose File",
                        label_selected: "Change File",
                        no_label: true,
                        success_callback : null,
                    }, options);

                    // Check if FileReader is available
                    if (window.File && window.FileList && window.FileReader) {
                        if (typeof($(settings.input_field)) !== 'undefined' && $(settings.input_field) !== null) {
                            $(settings.input_field).change(function(e) {
                                var files = this.files;
                                console.log($(this).data('preview'));
                                var preview_box = "#"+$(this).data('preview');

                                if (files.length > 0) {
                                    var file = files[0];
                                    var reader = new FileReader();

                                    // Load file
                                    reader.addEventListener("load",function(event) {
                                        var loadedFile = event.target;

                                        // Check format
                                        if (file.type.match('image')) {
                                            // Image
                                            $(preview_box).css("background-image", "url("+loadedFile.result+")");
                                            $(preview_box).css("background-size", "cover");
                                            $(preview_box).css("background-position", "center center");
                                        } else {
                                            alert("This file type is not supported yet.");
                                        }
                                    });

                                    if (settings.no_label == false) {
                                        // Change label
                                        $(settings.label_field).html(settings.label_selected);
                                    }

                                    // Read the file
                                    reader.readAsDataURL(file);

                                    // Success callback function call
                                    if(settings.success_callback) {
                                        settings.success_callback();
                                    }
                                } else {
                                    if (settings.no_label == false) {
                                        // Change label
                                        $(settings.label_field).html(settings.label_default);
                                    }

                                    // Clear background
                                    $(settings.preview_box).css("background-image", "none");

                                    // Remove Audio
                                    $(settings.preview_box + " audio").remove();
                                }
                            });
                        }
                    } else {
                        alert("You need a browser with file reader support, to use this form properly.");
                        return false;
                    }
                }
            });
        })(jQuery);


        $(document).ready(function() {
            $.uploadPreview({
                // input_field: "#image-upload",   // Default: .image-upload
                // preview_box: "#image-preview",  // Default: .image-preview
                // label_field: "#image-label",    // Default: .image-label
                // label_default: "Choose File",   // Default: Choose File
                // label_selected: "Change File",  // Default: Change File
                no_label: true                 // Default: false
            });
        });
    </script>


@endpush
