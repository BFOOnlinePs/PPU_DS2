<div class="modal fade show" id="AddCompaniesCategoriesModal" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="height: 73px;">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="CompaniesCategories" method="post">
                    <div class="row p-3 m-5">
                        <div class="col-md-4 text-center">
                            <h1><span class="fa fa-briefcase" style="text-align: center; font-size:80px; "></span></h1>
                            <h3>{{__('translate.Add Category to the Company')}}{{-- إضافة تصنيف للشركة --}}</h3>
                            <hr>
                            <p>{{__('translate.In this section, you can add a category to the company')}}{{-- في هذا القسم يمكنك إضافة تصنيف للشركة --}}</p>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">{{__('translate.Company Category')}}{{-- تصنيف الشركة --}}</label>
                                        <input autocomplete="off" name="cc_name" id="cc_name" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer ">
                <button type="submit" id="submit" class="btn btn-primary">{{__('translate.Add Category')}}{{-- إضافة تصنيف --}}</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('translate.Cancel')}}{{-- إلغاء --}}</button>
            </div>
            </form>
        </div>
    </div>
</div>
