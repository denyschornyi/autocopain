<script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>

<script>
//$.ajaxPrefilter(function (options, originalOptions, xhr) {
//      var token = '{{ csrf_token() }}';
//
//      if (token) {
//          return xhr.setRequestHeader('X-CSRF-TOKEN', token);
//      }
//  });
//
//    tinymce.init({
//        selector: "textarea",
////    extended_valid_elements: 'span',
//        browser_spellcheck: true,
//        force_br_newlines: true,
//        force_p_newlines: false,
//        forced_root_block: '',
//        font_formats: 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;AkrutiKndPadmini=Akpdmi-n;Comic Sans MS=comic sans ms,sans-serif;Times New Roman=times new roman,times;',
//        fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
//        plugins: ["textcolor", "advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table paste"],
//        toolbar: "fontselect | fontsizeselect | forecolor backcolor | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
//        height: "300",
//        content_css: [
//            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
//            '//cdnjs.cloudflare.com/ajax/libs/prism/0.0.1/prism.css',
//            '//www.tinymce.com/css/codepen.min.css'
//        ],
//        image_title: true,
//        // enable automatic uploads of images represented by blob or data URIs
//        automatic_uploads: true,
//        // URL of our upload handler (for more details check: https://www.tinymce.com/docs/configure/file-image-upload/#images_upload_url)
//        images_upload_url: '/admin/fileUpload', 
////    images_upload_base_path: 'http://localhost/tgir',
//        // here we add custom filepicker only to Image dialog
//        file_picker_types: 'image',
//        // and here's our custom image picker
//        file_picker_callback: function (cb, value, meta) {
//            var input = document.createElement('input');
//            input.setAttribute('type', 'file');
//            input.setAttribute('accept', 'image/*');
//
//            // Note: In modern browsers input[type="file"] is functional without
//            // even adding it to the DOM, but that might not be the case in some older
//            // or quirky browsers like IE, so you might want to add it to the DOM
//            // just in case, and visually hide it. And do not forget do remove it
//            // once you do not need it anymore.
//
//            input.onchange = function () {
//                var file = this.files[0];
//
//                // Note: Now we need to register the blob in TinyMCEs image blob
//                // registry. In the next release this part hopefully won't be
//                // necessary, as we are looking to handle it internally.
//                var id = (new Date()).getTime();
//                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
//                var blobInfo = blobCache.create(id, file);
//                blobCache.add(blobInfo);
//
//                // call the callback and populate the Title field with the file name
//                cb(blobInfo.blobUri(), {title: file.name});
//            };
//
//            input.click();
//        }
//    });
</script>
<script>
    tinymce.init({
        selector: 'textarea',
        height: 500,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste imagetools"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ],
        image_title: true,
        automatic_uploads: true,
        images_upload_url: "{{url('fileUpload')}}",
        file_picker_types: 'image',
        file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            // Note: In modern browsers input[type="file"] is functional without 
            // even adding it to the DOM, but that might not be the case in some older
            // or quirky browsers like IE, so you might want to add it to the DOM
            // just in case, and visually hide it. And do not forget do remove it
            // once you do not need it anymore.

            input.onchange = function () {
                var file = this.files[0];
                console.log(file);

                // Note: Now we need to register the blob in TinyMCEs image blob
                // registry. In the next release this part hopefully won't be
                // necessary, as we are looking to handle it internally.
                var id = (new Date()).getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var blobInfo = blobCache.create(id, file);
                blobCache.add(blobInfo);

                // call the callback and populate the Title field with the file name
                cb(blobInfo.blobUri(), {title: file.name});
            };

            input.click();
        }
    });
</script>