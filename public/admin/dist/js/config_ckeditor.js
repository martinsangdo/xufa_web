/*
* Author: VietNgo
* Created date: 20170722
*/

function ckeditor (name) {

    CKEDITOR.config.extraPlugins = 'filebrowser';
    CKEDITOR.config.uiColor = '#9AB8F3';
    CKEDITOR.config.language = 'vi';
    CKEDITOR.config.fillEmptyBlocks = false;

    CKEDITOR.replace( name, {
        filebrowserBrowseUrl: '/admin/plugins/ckeditor/plugins/filemanager/browser/default/browser.html?Connector=/browser/browse',
        filebrowserUploadUrl: '/uploader/upload?Type=File',
        filebrowserImageUploadUrl: '/uploader/upload?Type=Image',
        filebrowserFlashUploadUrl: '/uploader/upload?Type=Flash',
        toolbar:[
            ['Source','-','Save','NewPage','Preview','-','Templates'],
            ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print'],
            ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
            ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'HiddenField'],
            ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
            ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            ['Link','Unlink','Anchor'],
            ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
            ['Styles','Format','Font','FontSize'],
            ['TextColor','BGColor'],
            ['Maximize', 'ShowBlocks','-','About']
        ]
    });
}