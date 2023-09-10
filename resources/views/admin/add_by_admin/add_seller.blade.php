@extends("layout.Cover")
@section("content")

    @if ($errors->any())
        <div class="card">
            <div class="card-body" style="direction: rtl">

                <div class="alert alert-danger" style="margin-top: 15px">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    <div class="col-lg-12" >

        <div  style="direction: rtl">
            <form class="card-body" action="{{ route('store_seller_by_admin') }}" method="POST"  enctype="multipart/form-data">
                @csrf
                <h5 class="card-title">اضافة محل تجاري (ملابس - مطاعم -صيدلية)</h5>

                <!-- General Form Elements -->




                <div class="row mb-3">
                    <label for="inputText" class="col-sm-2 col-form-label">عنوان المشروع</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="title">
                    </div>
                </div>


                <div class="row mb-3">
                    <label for="inputPassword" class="col-sm-2 col-form-label">وصف المشروع </label>
                    <div class="col-sm-10">
                        <textarea class="form-control" style="height: 100px" name="description"></textarea>
                    </div>
                </div>


                <div class="row mb-3">
                    <label for="inputText" class="col-sm-2 col-form-label"  >المنطقة</label>
                    <div class="col-sm-4">

                        <select class="form-select" onchange="get_the_area()" id="regions_element" name="region_id">
                            <option selected>الرجاء الاختيار </option>
                            @if(isset($regions))
                                @foreach($regions as $region)
                            <option value="{{$region->id}}"> {{$region->region}}</option>
                                @endforeach
                                @else
                                <option> لا يوجد خيارات</option>
                                @endif
                        </select>
                    </div>

                    <label for="inputText" class="col-sm-1 col-form-label" >قسم المنطقة</label>
                    <div class="col-sm-4">
                        <select class="form-select" id="areas_element" name="area_id">
                                <option> No Options</option>
                        </select>
                    </div>

                </div>
                <div class="col-sm-12">

                    <div class="row">
                        <div class="col-2">الصنف</div>
                        <div class="col-10"> <select   class="form-select" name="category_id" style="direction: rtl">
                                <option selected>اختر نوع المصلحة التجارية</option>
                                @foreach($category as $item)
                                    <option value="{{$item->id}}">{{$item->title}}</option>

                                @endforeach
                            </select></div>
                    </div>

                </div>
                <hr/>




                <div class="row">
                    <div class="col-2" style="font-weight: bold;font-size: 14px"> اسم المالك للمشروع</div>
                    <div class="col-10">
                      <input type="text" class="form-control" placeholder=" اسم المالك للمشروع" name="name">
                    </div>
                </div>
                <br> <br>

                <div class="row">
                    <div class="col-2" style="font-weight: bold;font-size: 14px" >  ( for SMS) رقم الهاتف</div>
                    <br><br> <br>
                    <div class="col-10">
                        <input type="text"  placeholder="0594419959" class="form-control" name="place_phone">

                    </div>
                </div>

                <div class="row mb-12" >
                    <label for="inputText" class="col-sm-2 col-form-label" >ايميل الدخول </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Email" name="email">
                    </div>
                    <br><br><br>



                </div>
                <hr>

                <div class="row mb-3">
                    <label for="inputText" class="col-sm-2 col-form-label"  >بداية العمل</label>
                    <div class="col-sm-4">
                        <input type="number" id="from_work" value="6" onchange="store_seller_by_admin()" class="form-control" placeholder="صباحا" name="from">
                    </div>

                    <label for="inputText" class="col-sm-1 col-form-label" >نهاية العمل</label>
                    <div class="col-sm-4">
                        <input type="number" id="to_work" value="6" onchange="store_seller_by_admin()"  placeholder="مساء" class="form-control" name="to">
                    </div>

                </div>







                <center><u><h4 class="text-danger">ايام العمل</h4></u></center>
                <br>
                <div class="row" style="margin-left: 20px;direction: ltr">
                    @foreach($days as $day)
                        <div class="form-check col-xl">
                            <input class="form-check-input" type="checkbox" value="{{$day->id}}"  id="{{$day->id}}" name="days[]" >
                            <label class="form-check-label" for="{{$day->id}}">
                                {{$day->day}}
                            </label>
                        </div>
                    @endforeach
                </div>



                <hr/>
                <hr>



                <div class="row mb-3">
                    <label for="inputPassword" class="col-sm-2 col-form-label">صور للمحل التجاري</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="file" name="file"/>
                    </div>
                </div>

                <div class="row mb-3">

                    <div class="col-sm-10">
                        <button  class="btn btn-danger" type="submit">ارسال الطلب</button>
                    </div>
                </div>




            </form></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <script>
        function store_seller_by_admin() {

           let from= document.getElementById('from_work');
            let to=document.getElementById('to_work');
            if (Number(from.value)>12){
                window.alert("الرجاء اختيار قيمة اقل من 12");
                from.value="6";
            }
            if (Number(from.value)<0){
                window.alert("الرجاء اختيار قيمة موجبة");
                from.value="6";
            }

            if (Number(to.value)>12){
                window.alert("الرجاء اختيار قيمة موجبة");
                to.value="6";
            }

            if (Number(from.value)<0){
                window.alert("الرجاء اختيار قيمة موجبة");
                to.value="6";
            }



        }
    function get_the_area() {
        $.ajax({
            type: 'post',
            url: '{{route('get_areas_by_region')}}',
            data: {
                '_token':"{{csrf_token()}}",
                'regions_id': document.getElementById("regions_element").value,
                {{--'txt':document.getElementById("tit").value,--}}

            },
            success: function (data) {
                if(data.status==true){

                    document.getElementById("areas_element").innerHTML=null;

                    let option=document.createElement('option');

                    option.innerText="الرجاء اختيار المنطقة";


                    document.getElementById("areas_element").appendChild(option);


                    for (let x= 0; x<data.data.length;x++){
                        let option=document.createElement('option');
                        option.setAttribute("value",data.data[x]['id']);
                        option.innerText=data.data[x]['area'];
                        document.getElementById("areas_element").appendChild(option)
                    }


                }
            },
            error:function (data){

            },





        });
    }
</script>


@endsection
