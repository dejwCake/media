var FileUpload = {
    init: function(context) {
        context.fileupload({
//                    dropZone: $('.<?//= $collection["name"] ?>//-fileinput-preview'),
            url : context.attr('data-upload-url'),

            // This function is called when a file is added to the queue;
            // either via the browse button, or via drag/drop:
            add: function (e, data) {
                console.log(data);
                data.context = context.find('.files');

                //Clear files element, because only one file can be added
                data.context.empty();

                //Add filename and progress bar from template
                var template = $(context.attr('data-use-template')).html();
                Mustache.parse(template);

                $.each(data.files, function (index, file) {
                    //attach rendered template
                    var rendered = Mustache.render(template, {filename: file.name, index: index});
                    data.context.append(rendered);

                    //prepare remove button
                    $(data.context.children()[index]).find('[data-provides="remove-button"]').on('click', function(event) {
                        event.preventDefault();
                        $(this).closest('[data-provide="file-info"]').remove();
                        context.find('[data-provides="mediumInput"]').val('');
                        context.find('[data-provides="hasDeletedInput"]').val(1);

                        //Change add button text
                        var addButton = context.find('[data-provides="button-text"]');
                        $(addButton).text($(addButton).attr('data-empty-text'));
                    });
                    data.context.find('[data-provides="remove-button"]').hide();
                });

                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
            },

            done: function(e, data){
                dataResponse = data.result.response;
                var originalUrl = dataResponse.original_filepath;

                //Remove progress bar
                data.context.find('.progress').remove();
                //Show remove button
                data.context.find('[data-provides="remove-button"]').show();
                //Change add button text
                var addButton = context.find('[data-provides="button-text"]');
                $(addButton).text($(addButton).attr('data-selected-text'));

                //Fill hidden inputs with proper values
                context.find('[data-provides="mediumInput"]').val(originalUrl);
                context.find('[data-provides="hasDeletedInput"]').val(0);
            },

            fail: function(e, data){
                $.each(data.files, function (index) {
                    //TODO get text from context
                    var error = $('<span class="text-danger"/>').text('File upload failed.');
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
    }
};