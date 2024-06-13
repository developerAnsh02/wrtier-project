<aside class="left-sidebar">
            <div class="scroll-sidebar">
                <div class="user-profile">
                    <div class="user-pro-body">
                        <div>
                            <img src="https://assignnmentinneed.com/{{Auth::user()->photo}}" alt="user-img" class="img-circle">
                        </div>
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}}<span class="caret"></span></a>
                            <div class="dropdown-menu animated flipInY">
                                <a href="/profile" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a>
                                <div class="dropdown-divider"></div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                  @csrf
                                </form>
                                <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item"><i class="fas fa-power-off"></i> Logout</a>                            </div>
                        </div>
                    </div>
                </div>
                @foreach($premission as $permission)
    				@if(auth()->user()->role_id == $permission->role_id)
						@php
							$menuIds = json_decode($permission->menu_id);
							$submenuIds = json_decode($permission->submenu_id);
							if ($submenuIds === null) {
								$submenuIds = [];
							}
							if ($menuIds === null) {
								$menuIds = [];
							}
						@endphp
					@endif
				@endforeach

                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                   
                        <li class="nav-small-cap">--- MAIN MENU</li>
                            @foreach ($menus as $menu)
                                @if ($menu->show_menu == 'Y'  && in_array($menu->id, $menuIds))
                                    @if (count($menu->submenus) > 0)
                                        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="{{ $menu['icon_class'] }}"></i><span class="hide-menu">{{ $menu['menu_name'] }}</span></a>
                                            <ul aria-expanded="false" class="collapse">
                                                @foreach ($menu->submenus as $submenu)
                                                    @if(in_array($submenu->id, $submenuIds))
                                                    <li><a href="{{$submenu->routes}}">{{ $submenu->sub_menu_name }}</a></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                    <li>
                                        <a class="waves-effect waves-dark" href="{{$menu['routes']}}" aria-expanded="false">
                                            <i class="{{ $menu['icon_class'] }}"></i>
                                            <span class="hide-menu">{{ $menu['menu_name'] }}</span>
                                        </a>
                                    </li>
                                    @endif

                                @endif
                            @endforeach
						
						
                        <li class="nav-small-cap">--- MAIN MENU</li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>