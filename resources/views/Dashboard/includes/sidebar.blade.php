<div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="nav-item active"><a href=""><i class="la la-mouse-pointer"></i><span
                        class="menu-title" data-i18n="nav.add_on_drag_drop.main">الرئيسية </span></a>
            </li>






            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('branches-read')  )



            <li class=" nav-item"><a href="#"><i class="la la-bolt"></i><span class="menu-title"
                                                                              data-i18n="nav.flot_charts.main">لفروع</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('branch.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل الفروع</a>
                    </li>
                    @if(  auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('branches-create') )

                    <li><a class="menu-item" href="{{route('branch.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء فرع جديد</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('sports-read')  )

            <li class=" nav-item"><a href="#"><i class="icon-game-controller"></i><span class="menu-title"
                                                                              data-i18n="nav.flot_charts.main">الالعاب</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('sport.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل الالعاب</a>
                    </li>
                    @if(  auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('sports-create') )

                    <li><a class="menu-item" href="{{route('sport.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء لعبه جديد</a>
                    </li>
                    @endif


                </ul>
            </li>
            @endif
            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('levels-read')  )


            <li class=" nav-item"><a href="#"><i class="la la-level-up"></i><span class="menu-title"
                                                                              data-i18n="nav.flot_charts.main">المستويات</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('level.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل المستويات</a>
                    </li>
                    @if(  auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('levels-create') )

                    <li><a class="menu-item" href="{{route('level.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء مستوي جديد</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('price-list-read')  )


            <li class=" nav-item"><a href="#"><i class="la la-money"></i><span class="menu-title"
                                                                              data-i18n="nav.flot_charts.main">قائمه الاسعار</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('price-list.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل قوائم الاسعار</a>
                    </li>
                    @if(  auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('price-list-create') )

                    <li><a class="menu-item" href="{{route('price-list.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء قائمه سعر جديد</a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('package-read')  )

            <li class=" nav-item"><a href="#"><i class="la la-list"></i><span class="menu-title"
                                                                              data-i18n="nav.flot_charts.main"> الباكدج</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('package.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل الباكدج</a>
                    </li>
                    @if(  auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('package-create') )

                    <li><a class="menu-item" href="{{route('package.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء باكدج  جديد</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @if( auth()->user()->hasRole(['administrator','superadministrator'])  || auth()->user()->hasPermission('employee-read'))

            <li class=" nav-item"><a href="#"><i class="ft-user"></i><span class="menu-title"
                                                                              data-i18n="nav.flot_charts.main"> الموظفين </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('employee.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل الموظفين</a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator'])  || auth()->user()->hasPermission('employee-create'))


                    <li><a class="menu-item" href="{{route('employee.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء موظف  جديد</a>
                    </li>
                    @endif
                    <li><a class="menu-item" href="{{route('role.index')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            الادوار</a>
                    </li>

                </ul>
            </li>
            @endif
            @if( auth()->user()->hasRole(['administrator','superadministrator'])  || auth()->user()->hasPermission('trainer-read'))

            <li class=" nav-item"><a href="#"><i class="icon-users"></i><span class="menu-title"
                                                                              data-i18n="nav.flot_charts.main"> المدربين </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('trainer.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل المدربين</a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator'])  || auth()->user()->hasPermission('trainer-create'))

                    <li><a class="menu-item" href="{{route('trainer.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء مدرب  جديد</a>
                    </li>

                    @endif
                </ul>
            </li>
            @endif
            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('players-read')  )

            <li class=" nav-item"><a href="#"><i class="icon-user-following"></i><span class="menu-title"
                                                                              data-i18n="nav.flot_charts.main"> اللاعبين </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('player.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل اللاعبين</a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('players-create')  )

                    <li><a class="menu-item" href="{{route('player.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء لاعيب  جديد</a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('Incoming-receipts-read')  || auth()->user()->hasPermission('type-receipts-read'))

            <li class=" nav-item  @if(Route::is('receipt.*') || Route::is('receipt-type.*') ) open @endif"><a href="#"><i class="la la-file-zip-o"></i><span class="menu-title"
                                                                              data-i18n="nav.flot_charts.main"> ايصالات التوريد </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('receipt.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل الايصالات التوريد</a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('Incoming-receipts-create')  )

                    <li><a class="menu-item" href="{{route('receipt.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء ايصال توريد جديد</a>
                    </li>
                    @endif

                    <li><a class="menu-item" href="{{route('receipt-type.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل انواع  الايصالات التوريد</a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('type-receipts-create')  )

                    <li><a class="menu-item" href="{{route('receipt-type.create')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            انشاء  نوع ايصال توريد جديد</a>
                    </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('type-receipts-create')  )

                        <li>
                            <a class="menu-item" href="{{route('receipt.discount_waiting_approve')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                الخصومات المعتمده
                            </a>
                        </li>
                    @endif


                </ul>
            </li>
            @endif
                @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('Exchange-receipts-read') )

            <li class=" nav-item  @if(Route::is('receipt-pay.*') || Route::is('receipt-type-pay.*') ) open @endif"><a href="#"><i class="la la-file-zip-o"></i><span class="menu-title"
                                                                                    data-i18n="nav.flot_charts.main"> ايصالات الصرف </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('receipt-pay.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل الايصالات الصرف</a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('Exchange-receipts-create') )

                    <li><a class="menu-item" href="{{route('receipt-pay.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء ايصال صرف جديد</a>
                    </li>
                    @endif
                    <li><a class="menu-item" href="{{route('receipt-type-pay.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل انواع  الايصالات الصرف</a>
                    </li>
                    <li><a class="menu-item" href="{{route('receipt-type-pay.create')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            انشاء  نوع ايصال صرف جديد</a>
                    </li>

                </ul>
            </li>
                @endif
                @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('custody-read') )

            <li class=" nav-item"><a href="#"><i class="la la-file-zip-o"></i><span class="menu-title"
                                                                                    data-i18n="nav.flot_charts.main">  العهده </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('custody.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل مصروفات العهده </a>
                    </li>

                </ul>
            </li>
                @endif
                @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('settlements-read') )

            <li class=" nav-item"><a href="#"><i class="la la-file-zip-o"></i><span class="menu-title"
                                                                                    data-i18n="nav.flot_charts.main">  طلبات تسويه العهده  </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('settlement-request.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل  طلبات تسويه  العهده </a>
                    </li>

                </ul>
            </li>
                @endif
                @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('deductions-read') )


            <li class=" nav-item"><a href="#"><i class="ft-user-minus"></i><span class="menu-title"
                                                                                    data-i18n="nav.flot_charts.main"> الاستقطاعات  </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('cuts-employee.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل  الاستقطاعات الموظفين </a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('deductions-create') )

                    <li><a class="menu-item" href="{{route('cuts-employee.create')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            اضافه استقطاع</a>
                    </li>
                    @endif
                </ul>
            </li>
                @endif
                @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('contracts-read') )

            <li class=" nav-item"><a href="#"><i class="ft-align-justify"></i><span class="menu-title"
                                                                                    data-i18n="nav.flot_charts.main"> بنود العقد </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('item.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل البنود</a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('contracts-create') )

                    <li><a class="menu-item" href="{{route('item.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء بند  جديد</a>
                    </li>
                    @endif
                </ul>
            </li>
                @endif
                @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('contracts-read') )

            <li class=" nav-item"><a href="#"><i class="la la-commenting"></i><span class="menu-title"
                                                                                    data-i18n="nav.flot_charts.main">  العقود </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('contract.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل العقود</a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('contracts-create') )

                    <li><a class="menu-item" href="{{route('contract.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء عقد  جديد</a>
                    </li>
                    @endif

                </ul>
            </li>
                @endif
                @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('contracts-partners-read') )

            <li class=" nav-item"><a href="#"><i class="la la-commenting"></i><span class="menu-title"
                                                                                    data-i18n="nav.flot_charts.main">  عقود الشركاء </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('contract-partner.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                             كل  عقود الشركاء </a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('contracts-partners-create') )

                    <li><a class="menu-item" href="{{route('contract-partner.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء عقد  جديد</a>
                    </li>
                    @endif

                </ul>
            </li>
                @endif
            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('stadiums-rent-read') )

            <li class=" nav-item"><a href="#"><i class="la la-commenting"></i><span class="menu-title"
                                                                                    data-i18n="nav.flot_charts.main">   جدول الملاعب و التدريبات </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('trainer-and-player.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل جدول الملاعب و التدريبات</a>
                    </li>
                    <li><a class="menu-item" href="{{route('stadium-rent-table.index')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            ايجار الملاعب   </a>
                    </li>

                </ul>
            </li>
            @endif
            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('Attendance-players-read')  || auth()->user()->hasPermission('Attendance-trainers-read')  )

            <li class=" nav-item"><a href="#"><i class="la la-report"></i><span class="menu-title"
                                                                                    data-i18n="nav.flot_charts.main">  الحضور و الانصراف  </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('attendance-player.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            حضور و غياب اللاعبيين </a>
                    </li>
                    <li><a class="menu-item" href="{{route('attendance-trainer.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            حضور و غياب المدربين </a>
                    </li>
                    <li><a class="menu-item" href="{{route('attendance-employee.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            حضور و غياب الموظفين </a>
                    </li>

                </ul>
            </li>
            @endif
            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('stadiums-read') )

            <li class=" nav-item"><a href="#"><i class="la la-futbol-o"></i><span class="menu-title"
                                                                                    data-i18n="nav.flot_charts.main"> الملاعب </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('stadium.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل الملاعب</a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('stadiums-create') )

                    <li><a class="menu-item" href="{{route('stadium.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء ملعب  جديد</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif



{{--            <li class=" nav-item"><a href="#"><i class="la la-commenting"></i><span class="menu-title"--}}
{{--                                                                                    data-i18n="nav.flot_charts.main">  تعاقدات الشركاء </span></a>--}}
{{--                <ul class="menu-content">--}}
{{--                    <li><a class="menu-item" href="{{route('partner-contract.index')}}" data-i18n="nav.flot_charts.flot_line_charts">--}}
{{--                            كل تعاقدات الشركاء</a>--}}
{{--                    </li>--}}
{{--                    <li><a class="menu-item" href="{{route('partner-contract.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">--}}
{{--                            انشاء تعاقد  جديد</a>--}}
{{--                    </li>--}}

{{--                </ul>--}}
{{--            </li>--}}
            {{-- @if(auth()->user()->hasPermission('finance'))--}}

            <li class=" nav-item"><a href="#"><i class="la la-clone"></i><span class="menu-title"
                                                                               data-i18n="nav.flot_charts.main"> القوائم الماليه </span></a>
                <ul class="menu-content">
                    @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('income_list') )
                        <li><a class="menu-item" href="{{route('lists.income_list_month')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                قائمة الدخل عن شهر معين</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('subscription_income_reports') )
                            <li><a class="menu-item" href="{{route('lists.expenseAnalysis')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                    تقارير التحليل المالي</a>
                            </li>
                        @endif
                    @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('income_list') )
                        <li><a class="menu-item" href="{{route('lists.income_list')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                قائمة الدخل</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('recipt_report') )
                        <li><a class="menu-item" href="{{route('lists.recipt_report')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            تقارير الخزن</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('due_date_reports') )
                        <li><a class="menu-item" href="{{route('lists.due_date_reports')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                كشف حساب المستحق</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('rent_report') )
                        <li><a class="menu-item" href="{{route('lists.rent_report')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            تقارير ايرادات الملاعب</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('rent_detial_report') )
                        <li><a class="menu-item" href="{{route('lists.rent_detial_report')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                تقارير الايجار</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('subscription_income_reports') )
                        <li><a class="menu-item" href="{{route('lists.subscription_income_reports')}}" data-i18n="nav.flot_charts.flot_line_charts">
                               تقارير الاشتراكات</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('subscription_income_reports') )
                        <li><a class="menu-item" href="{{route('lists.custody_reports')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                 تقارير العهد</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('subscription_income_reports') )
                        <li><a class="menu-item" href="{{route('lists.income_reports_comparison')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                تقارير المقارنه</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('subscription_income_reports') )
                        <li><a class="menu-item" href="{{route('lists.tournament_reports')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                تقارير المسابقات</a>
                        </li>
                    @endif
                        @if( auth()->user()->hasRole(['administrator']) ||auth()->user()->hasPermission('subscription_income_reports') )
                            <li><a class="menu-item" href="{{route('lists.income_list_daily')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                    تقرير يومي</a>
                            </li>
                        @endif
                </ul>
            </li>
            {{-- @endif--}}
            <li class=" nav-item"><a href="#"><i class="la la-clone"></i><span class="menu-title"
                                                                               data-i18n="nav.flot_charts.main"> التقارير </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('report.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل التقارير</a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator']) || auth()->user()->hasPermission('income_list') )

                        <li><a class="menu-item" href="{{route('reports.subscription_reports')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                قائمة الدخل</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) || auth()->user()->hasPermission('subscription_reports') )

                        <li><a class="menu-item" href="{{route('reports.subscription_reports')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                تقارير الاشتراكات</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) || auth()->user()->hasPermission('schedules_reports') )

                        <li><a class="menu-item" href="{{route('reports.schedules_reports')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                تقارير الجداول</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) || auth()->user()->hasPermission('stadiums_reports') )

                        <li><a class="menu-item" href="{{route('reports.stadiums_reports')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                تقارير الملاعب</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) || auth()->user()->hasPermission('stadiums_reports') )

                        <li><a class="menu-item" href="{{route('reports.deleted_recipt')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                تقارير الفواتير الملغيه</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) || auth()->user()->hasPermission('attendance_reports') )

                        <li><a class="menu-item" href="{{route('reports.attendance_report')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                تقارير حضور الموظفين</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) || auth()->user()->hasPermission('trinar_attendance_report') )

                        <li><a class="menu-item" href="{{route('reports.trinar_attendance_report')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                تقارير حضور المدربين</a>
                        </li>
                    @endif
                    @if( auth()->user()->hasRole(['administrator']) || auth()->user()->hasPermission('player_attendance_report') )

                        <li><a class="menu-item" href="{{route('reports.player_attendance_report')}}" data-i18n="nav.flot_charts.flot_line_charts">
                                تقارير حضور اللاعبين</a>
                        </li>
                    @endif
                </ul>
            </li>
            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('tournament-read') )

            <li class=" nav-item"><a href="#"><i class="ft-layers"></i><span class="menu-title"
                                                                                    data-i18n="nav.flot_charts.main">   المسابقات </span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{route('tournament.index')}}" data-i18n="nav.flot_charts.flot_line_charts">
                            كل المسابقات </a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('tournament-create') )

                    <li><a class="menu-item" href="{{route('tournament.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء مسابقه  جديد</a>
                    </li>
                    @endif
                    <li><a class="menu-item" href="{{route('tournament-subscription.index')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            كل اشتراكات  المسابقات</a>
                    </li>
                    @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('tournament-create') )

                    <li><a class="menu-item" href="{{route('tournament-subscription.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                            انشاء اشتراك للمسابقه  </a>
                    </li>
                    @endif
                    <li><a class="menu-item" href="{{route('tournament-follow.create')}}" data-i18n="nav.flot_charts.flot_bar_charts">
                              متابعه المسابقه   </a>
                    </li>

                </ul>
            </li>
            @endif
        </ul>
    </div>
</div>

