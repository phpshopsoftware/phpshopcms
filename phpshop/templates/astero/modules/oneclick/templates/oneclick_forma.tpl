<form role="form" method="post" name="user_forma" action="@ShopDir@/oneclick/">
    <div class="form-group">
        <label>���</label>
        <input type="text" name="oneclick_mod_name" class="form-control" placeholder="���..." required="">
    </div>
    <div class="form-group">
        <label>�������</label>
        <input type="text" name="oneclick_mod_tel" class="form-control" placeholder="�������..." required="">
    </div>
    <div class="text-center">
        <input type="hidden" name="oneclick_mod_product_id" value="@productUid@">
        <input type="hidden" name="oneclick_mod_send" value="1">
        <button type="submit" class="btn btn-primary">�������� ������</button>
    </div>
</form>