// Shared CKEditor adapter: send the session CSRF token in a header, never in a URL.
function SecureUploadPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
        let xhr;
        return {
            upload: function () {
                return loader.file.then(function (file) {
                    return new Promise(function (resolve, reject) {
                        xhr = new XMLHttpRequest();
                        xhr.open('POST', editor.config.get('ckfinder.uploadUrl'));
                        xhr.responseType = 'json';
                        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                        xhr.addEventListener('error', function () { reject('Upload gagal.'); });
                        xhr.addEventListener('abort', function () { reject('Upload dibatalkan.'); });
                        xhr.addEventListener('load', function () {
                            const data = xhr.response;
                            if (xhr.status < 200 || xhr.status >= 300 || !data || !data.url) {
                                reject(data && data.error ? data.error.message : 'Upload gagal.');
                                return;
                            }
                            resolve({default: data.url});
                        });
                        xhr.upload.addEventListener('progress', function (event) {
                            if (event.lengthComputable) {
                                loader.uploadTotal = event.total;
                                loader.uploaded = event.loaded;
                            }
                        });
                        const form = new FormData();
                        form.append('upload', file);
                        xhr.send(form);
                    });
                });
            },
            abort: function () { if (xhr) xhr.abort(); }
        };
    };
}
