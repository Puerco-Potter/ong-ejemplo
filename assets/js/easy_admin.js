import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
var textareas = $( '.ckeditor' );
textareas.each( function() {
    var textarea_id=$(this).find('textarea').attr('id');
    console.log(textarea_id);
    ClassicEditor
        .create( document.querySelector('#'+textarea_id) )
        .catch( error => {
            console.error( error );
        } );
});