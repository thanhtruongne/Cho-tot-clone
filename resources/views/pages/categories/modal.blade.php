<div class="modal fade modal_add_permission" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form  _lpchecked="1" action="{{ route('module.permission.type.save') }}" method="post" class="form-ajax" data-success="success_submit">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="modal-header">
                <h4 class="modal-title">@if($model->name) {{ $model->name }} @else {{trans('labutton.add_new')}}  @endif</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                {{-- <div class="form-group form-group--placeholder-label {{$activeName}}" role="group">
                    <label  for="name" class="form-control-placeholder d-block" required="required"> {{ trans('backend.name') }} <span class="text-danger">*</span></label>
                    <input name="name" id="name" type="text" class="form-control" value="{{ $model->name }}" required="required" oninvalid="this.setCustomValidity('{{ trans('lasetting.please_input_permission_group') }}')" onchange="this.setCustomValidity('')">
                </div>

                <div class="form-group form-group--placeholder-label {{$activeDescription}}" >
                    <label for="description" class="form-control-placeholder d-block ">{{trans('latraining.description')}}</label>
                    <textarea cols="15" class="form-control" name="description" rows="2">{{$model->description}}</textarea>
                </div> --}}
                <div class="form-group">
{{--                    <label>{{trans('backend.viewable_units')}} <span class="text-danger">*</span></label>--}}
{{--                    <input type="text" id="agentSearch" class="form-control" placeholder="Nhập tên đơn vị để tìm kiếm" title="Nhập tên đơn vị để tìm kiếm">--}}
                    <div class="list-group checkbox-list-group wrapped_list" style="max-height: 400px;overflow: auto;" id="wrapped_list">
                        <div role="main" id="rolepermission">
                            <nav class="tree-nav">

                                <a class="tree-nav__item-title">
                                    <i class="icon ion-scissors"></i>
                                    {{ trans('lasetting.struct_unit') }}
                                </a>
                                @php
                                    $levelCurrentUnit = \App\Models\Categories\Unit::getMaxLevelUnit(getUserUnit());
                                    $units = $getUnitBylevel($levelCurrentUnit,$roleId,$id);
                                @endphp
                                @foreach($units as $unit)
                                    @php
                                        // $hasChildren = 
                                    @endphp
                                    <details class="tree-nav__item @if($hasChildren) is-expandable @endif" open>
                                        <summary class="tree-nav__item-title ">
                                            <div class="node">{{$unit->name}}
                                                <div class="group-permission @if($permission) group-permission-active @endif" data-unit="{{$unit->id}}">
                                                    <label><input type="checkbox" name="permission[{{$unit->id}}]" value="1" @if($id && $permission=='owner') checked @endif /> Group</label>
                                                    <label><input type="checkbox" name="permission[{{$unit->id}}]" value="2" @if($id && $permission=='group-child') checked @endif /> Group-Child</label>
                                                </div>
                                            </div>
                                        </summary>
                                        {{-- @include("permissiontype::template_node.node",['parentCode'=>$unit->code]) --}}
                                    </details>
                                @endforeach
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <input type="hidden" name="role" value="{{$roleId}}">
                <button type="submit" class="btn btn-save-group-permission"><i class="fa fa-save"></i> {{trans('labutton.save')}}</button>
                <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> {{trans('labutton.close')}}</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.form-group--placeholder-label .form-control').focus(function(){
        $(this).parents('.form-group').addClass('form-group--placeholder-label--active');
    });

    $('.form-control').blur(function(){
        var inputValue = $(this).val();
        if ( inputValue == "" ) {
            $(this).removeClass('form-control--filled');
            $(this).parents('.form-group--placeholder-label').removeClass('form-group--placeholder-label--active');
        } else {
            $(this).addClass('form-control--filled');
        }
    })
</script>
<script>
    $(document).ready(function(){
        $('input[type=checkbox]').on('change',function (){
            let group = $(this).closest('.group-permission');
            $('input[type=checkbox]',group).not(this).prop('checked', false);
            if($(this).is(':checked')){
                group.addClass('group-permission-active' );
            }else{
                group.removeClass('group-permission-active');
            }
        })
    })
    function success_submit(form) {
        $("#app-modal #myModal").modal('hide');
        table.refresh();
    }
    $('.btn-save-group-permission').on('submit' , function(event) {
        if (event.isDefaultPrevented()) {
            return false;
        }

        event.preventDefault();
        var form = $(this).closest('form');
        var formData = new FormData(form[0]);
        var btnsubmit = form.find("button:focus");
        var oldText = btnsubmit.text();
        var currentIcon = btnsubmit.find('i').attr('class');
        var submitSuccess = form.data('success');
        var exists = btnsubmit.find('i').length;
        if (exists>0)
            btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
        else
            btnsubmit.html('<i class="fa fa-spinner fa-spin"></i>'+oldText);

        btnsubmit.prop("disabled", true);
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            dataType: 'json',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done(function(data) {

                show_message(
                    data.message,
                    data.status
                );

                if (data.redirect) {
                    setTimeout(function () {
                        window.location = data.redirect;
                    }, 1000);
                    return false;
                }

            if (exists>0)
                btnsubmit.find('i').attr('class', currentIcon);
            else
                btnsubmit.html(oldText);
            btnsubmit.prop("disabled", false);

            if (data.status === "error") {
                return false;
            }

            if (submitSuccess) {
                eval(submitSuccess)(form);
            }

            return false;
        }).fail(function(data) {
            if (exists>0)
                btnsubmit.find('i').attr('class', currentIcon);
            else
                btnsubmit.html(oldText);
            btnsubmit.prop("disabled", false);

            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });
</script>

