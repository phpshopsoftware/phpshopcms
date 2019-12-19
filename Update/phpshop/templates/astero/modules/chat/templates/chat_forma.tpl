
<div id="chatModalPre">
    <form id="chatform">
        <div class="form-group" >
            <label>Имя</label>
            <input type="text" name="chat_mod_user_name" id="chat_mod_user_name" class="form-control input-sm" placeholder="Имя..." required="" value="@php echo $_SESSION[mod_chat_user_name]; php@">
        </div>
        <div class="text-center">
            <button type="button" class="btn btn-default btn-sm hide"  id="chatend">Закрыть</button> &nbsp;
            <button type="submit" class="btn btn-primary btn-sm pull-right" id="chatstart">@php if(empty($_SESSION['mod_chat_user_session'])) echo "Начать"; else echo "Далее"; php@</button>
        </div>
    </form>   
</div>
<input type="hidden" name="chat_mod_user_name_true" id="chat_mod_user_name_true" value="@php echo $_SESSION[mod_chat_user_name]; php@">
<button  class="btn btn-info chat"  id="chatbutton" data-placement="right"  data-html="true" data-content="123">Чат<span class="glyphicon glyphicon-user"></span></button>
<script>
    $().ready(function() {

        $('#chatbutton').hover(
                function() {
                    $(this).animate({"left": "0px"}, "slow");
                },
                function() {
                    $(this).animate({"left": "-36px"}, "slow");
                });

        $('.breadcrumb, .template-slider').waypoint(function() {
            $('#chatbutton').popover('hide');
        });

        $("body").on("click", "#chatend", function() {
            $('#chatbutton').popover('hide');
            $('#chatModalPre').modal('hide');
            $('#chatend').addClass('hide');
        });


        $("body").on("input", "#chat_mod_user_name", function() {
            $('#chat_mod_user_name_true').val(($(this).val()));
        });

        $('#chatbutton').popover();
        $('#chatbutton').on('show.bs.popover', function() {
            $('#chatend').removeClass('hide');
            $('#chatbutton').attr('data-content', $("#chatModalPre").html());
        });

        $(document).on('submit', '#chatform', function() {

            if ($('#chat_mod_user_name_true').val().length > 0) {
                var url = ('https:' == document.location.protocol ? 'https://' : 'http://')+'@serverName@/phpshop/modules/chat/chat.php?name=' + $('#chat_mod_user_name_true').val();
                $('.chat-modal-content').attr('src', url);
                $('#chatModal').modal('show');
                $('#chatstart').html('Далее');
                $('#chatbutton').popover('hide');
                $('#chatend').addClass('hide');
            }
            return false;
        });

        $('#chatopenwindow').on('click', function() {
            var w = 500;
            var h = 550;
            var url = '//@serverName@phpshop/modules/chat/chat.php?name=' + $('#chat_mod_user_name').val();
            chat = window.open(url, "chat", "dependent=1,left=100,top=20,width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
            chat.focus();
            $('#chatModal').modal('hide');
            $('.chat-modal-content').attr('src', null);
        });

    });
</script>
<!-- Модальное окно чата -->
<div class="modal bs-example-modal" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>


                <span class="btn btn-default btn-sm pull-left glyphicon glyphicon-th-large" id="chatopenwindow" data-toggle="tooltip" data-placement="bottom" title="Открыть в окне"></span>

                <h4 class="modal-title" id="myModalLabel">Чат онлайн</h4>
            </div>
            <div class="modal-body">
                <iframe class="chat-modal-content"></iframe>

            </div>
        </div>
    </div>
</div>
<!--/ Модальное окно чата -->