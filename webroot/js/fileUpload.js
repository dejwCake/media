var FileUpload = {
    init: function(context) {
        var fileUpload = this;

        //Prepare form with
        var medium = JSON.parse(context.attr('data-medium'));
        if(typeof medium != typeof undefined && medium != null) {
            var dataContext = context.find('.files');

            //attach rendered template
            var url = medium.file;
            var name = medium.name;
            var thumbUrl = medium.thumb;
            var deleted = medium.deleted;
            var index = 0;
            if(typeof medium.name != typeof undefined && medium.name != null && medium.name != '') {
                fileUpload.attachFile(dataContext, index, name, context);

                fileUpload.attachFileFinished(dataContext, context);

                if(thumbUrl) {
                    dataContext.find('[data-provides="thumb-image"]').attr('src', thumbUrl).removeClass('hidden');
                }
            }

            //Fill hidden inputs with proper values
            context.find('[data-provides="fileInput"]').val(url);
            context.find('[data-provides="nameInput"]').val(name);
            context.find('[data-provides="thumbInput"]').val(thumbUrl);
            context.find('[data-provides="deletedInput"]').val(deleted);
        }

        context.fileupload({
            //dropZone: $('.<?//= $collection["name"] ?>//-fileinput-preview'),
            url : context.attr('data-upload-url'),

            // This function is called when a file is added to the queue;
            // either via the browse button, or via drag/drop:
            add: function (e, data) {
                data.context = context.find('.files');

                //Clear files element, because only one file can be added
                //TODO change for multiple
                data.context.empty();

                //Add filename and progress bar from template
                $.each(data.files, function (index, file) {
                    //attach rendered template
                    fileUpload.attachFile(data.context, index, file.name, context);
                });

                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
            },

            done: function(e, data){
                dataResponse = data.result.response;
                var url = dataResponse.original_filepath;
                var name = dataResponse.original_filename;
                var thumbUrl = "/" + dataResponse.dir + "/" + encodeURIComponent(dataResponse.filename);

                fileUpload.attachFileFinished(data.context, context);

                //Set image if exists
                data.context.find('[data-provides="thumb-image"]').attr('src', thumbUrl).removeClass('hidden');

                //Fill hidden inputs with proper values
                context.find('[data-provides="fileInput"]').val(url);
                context.find('[data-provides="nameInput"]').val(name);
                context.find('[data-provides="thumbInput"]').val(thumbUrl);
                context.find('[data-provides="deletedInput"]').val(0);
            },

            fail: function(e, data){
                $.each(data.files, function (index) {
                    var error = $('<span class="text-danger"/>').text(context.attr('data-error-message'));
                    $(data.context.children()[index])
                        .append('<br>')
                        .append(error);
                });
            },

            processalways: function(e, data) {
                var index = data.index,
                    file = data.files[index],
                    node = $(data.context.children()[index]);
                if (file.preview) {
                    node
                        .prepend('<br>')
                        .prepend(file.preview);
                }
                if (file.error) {
                    node
                        .append('<br>')
                        .append($('<span class="text-danger"/>').text(file.error));
                }
            },

            progressall: function(e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                context.find('.progress-bar').css('width', progress + '%');
            },
        });
    },

    attachFile: function(dataContext, index, fileName, context) {
        var fileUpload = this;
        var template = $(context.attr('data-use-template')).html();
        Mustache.parse(template);

        var rendered = Mustache.render(template, {filename: fileName, index: index});
        dataContext.append(rendered);

        //prepare remove button
        fileUpload.setRemoveButton(dataContext.children()[index], context);
        dataContext.find('[data-provides="remove-button"]').hide();
    },

    attachFileFinished: function (dataContext, context) {
        //Remove progress bar
        dataContext.find('.progress').remove();
        //Show remove button
        dataContext.find('[data-provides="remove-button"]').show();
        //Change add button text
        var addButton = context.find('[data-provides="button-text"]');
        $(addButton).text($(addButton).attr('data-selected-text'));
    },

    setRemoveButton: function(item, context) {
        $(item).find('[data-provides="remove-button"]').on('click', function(event) {
            event.preventDefault();
            $(this).closest('[data-provides="file-info"]').remove();
            context.find('[data-provides="fileInput"]').val('');
            context.find('[data-provides="nameInput"]').val('');
            context.find('[data-provides="thumbInput"]').val('');
            context.find('[data-provides="deletedInput"]').val(1);

            //Change add button text
            var addButton = context.find('[data-provides="button-text"]');
            $(addButton).text($(addButton).attr('data-empty-text'));
        });
    }
};