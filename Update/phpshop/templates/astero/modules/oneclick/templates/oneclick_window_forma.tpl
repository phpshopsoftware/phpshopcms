

<div class="modal fade bs-example-modal-sm" id="oneClickModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Быстрый заказ</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="post" name="user_forma" action="@ShopDir@/oneclick/">
                    <div class="form-group">
                        <label>Имя</label>
                        <input type="text" name="oneclick_mod_name" class="form-control" placeholder="Имя..." required="">
                    </div>
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="text" name="oneclick_mod_tel" class="form-control" placeholder="Телефон..." required="">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="oneclick_mod_product_id" value="@productUid@">
                        <input type="hidden" name="oneclick_mod_send" value="1">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Заказать звонок</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<a href="#" data-toggle="modal" data-target="#oneClickModal" class="btn btn-success">Купить в 1 клик!</a>