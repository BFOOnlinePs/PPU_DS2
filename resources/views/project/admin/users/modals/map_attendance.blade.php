<div class="modal fade show" id="studentsAttendanceModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3 m-5">
                        <div class="col-md-3 text-center" >
                            <h1><span class="fa fa-map-marker" style="text-align: center; font-size:80px; "></span></h1>
                            <h3>{{__('translate.Map')}}{{--الخريطة--}}</h3>
                            <hr>
                            <p>{{__("translate.In this section, you can view student attendance location")}}{{--في هذا القسم يمكنك رؤية موقع الطالب على الخريطة عند تسجيل الحضور و المغادرة--}}</p>
                        </div>
                        <div class="col-md-9">
                            <form class="form-horizontal" id="studentsAttendanceForm" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <div class="col-md-6 p-3">
                                                <div class="mb-3 row">
                                                    <label for="attendance-map" class="col-md-12">{{__("translate.Student's Check-In Location")}}{{--موقع الطالب عند تسجيل الحضور--}}</label>
                                                    <div id="map1" style="height: 300px;"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 p-3">
                                                <div class="mb-3 row">
                                                <label for="absence-map" class="col-md-12">{{__("translate.Student's Check-Out Location")}}{{--موقع الطالب عند تسجيل المغادرة--}}</label>
                                                <div id="map2" style="height: 300px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
