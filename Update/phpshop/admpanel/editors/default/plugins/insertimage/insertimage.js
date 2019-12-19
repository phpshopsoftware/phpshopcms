if (!RedactorPlugins)
    var RedactorPlugins = {};

RedactorPlugins.insertimage = function()
{
    return {
        getTemplate: function()
        {
            return String()
                    + '<section id="redactor-modal-advanced">'
                    + '<div class="input-group">'
                    + '<input type="text" class="form-control input-sm" placeholder="'+this.lang.get('image_web_link')+'" id="mymodal-textarea">'
                    + '<span class="input-group-btn">'
                    + '<button class="btn btn-default btn-sm" type="button" id="redactorimg" data-toggle="modal" data-target="#elfinderModal">'+this.lang.get('choose')+'</button>'
                    + '</span>'
                    + '</div>'
                    + '</section>';
        },
        init: function()
        {
            var button = this.button.add('insertimage', this.lang.get('image'));
            this.button.addCallback(button, this.insertimage.show);

            // make your added button as Font Awesome's icon
            this.button.setAwesome('insertimage', 're-icon re-image');
        },
        show: function()
        {
            $('.elfinder-modal-content').attr('data-path', 'image');
            RedactorModalOpen = 1;

            this.modal.addTemplate('insertimage', this.insertimage.getTemplate());

            this.modal.load('insertimage', this.lang.get('image'), 500);

            this.modal.createCancelButton();

            var button = this.modal.createActionButton(this.lang.get('insert'));
            button.on('click', this.insertimage.insert);

            this.selection.save();
            this.modal.show();

            $('#mymodal-textarea').focus();

        },
        insert: function()
        {

            if ($('#mymodal-textarea').val())
                this.image.insert('<img src="' + $('#mymodal-textarea').val() + '">');
            redactorModalOpen = 0;

        }
    };
};