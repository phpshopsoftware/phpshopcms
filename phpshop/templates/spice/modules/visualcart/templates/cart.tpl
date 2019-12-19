<ul id="visualcart_content" class="dropdown-menu pull-right">
    <li>
        <table class="table hcart" id="visualcart">
            @php if(!empty($_SESSION['cart'])) echo $GLOBALS['SysValue']['other']['visualcart_list'];  php@
        </table>
    </li>
    <li>
        <p class="text-center btn-block1">
            <a href="/order/">
                ќформить заказ
            </a>
        </p>
    </li>
</ul>