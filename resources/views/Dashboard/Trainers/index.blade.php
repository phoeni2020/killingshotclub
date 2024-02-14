@extends('Dashboard.includes.admin')
@section('content')

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">قسم المدربيين</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">لوحة التحكم</a></li>
                                <li class="breadcrumb-item active">كل المدربيين</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if(Session::has('message'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{session()->get('message')}}</strong>
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-di smiss="alert">×</button>
                    <strong>{{session()->get('error')}}</strong>
                </div>
            @endif

            <div class="content-body">
                <!-- Recent Transactions -->
                <div class="row">
                    <div id="recent-transactions" class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">المدربيين ({{$users->total()}})</h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        @if( auth()->user()->hasRole(['administrator','superadministrator'])  || auth()->user()->hasPermission('trainer-create'))

                                        <li>
                                                <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('trainer.create')}}"> <i class="ft-plus ft-md"></i> اضافة مدرب جديد</a>
                                            </li>
                                    @endif
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">#</th>
                                            <th class="border-top-0">اسم المدرب</th>
                                            <th class="border-top-0">البريد الإلكتروني </th>
                                            <th class="border-top-0"> الهاتف </th>
                                            <th class="border-top-0"> الهاتف الاخر </th>
                                            <th class="border-top-0"> العنوان  </th>
                                            <th class="border-top-0">تاريخ الميلاد </th>
                                            <th class="border-top-0">الرقم القومي  </th>
                                            <th class="border-top-0"> شهاده التخرج </th>
                                            <th class="border-top-0"> الحاله لعسكريه </th>
                                            <th class="border-top-0"> الصوره </th>
{{--                                            <th class="border-top-0">قسم الوظف</th>--}}


                                            <th class="border-top-0">التحكم</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($users as $user )

                                            <tr class="row1" data-id="{{ $user->id }}" >
                                                <td class="text-truncate"> {{$loop->iteration}} </td>
                                                <td class="text-truncate"> {{$user->name}} </td>
                                                <td class="text-truncate">{{$user->email}}</td>
                                                <td class="text-truncate">{{$user->phone ?? '------'}}</td>
                                                <td class="text-truncate">{{$user->phone2 ?? '------'}}</td>
                                                <td class="text-truncate">{{$user->address ?? '------'}}</td>
                                                <td class="text-truncate">{{$user->birth_day}}</td>
                                                <td class="text-truncate">{{$user->national_id}}</td>
                                                <td class="text-truncate">{{$user->degree}}</td>
                                                <td class="text-truncate">{{$user->military_status}}</td>


                                                <div class="modal fade" id="smallmodal-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="smallmodalLabel"> صوره الموظف</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body row justify-content-center">
                                                                @if($user->image)

                                                                    <img class="img-fluid" width="100" height="100" src="{{asset($user->image) ?? "----"}}" alt="">
                                                                @else
                                                                    <h5> لايوجد صوره لهذا لمدرب</h5>
                                                                @endif


                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">غلق</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>



                                                <td>
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-warning  "  data-toggle="modal" data-target="#smallmodal-{{$user->id}}">

                                                        <i class="la la-eye"></i>
                                                    </button>


                                                <td class="text-truncate">
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        @if( auth()->user()->hasRole(['administrator','superadministrator'])  || auth()->user()->hasPermission('trainer-update'))

                                                        <a href="{{route('trainer.edit', $user->id)}}" class="btn btn-info btn-sm round"> تعديل</a>
                                                        @endif
                                                            @if( auth()->user()->hasRole(['administrator','superadministrator'])  || auth()->user()->hasPermission('trainer-delete'))


                                                            <form action="{{route('trainer.destroy' ,$user->id)}}" method="POST" class="btn-group">
                                                                @csrf @method('delete')
                                                                <button

                                                                    class="btn btn-danger btn-sm round"
                                                                    onclick="return confirm('حذف هذه المدرب سيقوم بحذف جميع الالعاب و المتدربين المتعلقين به !! هل انت متاكد من الحذف ؟')"
                                                                >
                                                                    حذف
                                                                </button>
                                                            </form>
                                                            @endif
                                                    </div>
                                                </td>
                                            </tr>

                                        @empty
                                        <tr>
                                            <td colspan="8">
                                                لايوجد مدربيين  حاليا

                                            </td>
                                        </tr>
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($users->hasPages())
                    {{$users->appends(request()->input())->links('pagination::bootstrap-4')}}
                @endif
                <!--/ Recent Transactions -->
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        let table = new DataTable('#myTable');
    </script>
@endsection
