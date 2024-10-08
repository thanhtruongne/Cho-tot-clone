
                {{-- @foreach ($leftMenuBackend as $key =>  $item)
                <li class="nav-item menu-open" data-id="{{$item['id']}}" data-key="{{$key}}">
                     <a href="{{ $item['url'] ?? '#' }}" class="nav-link active">
                        {!! $item['icon'] !!}
                        <p>
                            {{ $item['name'] }}
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    @if (isset($item['item_childs']) && !empty($item['item_childs']))
                       <ul class="nav nav-treeview">
                            @foreach ($item['item_childs'] as $child)
                                <li class="nav-item"> 
                                    {!! $child['icon'] !!}
                                    <a href="{{$child['url'] ?: ''}}" class="nav-link active"> 
                                        <p>{{ $child['name'] }}</p>
                                    </a>
                                </li>
                            @endforeach    
                        </ul>
                    @endif
                </li>
                @endforeach --}}
                <aside class="main-sidebar sidebar-dark-primary elevation-4">
                    <!-- Brand Logo -->
                    <a href="index3.html" class="brand-link">
                      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                      <span class="brand-text font-weight-light">AdminLTE 3</span>
                    </a>     
                    <!-- Sidebar -->
                    <div class="sidebar">    
                      <!-- Sidebar Menu -->
                      <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                          @php
                              $tab =  request()->segment(2);
                          @endphp
                          @foreach ($leftMenuBackend as $key =>  $item)
                              <li class="nav-item {{$item['url_name'] == $tab ? 'menu-open' : ''}}" data-id="{{$item['id']}}" data-key="{{$key}}">
                                  <a href="{{ $item['url'] ?? '#' }}" class="nav-link {{$item['url_name'] == $tab ? 'active' : ''}}">
                                      {!! $item['icon'] !!}
                                      <p>
                                          {{ $item['name'] }}
                                          @if (isset($item['item_childs']) && !empty($item['item_childs']))
                                            <i class="fas fa-angle-left right"></i>
                                          @endif
                                         
                                      </p>
                                  </a>
                                  @if (isset($item['item_childs']) && !empty($item['item_childs']))
                                    <ul class="nav nav-treeview">
                                        @foreach ($item['item_childs'] as $child)
                                            <li class="nav-item pl-3"> 
                                              <a href="{{$child['url'] ?: ''}}" class="nav-link {{$child['url_name'] == $tab ? 'active' : ''}}"> 
                                                  {!! $child['icon'] !!}
                                                    <p>{{ $child['name'] }}</p>
                                                    @if (isset($child['item_childs']) && !empty($child['item_childs']))
                                                      <i class="fas fa-angle-left right"></i>
                                                    @endif
                                              </a>
                                              @if (isset($child['item_childs']) && !empty($child['item_childs']))
                                                  <ul class="nav nav-treeview">
                                                    @php
                                                         $tab3 =  request()->segment(3);
                                                         $tab4 =  request()->segment(4) == 'categories' ?  request()->segment(3).'/'.'categories' : '';
                                                    @endphp
                                                    @foreach ($child['item_childs'] as $tree)
                                                        <li class="nav-item pl-3"> 
                                                          <a href="{{$tree['url'] ?: ''}}" class="nav-link {{$tree['url_name'] == $tab3 ||$tree['url_name'] == $tab4  ? 'active' : ''}}"> 
                                                              {!! $tree['icon'] !!}
                                                                <p>{{ $tree['name'] }}</p>
                                                          </a> 
                                                          @if (isset($tree['item_childs']) && !empty($tree['item_childs']))
                                                              <ul class="nav nav-treeview">
                                                                @php
                                                                    $tab3 =  request()->segment(3);
                                                                    $tab4 =  request()->segment(4) == 'categories' ?  request()->segment(3).'/'.'categories' : '';
                                                                @endphp
                                                                @foreach ($tree['item_childs'] as $treeChild)
                                                                    <li class="nav-item pl-3"> 
                                                                      <a href="{{$treeChild['url'] ?: ''}}" class="nav-link {{$treeChild['url_name'] == $tab3 ||$treeChild['url_name'] == $tab4  ? 'active' : ''}}"> 
                                                                          {!! $treeChild['icon'] !!}
                                                                            <p>{{ $treeChild['name'] }}</p>
                                                                      </a> 
                                                                    </li>
                                                                @endforeach    
                                                            </ul>
                                                          @endif
                                                        </li>
                                                    @endforeach    
                                                </ul>
                                              @endif
                                            </li>
                                        @endforeach    
                                    </ul>
                                  @endif
                              </li>
                            @endforeach
                          {{-- <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
                              <i class="nav-icon fas fa-tachometer-alt"></i>
                              <p>
                                Dashboard
                                <i class="right fas fa-angle-left"></i>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                              <li class="nav-item">
                                <a href="./index.html" class="nav-link active">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Dashboard v1</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="./index2.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Dashboard v2</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="./index3.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Dashboard v3</p>
                                </a>
                              </li>
                            </ul>
                          </li>
                          <li class="nav-item">
                            <a href="pages/widgets.html" class="nav-link">
                              <i class="nav-icon fas fa-th"></i>
                              <p>
                                Widgets
                                <span class="right badge badge-danger">New</span>
                              </p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link">
                              <i class="nav-icon fas fa-copy"></i>
                              <p>
                                Layout Options
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">6</span>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                              <li class="nav-item">
                                <a href="pages/layout/top-nav.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Top Navigation</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Top Navigation + Sidebar</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/layout/boxed.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Boxed</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/layout/fixed-sidebar.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Fixed Sidebar</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/layout/fixed-sidebar-custom.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Fixed Sidebar <small>+ Custom Area</small></p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/layout/fixed-topnav.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Fixed Navbar</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/layout/fixed-footer.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Fixed Footer</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/layout/collapsed-sidebar.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Collapsed Sidebar</p>
                                </a>
                              </li>
                            </ul>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link">
                              <i class="nav-icon fas fa-chart-pie"></i>
                              <p>
                                Charts
                                <i class="right fas fa-angle-left"></i>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                              <li class="nav-item">
                                <a href="pages/charts/chartjs.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>ChartJS</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/charts/flot.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Flot</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/charts/inline.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Inline</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/charts/uplot.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>uPlot</p>
                                </a>
                              </li>
                            </ul>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link">
                              <i class="nav-icon fas fa-tree"></i>
                              <p>
                                UI Elements
                                <i class="fas fa-angle-left right"></i>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                              <li class="nav-item">
                                <a href="pages/UI/general.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>General</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/UI/icons.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Icons</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/UI/buttons.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Buttons</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/UI/sliders.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Sliders</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/UI/modals.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Modals & Alerts</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/UI/navbar.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Navbar & Tabs</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/UI/timeline.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Timeline</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/UI/ribbons.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Ribbons</p>
                                </a>
                              </li>
                            </ul>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link">
                              <i class="nav-icon fas fa-edit"></i>
                              <p>
                                Forms
                                <i class="fas fa-angle-left right"></i>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                              <li class="nav-item">
                                <a href="pages/forms/general.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>General Elements</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/forms/advanced.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Advanced Elements</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/forms/editors.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Editors</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/forms/validation.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Validation</p>
                                </a>
                              </li>
                            </ul>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link">
                              <i class="nav-icon fas fa-table"></i>
                              <p>
                                Tables
                                <i class="fas fa-angle-left right"></i>
                              </p>
                            </a>
                            <ul class="nav nav-treeview">
                              <li class="nav-item">
                                <a href="pages/tables/simple.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Simple Tables</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/tables/data.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>DataTables</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="pages/tables/jsgrid.html" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>jsGrid</p>
                                </a>
                              </li>
                            </ul>
                          </li> --}}
                         
                        </ul>
                      </nav>
                      <!-- /.sidebar-menu -->
                    </div>
                    <!-- /.sidebar -->
                  </aside>               


                