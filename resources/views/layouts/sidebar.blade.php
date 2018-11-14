<nav id="sidebar">
    <!-- <div id="dismiss">
        <i class="fas fa-arrow-left"></i>
    </div> -->

    <div class="sidebar-header">
        <h3>Preetom</h3>
    </div>

    <ul class="list-unstyled components">
        <li class="active">
            <!-- <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">Order History</a> -->
            <!-- <ul class="collapse list-unstyled" id="homeSubmenu">
                <li>
                    <a href="#">Home 1</a>
                </li>
                <li>
                    <a href="#">Home 2</a>
                </li>
                <li>
                    <a href="#">Home 3</a>
                </li>
            </ul> -->
        <!-- </li> -->
        <li>
            <a href="{{ route('home') }}"><i class="fas fa-tachometer-alt"></i> Order Dashborad</a>
            <a href="{{ route('orderHistory.index') }}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Order History</a>
            <a href="{{ route('catalogView') }}"><i class="fas fa-cart-plus" aria-hidden="true"></i> Inventory Management</a>
            <a href="{{ route('InvRemarks') }}"><i class="fas fa-comment" aria-hidden="true"></i> Quantity Handling</a>
            <!-- <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false">Pages</a>
            <ul class="collapse list-unstyled" id="pageSubmenu">
                <li>
                    <a href="#">Page 1</a>
                </li>
                <li>
                    <a href="#">Page 2</a>
                </li>
                <li>
                    <a href="#">Page 3</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">Portfolio</a> -->
        </li> 
        <li>
            <a class="dropdown-item" style="position: absolute; bottom: 5rem;" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i> {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</nav>