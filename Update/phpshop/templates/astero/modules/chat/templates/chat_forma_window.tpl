

<div class="modal fade bs-example-modal-sm" id="chatModalPre" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">������ ���</h4>
            </div>
            <div class="modal-body">
                <form id="chatform">
                <div class="form-group">
                    <input type="text" name="chat_mod_user_name" id="chat_mod_user_name" class="form-control input-sm" placeholder="���..." value="@php echo $_SESSION[mod_chat_user_name]; php@">
                </div>
                <div >
                    <button type="button" class="btn btn-default btn-sm" id="chatend">�������</button> &nbsp;
                    <button type="submit" class="btn btn-primary btn-sm pull-right" id="chatstart">@php if(empty($_SESSION['mod_chat_user_session'])) echo "������"; else echo "�����"; php@</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<a href="#" data-toggle="modal" data-target="#chatModalPre" class="btn btn-success">������ ���!</a>
<input type="hidden" name="chat_mod_user_name_true" id="chat_mod_user_name_true" value="@php echo $_SESSION[mod_chat_user_name]; php@">
<button href="#"  class="btn btn-info chat"  id="chatbutton" data-placement="right"  data-html="true" data-content="123">���<span class="glyphicon glyphicon-user"></span></button>
<script>

    $().ready(function() {
        
                $('#chatbutton').hover(
                function() {
                    $(this).animate({"left": "0px"}, "slow");
                },
                function() {
                    $(this).animate({"left": "-36px"}, "slow");
                });

        $("body").on("click", "#chatend", function() {
            $('#chatbutton').popover('hide');
            $('#chatModalPre').modal('hide');
        });


        $('.breadcrumb, .template-slider').waypoint(function() {
            $('#chatbutton').popover('hide');
        });

        $("body").on("input", "#chat_mod_user_name", function() {
              $('#chat_mod_user_name_true').val(($(this).val()));
        });

        $('#chatbutton').popover();
        $('#chatbutton').on('show.bs.popover', function() {

            $('#chatbutton').attr('data-content', $("#chatModalPre .modal-body").html());
        });


        $('#chatopenwindow').on('click', function() {          
            var w = 500;
            var h = 550;
            var url = '//@serverName@phpshop/modules/chat/chat.php?name=' + $('#chat_mod_user_name').val();
            chat = window.open(url, "chat", "dependent=1,left=100,top=20,width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
            chat.focus();
            $('#chatModal').modal('hide');
        });
        

        $(document).on('submit', '#chatform', function() {
  
            if ($('#chat_mod_user_name_true').val().length > 0) {
                var url = '//@serverName@/phpshop/modules/chat/chat.php?name=' + $('#chat_mod_user_name_true').val();
                $('.chat-modal-content').attr('src', url);
                $('#chatModal').modal('show');
                $('#chatstart').html('����������');
                $('#chatbutton').popover('hide');
                $('#chatend').addClass('hide');
                $('#chatModalPre').modal('hide');
            }
            return false;
        });


    });
</script>

<!-- ��������� ���� ���� -->
<div class="modal bs-example-modal" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>


                <span class="btn btn-default btn-sm pull-left glyphicon glyphicon-th-large" id="chatopenwindow" data-toggle="tooltip" data-placement="bottom" title="������� � ����"></span>

                <h4 class="modal-title" id="myModalLabel">��� ������</h4>
            </div>
            <div class="modal-body ">
                <iframe class="chat-modal-content"></iframe>

            </div>
        </div>
    </div>
</div>
<!--/ ��������� ���� ���� -->