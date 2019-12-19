if (!RedactorPlugins)
    var RedactorPlugins = {};

RedactorPlugins.insertfile = function()
{
    return {
        getTemplate: function()
        {
            return String()
                    + '<section id="redactor-modal-advanced">'
                    + '<label>'+this.lang.get('filename')+'</label>'
                    + '<div><input type="text" class="form-control input-sm" id="mymodal-title"></div><br><br>'
                    + '<div class="input-group">'
                    + '<input type="text" class="form-control input-sm" placeholder="'+this.lang.get('file_web_link')+'" id="mymodal-textarea">'
                    + '<span class="input-group-btn">'
                    + '<button class="btn btn-default btn-sm" type="button" id="redactorimg" data-toggle="modal" data-target="#elfinderModal">'+this.lang.get('choose')+'</button>'
                    + '</span>'
                    + '</div>'
                    + '</section>';
        },
        init: function()
        {
            var button = this.button.add('insertfile', this.lang.get('file'));
            this.button.addCallback(button, this.insertfile.show);

            // make your added button as Font Awesome's icon
            this.button.setAwesome('insertfile', 're-icon re-file');
        },
        show: function()
        {
            $('.elfinder-modal-content').attr('data-path', 'file');
            RedactorModalOpen = 1;

            this.modal.addTemplate('insertfile', this.insertfile.getTemplate());

            this.modal.load('insertfile', this.lang.get('file'), 500);

            this.modal.createCancelButton();

            var button = this.modal.createActionButton(this.lang.get('insert'));
            button.on('click', this.insertfile.insert);

            this.selection.save();
            this.modal.show();

            $('#mymodal-textarea').focus();

        },
        insert: function()
        {

            if ($('#mymodal-textarea').val()) {

                // ָלÿ פאיכא
                if (!$('#mymodal-title').val()) {
                    $('#mymodal-title').val($('#mymodal-textarea').val().split('/UserFiles/Files/').join(''));
                }

                this.file.insert('<a href="' + $('#mymodal-textarea').val() + '">' + $('#mymodal-title').val() + '</a>');
            }
            redactorModalOpen = 0;

        }
    };
};