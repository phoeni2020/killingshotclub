@extends('Dashboard.includes.admin')

@section('content')


    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">قسم الايصالات التوريد</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="">لوحة التحكم</a></li>
                                <li class="breadcrumb-item active">   اضافة ايصال توريد</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @include('Dashboard.includes.alerts.errors')

                <div class="row justify-content-md-center">
                    <div class="col-lg-10">
                        <div class="card" style="zoom: 1;">
                            <div class="card-header">
                                <h4 class="card-title" id="bordered-layout-card-center">اضافة ايصال توريد جديد</h4>
                                <a href="/sat/courses/create.php" class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            </div>
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <form class="form" id="myForm" action="{{route('receipt.store')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-body">
                                            <div class="row">
                                                <fieldset class="row col-12" style="border: 1px solid;margin-right: 0px;">
                                                    <legend>Basic info:</legend>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput2">  اسم محرر الايصال </label>
                                                            <input type="text" class="form-control" disabled name="name" value="{{ auth()->user()->name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="projectinput2">  تاريخ الايصال</label>
                                                            <input type="date" name="date"
                                                                   class="form-control"
                                                                   {{--                                                               @if(auth()->user()->hasPermission('date-receipts-create') || auth()->user()->hasRole(['administrator','superadministrator']))   @else disabled  @endif--}}
                                                                   placeholder="dd-mm-yyyy" value = "{{ Carbon\Carbon::today()->format('Y-m-d') }}"
                                                                   min="{{ Carbon\Carbon::today()->format('Y')}}-01-01" max="2030-12-31">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="projectinput2">  رقم الايصال الدفتري </label>
                                                            <input type="text" class="form-control"  name="recipt_no" value="" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="projectinput2">  تاريخ الاستحقاق</label>
                                                            <input type="date" name="due_date"
                                                                   class="form-control"
                                                                   {{--                                                               @if(auth()->user()->hasPermission('date-receipts-create') || auth()->user()->hasRole(['administrator','superadministrator']))   @else disabled  @endif--}}
                                                                   placeholder="dd-mm-yyyy" value = "{{ Carbon\Carbon::today()->format('Y-m-d') }}"
                                                                   min="{{ Carbon\Carbon::today()->format('Y')}}-01-01" max="2030-12-31">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-0">
                                                        <div class="form-group">
                                                            <label for="projectinput2">   جزئي </label>
                                                            <input type="checkbox" class="form-control"  id="type_of_amount" name="type_of_amount"  value="part" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2" >
                                                        <h6>نوع الوصل :</h6>
                                                        <div class="form-group">
                                                            <label>من الاعبين</label>
                                                            <input class="from_type " type="radio"  checked  id="players" name="from_type" value="players">
                                                            <label>اخري </label>
                                                            <input class=" from_type" type="radio" id="others" name="from_type" value="others">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput2">  الفرع</label>
                                                        <select class="select2-placeholder-multiple form-control" id="branch_id"  name="branch_id" >
                                                            <option value="" selected >اختر فرع </option>

                                                            @foreach($branches as $branch)
                                                                <option value="{{$branch->id}}">{{$branch->name}}</option>

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4"  style="display: none" id="from_players">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">  من  </label>
                                                                <br>
                                                                <select class="select2 form-control"
                                                                        name="from" id="player_id"
                                                                        style="min-width: 200px"
                                                                >
                                                                    <option value="" selected>اختر لاعب
                                                                    </option>
                                                                    @foreach($players as $player)
                                                                        <option  data-price="{{ $player->PlayerSportPrice }}"  value="{{$player->id}}">{{$player->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">  المدرب  </label>
                                                                <br>
                                                                <select class="select2 form-control"
                                                                        name="traina_id" id="traina_id"
                                                                >
                                                                    <option value="" selected>اختر مدرب
                                                                    </option>
                                                                    @foreach($trainers as $trainer)
                                                                        <option value="{{$trainer->id}}">{{$trainer->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4" style="display: none" id="from_others">
                                                    <div class="form-group">
                                                        <label for="projectinput2">  من  </label>
                                                        <select class="form-control" id="others_recipt" name="from_others">
                                                            <option value="" selected>جهات اخري</option>
                                                            @foreach($receiptTypes as $type)
                                                                <option value="{{$type->id}}" data-type="{{$type->type}}">{{$type->name}}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput2">  الي   </label>
                                                        <select class="select2-placeholder-multiple form-control"  name="to" >
                                                            @foreach($receiptTypes as $type)
                                                                <option value="{{$type->id}}">{{$type->name}}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="rentList" style="display: none">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput2">  اختار الايجار </label>
                                                        <select class=" form-control rentList"   name="rentList" >
                                                            <option value="" selected>اختر  الايجار</option>
                                                            @foreach($rents as $rent)
                                                                <option value="{{$rent->id}}" >{{$rent->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="typePrice" id="type_price">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="projectinput2">  تصنيف الادخال</label>
                                                            <select class=" form-control"  id="price_list" name="price_list" >
                                                                <option value="" selected>اختر  تصنيف الادخال
                                                                </option>
                                                            </select>
                                                        <input type="hidden" name="typePrice" id="type_price">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="payer">  اسم القائم بالدفع </label>
                                                        <input type="text" name="payer" id="payer" value="{{old('payer')}}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" >
                                                <div class="col-md-6" >
                                                    <div class="form-group">
                                                        <label for="projectinput2">الخصم</label>
                                                        <select class=" form-control"  id="discount" name="discount" >
                                                            <option value="none" selected>بلا خصم</option>
                                                            <option value="amount" >مبلغ مباشر</option>
                                                            <option value="percentage" >نسبة من المبلغ</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" >
                                                    <div class="form-group">
                                                        <label for="projectinput2">الخصم</label>
                                                        <input type="number" min="0"  name="discount_rate" id="discount_rate" value="{{old('discount_rate')}}" class="form-control">
                                                        <input type="hidden" name="discount_amount_value" id="discount_amount_value">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">  المبلغ </label>
                                                        <input type="number" name="amount" id="amount" class="form-control">

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">  المدفوع </label>
                                                        <input type="number"  readonly name="paid" id="paid" class="form-control part">
                                                    </div>
                                                </div>
                                                <div class="ol-md-3">
                                                <div class="form-group">
                                                        <label for="">  المتبقي </label>
                                                        <input type="number" name="remain" id="remain" readonly class="form-control part">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-end">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="checkbox" name="payment_type" id="payment_type" class="checkbox">
                                                        <label for="payer">  الدفع بالفيزا </label>

                                                    </div>
                                                </div>
                                                <div class="col-md-5"  id="serial" style="display: none">
                                                    <div class="form-group">
                                                        <label for="payer">  رقم الايصال </label>
                                                        <input type="text" name="serial" id="serial" value="{{old('serial')}}" class="form-control">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for=""> البيان</label>
                                                        <textarea class="form-control" rows="6" name="statement"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions center">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <button type="submit" class="btn btn-primary w-100"><i class="la la-check-square-o"></i> حفظ</button>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button"  class="btn btn-danger   w-100" onclick="resetForm();">مسح  </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Recent Transactions -->
            </div>
        </div>
    </div>
@endsection
@section('script')

    <script>
        $(document).ready(function(){
            checkfromType();
            typeOfAmount();
            calcPaidAndRemain();

            $('.from_type').change(function (){
                checkfromType();
            });
            $('#type_of_amount').click(function (){
                typeOfAmount();
            });

            $("#paid").change(function(){
                calcPaidAndRemain();
            });
            $("#amount").change(function(){
                var amount = $(this).val()*1;
                if($('#discount').val() == 'amount')
                {
                    if(amount < $('#discount_rate').val())
                    {
                        if($('.error-hodler').length > 0)
                            $('.error-holder').remove();

                        $('#discount_rate').parent().after().append('<div class="error-holder"><br><br>' +
                            '<lable class="alert alert-danger">الخصم اكبر من المبلغ المدفوع</lable></div>')
                        return false;
                    }
                    var paid = parseInt(amount) - parseInt($('#discount_rate').val());
                    $('#discount_amount_value').val(parseInt(amount))
                    $('#paid').val(paid)
                }
                else if($('#discount').val() == 'percentage')
                {
                    if(100 < $('#discount_rate').val())
                    {
                        if($('.error-hodler').length > 0)
                            $('.error-holder').remove();

                        $('#discount_rate').parent().after().append('<div class="error-holder"><br><br>' +
                            '<lable class="alert alert-danger">نسبة الخصم اكبر من 100%</lable></div>')
                        return false;
                    }
                    var paid = amount - ($('#discount_rate').val()/100 * amount);
                    $('#discount_amount_value').val(parseInt(amount))
                    $('#paid').val(paid)
                }
            });

        });
        $('#branch_id').change(function () {
            var branch_id =$(this).val();

            var route = "{{route('get-player')}}";
            $.ajax(route,{
                type: 'GET',  // http method
                data:{"branch_id":branch_id},
                success: function(data){
                    $('#player_id').html(data.playersDatalist)
                }
            });
        })
        $('#player_id').change(function (){

            getPlayersData();

        });
        $("#price_list").change(function(){

           get_price();
       });

        /*+
           function of js to keep code and didn't duplicate
         */

        function resetForm() {
            document.getElementById("myForm").reset();
            typeOfAmount();
        }

        function checkfromType(){
            if($('input[name="from_type"]:checked').val() =='players'){
                $('#from_players').show().attr('name','from');
                $('#from_others').hide().removeAttr('name');
                $("#price_list").removeAttr('disabled');

            }
            if($('input[name="from_type"]:checked').val() =='others'){
                $('#from_others').show().attr('name','from');
                $("#price_list").attr('disabled','disabled');

                $('#from_players').hide().removeAttr('name');
            }
        }
        function typeOfAmount(){
            if($('input[name="type_of_amount"]:checked').val())
                $('#paid').removeAttr("disabled")
            else
            {

                var amount = $("#amount").val()*1;
                if($('#discount').val() == 'amount')
                {
                    if(amount < $('#discount_rate').val())
                    {
                        if($('.error-hodler').length > 0)
                            $('.error-holder').remove();

                        $('#discount_rate').parent().after().append('<div class="error-holder"><br><br>' +
                            '<lable class="alert alert-danger">الخصم اكبر من المبلغ المدفوع</lable></div>')
                        return false;
                    }
                    var paid = parseInt(amount) - parseInt($('#discount_rate').val());
                    $('#paid').val(paid)
                }
                else if($('#discount').val() == 'percentage'){
                    if(100 < $('#discount_rate').val()){
                        if($('.error-hodler').length > 0){
                            $('.error-holder').remove();
                        }
                        $('#discount_rate').parent().after().append('<div class="error-holder"><br><br>' +
                            '<lable class="alert alert-danger">نسبة الخصم اكبر من 100%</lable></div>')
                        return false;
                    }
                }

                $('#remain').val('');
            }
        }
        function calcPaidAndRemain(){
            var amount = $("#amount").val()*1;
            var paid = $("#paid").val()*1;
            if(paid){
                var remain = amount - paid;
                $('#remain').val(remain);
            }

        }
        function get_price(){
          var player_id =$("#player_id").val();
          var id =$("#price_list").val();
          var route = "{{route('get-players-sports-price')}}";
           var typePrice =  $("#price_list").find(':selected').attr('data-typeprice');
           $("#type_price").val(typePrice);
          $.ajax(route,{
              type: 'GET',  // http method
             data:{"player_id":player_id, "id":id,"typePrice":typePrice},
              success: function(data){
                  var amount = data.price;
                  $('#amount').val(data.price)
                  if($('#discount').val() == 'amount')
                  {
                      if(amount < $('#discount_rate').val())
                      {
                          if($('.error-hodler').length > 0)
                              $('.error-holder').remove();

                          $('#discount_rate').parent().after().append('<div class="error-holder"><br><br>' +
                              '<lable class="alert alert-danger">الخصم اكبر من المبلغ المدفوع</lable></div>')
                          return false;
                      }
                      var paid = parseInt(amount) - parseInt($('#discount_rate').val());
                      $('#discount_amount_value').val(parseInt(amount))
                      $('#paid').val(paid)
                  }
                  else if($('#discount').val() == 'percentage')
                  {
                      if(100 < $('#discount_rate').val())
                      {
                          if($('.error-hodler').length > 0)
                              $('.error-holder').remove();

                          $('#discount_rate').parent().after().append('<div class="error-holder"><br><br>' +
                              '<lable class="alert alert-danger">نسبة الخصم اكبر من 100%</lable></div>')
                          return false;
                      }
                      var paid = amount - ($('#discount_rate').val()/100 * amount);
                      $('#discount_amount_value').val(parseInt(amount))
                      $('#paid').val(paid)
                  }
              }
          });
        }
        function  getPlayersData(){
            var player_id =$("#player_id").val();
            var route = "{{route('get-players-data')}}";
            $.ajax(route,{
                type: 'GET',  // http method
                data:{"player_id":player_id},
                success: function(data){
                    $('#price_list').html(data.optionPriceList)
                    $('#package_id').html(data.optionPackage)
                }
            });
        }
        $('#payment_type').change(function() {
            if ($(this).is(':checked')) {
                $('#serial').show();
            } else {
                $('#serial`').hide();
            }
        });

        $('#discount_rate').change(function () {
            var amount = $('#amount').val();

            if($('#discount').val() == 'amount'){
                if(amount < $('#discount_rate').val()){
                    if($('.error-hodler').length > 0){
                        $('.error-holder').remove();
                    }
                    $('#discount_rate').parent().after().append('<div class="error-holder"><br><br>' +
                        '<lable class="alert alert-danger">الخصم اكبر من المبلغ المدفوع</lable></div>')
                    return false;
                }
                paid = parseInt(amount) - parseInt($('#discount_rate').val());
                $('#discount_amount_value').val(parseInt(amount))
                $('#paid').val(paid)
            }
            else if($('#discount').val() == 'percentage'){
                if(100 < $('#discount_rate').val()){
                    if($('.error-hodler').length > 0){
                        $('.error-holder').remove();
                    }
                    $('#discount_rate').parent().after().append('<div class="error-holder"><br><br>' +
                        '<lable class="alert alert-danger">نسبة الخصم اكبر من 100%</lable></div>')
                    return false;
                }
                var paid = amount - ($('#discount_rate').val()/100 * amount);
                $('#discount_amount_value').val(parseInt(amount))
                $('#paid').val(paid)
            }
        });
        $('#others_recipt').change(function (e) {
            var select_val = $(e.currentTarget).val();
            var ids =  [36,41,42,45,48]
            if(ids.includes(parseInt(select_val))){
                console.log('sssssssssssssssss')
                $('#rentList').css('display','block')
            }else{
                console.log('sssssssssssssssss')
                console.log(ids.includes(parseInt(select_val)))
                console.log(select_val)

                $('#rentList').css('display','none')

            }
            //36,42,45,48,45
        });
    </script>
@endsection
