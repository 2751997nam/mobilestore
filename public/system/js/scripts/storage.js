window.addEventListener('message', function(event) {
    if (~event.origin.indexOf('https://s3.shopbay.vn')) {
        let data = event.data;
        switch (data.function) {
            case 'useCkeditor3':
                window.CKEDITOR.tools.callFunction(data.CKEditorFuncNum, data.url);
                break;
            default:
                window.SetUrl(data.url, data.file_path);
                break;

        }
    } else {
        return;
    }
});
