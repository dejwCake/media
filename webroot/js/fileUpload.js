var FileUpload = {
    init: function(context) {
        var fileUpload = this;
        context.multiple = context.find('[name="fileinput[]"]').prop('multiple');
        context.files = context.find('[data-provides="files"]');
        context.hiddenInputs = context.find('[data-provides="hiddenInputs"]');
        context.collectionName = context.attr('data-collection-name');
        var i = 0;

        //Prepare form with
        var media = JSON.parse(context.attr('data-media'));
        if(typeof media != typeof undefined && media != null) {
            $.each(media, function (index, medium) {
                media[index].index = i;
                i++;
                if(typeof medium.name != typeof undefined && medium.name != null && medium.name != '') {
                    //attach rendered template
                    fileUpload.attachFile(medium, context);

                    var responseFile = new Object();
                    responseFile.id = medium.id;
                    responseFile.url = medium.file;
                    responseFile.name = medium.name;
                    responseFile.thumbUrl = medium.thumb;
                    responseFile.deleted = medium.deleted;
                    responseFile.title = medium.title;

                    fileUpload.attachFileFinished(medium.index, context, responseFile);
                    //Fill hidden inputs with proper values
                    fileUpload.setHiddenValues(medium.index, context, responseFile);
                    fileUpload.setTitleValues(medium.index, context, responseFile);
                }
            });
        }

        context.fileupload({
            //dropZone: $('.<?//= $collection["name"] ?>//-fileinput-preview'),
            url : context.attr('data-upload-url'),
            singleFileUploads : true,

            // This function is called when a file is added to the queue;
            // either via the browse button, or via drag/drop:
            add: function (e, data) {

                //Clear files element, because only one file can be added
                if(!context.multiple) {
                    context.files.empty();
                    context.hiddenInputs.empty();
                }

                //Add filename and progress bar from template
                $.each(data.files, function (index, file) {
                    data.files[index].index = i;
                    i++;
                    //attach rendered template
                    fileUpload.attachFile(file, context);
                });

                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
            },

            done: function(e, data){
                var responseFiles = data.result.response;
                $.each(data.files, function (index, file) {
                    $.each(responseFiles, function(index, responseFile) {
                        if(file.name == responseFile.original_filename) {
                            responseFile.id = '';
                            responseFile.url = responseFile.original_filepath;
                            responseFile.name = responseFile.original_filename;
                            responseFile.thumbUrl = "/" + responseFile.dir + "/" + encodeURIComponent(responseFile.filename);
                            responseFile.deleted = 0;

                            fileUpload.attachFileFinished(file.index, context, responseFile);
                            //Fill hidden inputs with proper values
                            fileUpload.setHiddenValues(file.index, context, responseFile);
                            return;
                        }
                    });
                });
            },

            fail: function(e, data){
                console.log(data);
                $.each(data.files, function (index) {
                    var error = $('<span class="text-danger"/>').text(context.attr('data-error-message'));
                    $(dataContext.children()[index])
                        .append('<br>')
                        .append(error);
                });
            },

            processalways: function(e, data) {
                var index = data.index,
                    file = data.files[index],
                    node = $(dataContext.children()[index]);
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

    attachFile: function(file, context) {
        var fileUpload = this;

        //Create block from template
        var template = $(context.attr('data-use-template')).html();
        Mustache.parse(template);
        var rendered = Mustache.render(template, {filename: file.name, index: file.index});
        context.files.append(rendered);

        //prepare remove button
        fileUpload.setRemoveButton(context.files.find('[data-index="'+file.index+'"]'), context);
        context.files.find('[data-index="'+file.index+'"]').find('[data-provides="remove-button"]').hide();

        //TODO change title name attribute
        context.files.find('[data-index="'+file.index+'"]').find('[data-provides="title"]').each(function() {
            $(this).attr('name', 'medium['+context.collectionName+']['+file.index+'][title]['+$(this).attr('data-locale')+']');
        });
    },

    attachFileFinished: function (index, context, responseFile) {
        //Remove progress bar
        context.files.find('[data-index="'+index+'"]').find('.progress').remove();
        //Show remove button
        context.files.find('[data-index="'+index+'"]').find('[data-provides="remove-button"]').show();
        //Set image if exists
        if(responseFile && responseFile.thumbUrl) {
            context.files.find('[data-index="'+index+'"]').find('[data-provides="thumb-image"]').attr('src', responseFile.thumbUrl).removeClass('hidden');
        }
        //Change add button text
        if(!context.multiple) {
            $(context.find('[data-provides="button-text"]')).text($(context.find('[data-provides="button-text"]')).attr('data-selected-text'));
        }
    },

    setHiddenValues: function(index, context, responseFile) {
        //Fill hidden inputs with proper values
        if(context.hiddenInputs.find('[data-provides="fileInput"][data-index="'+index+'"]').length > 0) {
            context.hiddenInputs.find('[data-provides="idInput"][data-index="'+index+'"]').val(responseFile.id);
            context.hiddenInputs.find('[data-provides="fileInput"][data-index="'+index+'"]').val(responseFile.url);
            context.hiddenInputs.find('[data-provides="nameInput"][data-index="'+index+'"]').val(responseFile.name);
            context.hiddenInputs.find('[data-provides="thumbInput"][data-index="'+index+'"]').val(responseFile.thumbUrl);
            context.hiddenInputs.find('[data-provides="deletedInput"][data-index="'+index+'"]').val(responseFile.deleted);
        } else {
            var idInput = $('<input>').attr('type','hidden')
                .attr('name','medium['+context.collectionName+']['+index+'][id]')
                .attr('data-provides','idInput')
                .attr('data-index',index)
                .val(responseFile.id);
            var fileInput = $('<input>').attr('type','hidden')
                .attr('name','medium['+context.collectionName+']['+index+'][file]')
                .attr('data-provides','fileInput')
                .attr('data-index',index)
                .val(responseFile.url);
            var nameInput = $('<input>').attr('type','hidden')
                .attr('name','medium['+context.collectionName+']['+index+'][name]')
                .attr('data-provides','nameInput')
                .attr('data-index',index)
                .val(responseFile.name);
            var thumbInput = $('<input>').attr('type','hidden')
                .attr('name','medium['+context.collectionName+']['+index+'][thumb]')
                .attr('data-provides','thumbInput')
                .attr('data-index',index)
                .val(responseFile.thumbUrl);
            var deletedInput = $('<input>').attr('type','hidden')
                .attr('name','medium['+context.collectionName+']['+index+'][deleted]')
                .attr('data-provides','deletedInput')
                .attr('data-index',index)
                .val(responseFile.deleted);
            context.hiddenInputs.append(idInput);
            context.hiddenInputs.append(fileInput);
            context.hiddenInputs.append(nameInput);
            context.hiddenInputs.append(thumbInput);
            context.hiddenInputs.append(deletedInput);
        }
    },

    setTitleValues: function(index, context, responseFile) {
        //Fill title inputs with proper values
        if(context.files.find('[data-index="'+index+'"]').find('[data-provides="title"]').length > 0) {
            $.each(responseFile.title, function (locale, title) {
                context.files.find('[data-index="'+index+'"]').find('[data-provides="title"][data-locale="'+locale+'"]').val(title);
            });
        }
    },

    setRemoveButton: function(item, context) {
        var index = item.attr('data-index');
        $(item).find('[data-provides="remove-button"]').on('click', function(event) {
            event.preventDefault();
            $(this).closest('[data-provides="file-info"]').remove();
            if(context.find('[data-provides="idInput"][data-index="'+index+'"]').val() == '') {
                //if id is empty, we can remove also hidden elements
                context.hiddenInputs.find('[data-provides="idInput"][data-index="'+index+'"]').remove();
                context.hiddenInputs.find('[data-provides="fileInput"][data-index="'+index+'"]').remove();
                context.hiddenInputs.find('[data-provides="nameInput"][data-index="'+index+'"]').remove();
                context.hiddenInputs.find('[data-provides="thumbInput"][data-index="'+index+'"]').remove();
                context.hiddenInputs.find('[data-provides="deletedInput"][data-index="'+index+'"]').remove();
            } else {
                //if not empty, we need to send info to delete this item
                context.hiddenInputs.find('[data-provides="fileInput"][data-index="'+index+'"]').val('');
                context.hiddenInputs.find('[data-provides="nameInput"][data-index="'+index+'"]').val('');
                context.hiddenInputs.find('[data-provides="thumbInput"][data-index="'+index+'"]').val('');
                context.hiddenInputs.find('[data-provides="deletedInput"][data-index="'+index+'"]').val(1);
            }

            //Change add button text
            if(!context.multiple) {
                $(context.find('[data-provides="button-text"]')).text($(context.find('[data-provides="button-text"]')).attr('data-empty-text'));
            }
        });
    }
};